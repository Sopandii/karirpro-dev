<?php
// Enable Application Passwords for n8n API
add_filter("wp_is_application_passwords_available", "__return_true");
/**
 * KarirPro Theme Functions
 * Standalone classic theme - no parent theme dependency
 *
 * @package KarirPro
 * @version 2.0.0
 */

if (!defined('ABSPATH')) exit;

define('KARIRPRO_VERSION', '2.1.0');
define('KARIRPRO_DIR', get_stylesheet_directory());
define('KARIRPRO_URI', get_stylesheet_directory_uri());

/* ─── Styles ─── */
function karirpro_enqueue_styles() {
    wp_enqueue_style('karirpro-style', KARIRPRO_URI . '/style.css', array(), KARIRPRO_VERSION);

    // Remove block theme bloat
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('wp-embed-template-styles');
    wp_dequeue_style('twentytwentyfive-style');
}
add_action('wp_enqueue_scripts', 'karirpro_enqueue_styles', 20);

/* ─── Clean up head ─── */
function karirpro_cleanup_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'karirpro_cleanup_head');

/* ─── Register Job Meta ─── */
function karirpro_register_job_meta() {
    $fields = array(
        '_company_name'   => 'string',
        '_job_location'   => 'string',
        '_job_salary'     => 'string',
        '_job_type'       => 'string',
        '_job_source'     => 'string',
        '_job_source_url' => 'string',
    );
    foreach ($fields as $key => $type) {
        register_post_meta('post', $key, array(
            'type'              => $type,
            'single'            => true,
            'show_in_rest'      => true,
            'auth_callback'     => '__return_true',
            'sanitize_callback' => ($key === '_job_source_url') ? 'esc_url_raw' : 'sanitize_text_field',
        ));
    }
}
add_action('init', 'karirpro_register_job_meta');

/* ─── Meta Box ─── */
function karirpro_add_meta_boxes() {
    add_meta_box('karirpro_job_meta', 'Data Lowongan Kerja', 'karirpro_render_meta_box', 'post', 'side', 'default');
}
add_action('add_meta_boxes', 'karirpro_add_meta_boxes');

function karirpro_render_meta_box($post) {
    wp_nonce_field('karirpro_job_meta', 'karirpro_nonce');
    $fields = array(
        '_company_name'   => 'Nama Perusahaan',
        '_job_location'   => 'Lokasi',
        '_job_salary'     => 'Gaji',
        '_job_type'       => 'Tipe (Full Time/Part Time)',
        '_job_source'     => 'Sumber (LinkedIn, JobStreet)',
        '_job_source_url' => 'URL Lamaran Asli',
    );
    foreach ($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        echo '<p><label for="' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label><br>';
        echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" style="width:100%"></p>';
    }
}

function karirpro_save_meta($post_id) {
    if (!isset($_POST['karirpro_nonce']) || !wp_verify_nonce($_POST['karirpro_nonce'], 'karirpro_job_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    foreach (array('_company_name','_job_location','_job_salary','_job_type','_job_source','_job_source_url') as $f) {
        if (isset($_POST[$f])) update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
    }
}
add_action('save_post', 'karirpro_save_meta');

/* ─── Schema.org JobPosting ─── */
function karirpro_job_schema() {
    if (!is_singular('post')) return;
    global $post;
    $company  = get_post_meta($post->ID, '_company_name', true);
    $location = get_post_meta($post->ID, '_job_location', true);
    $salary   = get_post_meta($post->ID, '_job_salary', true);
    $type     = get_post_meta($post->ID, '_job_type', true);
    $src_url  = get_post_meta($post->ID, '_job_source_url', true);

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'JobPosting',
        'title'         => get_the_title(),
        'description'   => wp_strip_all_tags(get_the_content()),
        'datePosted'    => get_the_date('c'),
        'validThrough'  => date('c', strtotime('+30 days')),
        'employmentType' => strtoupper(str_replace(' ', '_', $type ?: 'FULL_TIME')),
        'hiringOrganization' => array(
            '@type' => 'Organization',
            'name'  => $company ?: get_bloginfo('name'),
            'sameAs'=> home_url(),
        ),
        'jobLocation' => array(
            '@type'   => 'Place',
            'address' => array(
                '@type'           => 'PostalAddress',
                'addressLocality' => $location ?: 'Indonesia',
                'addressCountry'  => 'ID',
            ),
        ),
    );
    if ($salary) {
        $schema['baseSalary'] = array(
            '@type' => 'MonetaryAmount', 'currency' => 'IDR',
            'value' => array('@type' => 'QuantitativeValue', 'value' => $salary, 'unitText' => 'MONTH'),
        );
    }
    if ($src_url) {
        $schema['url'] = $src_url;
        $schema['applicationUrl'] = $src_url;
    }
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_head', 'karirpro_job_schema', 2);

/* ─── Breadcrumbs ─── */
function karirpro_breadcrumbs() {
    if (is_front_page()) return;
    echo '<nav class="breadcrumbs" aria-label="Breadcrumb"><a href="' . esc_url(home_url('/')) . '">Beranda</a>';
    if (is_category()) {
        echo ' <span>›</span> <span>' . esc_html(single_cat_title('', false)) . '</span>';
    } elseif (is_singular('post')) {
        $cats = get_the_category();
        if ($cats) {
            echo ' <span>›</span> <a href="' . esc_url(get_category_link($cats[0]->term_id)) . '">' . esc_html($cats[0]->name) . '</a>';
        }
        echo ' <span>›</span> <span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_search()) {
        echo ' <span>›</span> <span>Hasil Pencarian</span>';
    } elseif (is_page()) {
        echo ' <span>›</span> <span>' . esc_html(get_the_title()) . '</span>';
    }
    echo '</nav>';
}

/* ─── Excerpt length ─── */
function karirpro_excerpt_length($length) { return 25; }
add_filter('excerpt_length', 'karirpro_excerpt_length');

/* ─── Theme support ─── */
function karirpro_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('automatic-feed-links');
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'karirpro_setup');
// ==========================================
// FIX: Allow REST API to update Yoast meta fields
// ==========================================
function karirpro_register_yoast_meta_fields() {
    // Enable focus keyword update via REST API
    register_post_meta( 'post', '_yoast_wpseo_focuskw', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'auth_callback'     => '__return_true', // BOLEH dikasih key n8n
    ) );

    // Enable meta description update via REST API
    register_post_meta( 'post', '_yoast_wpseo_metadesc', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'auth_callback'     => '__return_true',
    ) );
}
add_action( 'init', 'karirpro_register_yoast_meta_fields' );
// BACA JUGA: Internal links
function karirpro_baca_juga($content) {
    if (!is_singular("post") || is_admin()) return $content;
    global $post;
    $cats = wp_get_post_categories($post->ID);
    if (empty($cats)) return $content;
    $related = new WP_Query(array(
        "category__in" => $cats,
        "post__not_in" => array($post->ID),
        "posts_per_page" => 3,
        "orderby" => "rand",
        "post_status" => "publish",
    ));
    if (!$related->have_posts()) return $content;
    $html = "<div class=\"baca-juga-section\">";
    $html .= "<h3 class=\"baca-juga-title\">📚 Baca Juga</h3>";
    $html .= "<ul class=\"baca-juga-list\">";
    while ($related->have_posts()) {
        $related->the_post();
        $company = get_post_meta(get_the_ID(), "_company_name", true);
        $loc = get_post_meta(get_the_ID(), "_job_location", true);
        $html .= "<li class=\"baca-juga-item\">";
        $html .= "<a href=\"" . get_permalink() . "\" class=\"baca-juga-link\">" . get_the_title() . "</a>";
        if ($company || $loc) {
            $html .= "<span class=\"baca-juga-meta\">";
            if ($company) $html .= "🏢 " . esc_html($company);
            if ($company && $loc) $html .= " · ";
            if ($loc) $html .= "📍 " . esc_html($loc);
            $html .= "</span>";
        }
        $html .= "</li>";
    }
    wp_reset_postdata();
    $html .= "</ul></div>";
    return $content . $html;
}
add_filter("the_content", "karirpro_baca_juga");

// ==========================================
// CUSTOM TAXONOMY: Job Location
// ==========================================
function karirpro_register_location_taxonomy() {
    register_taxonomy("job_location", "post", array(
        "labels" => array(
            "name" => "Lokasi Kerja",
            "singular_name" => "Lokasi",
            "search_items" => "Cari Lokasi",
            "all_items" => "Semua Lokasi",
            "edit_item" => "Edit Lokasi",
            "add_new_item" => "Tambah Lokasi Baru",
            "new_item_name" => "Nama Lokasi Baru",
        ),
        "hierarchical" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_rest" => true,
        "rewrite" => array("slug" => "lokasi"),
        "query_var" => true,
    ));
}
add_action("init", "karirpro_register_location_taxonomy");

// Auto-assign location taxonomy from meta field
function karirpro_sync_location_taxonomy($post_id) {
    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return;
    if (!current_user_can("edit_post", $post_id)) return;
    
    $location = get_post_meta($post_id, "_job_location", true);
    if (empty($location)) return;
    
    // Clean location name
    $location = trim($location);
    $location = ucwords(strtolower($location));
    
    // Set the taxonomy term
    wp_set_object_terms($post_id, $location, "job_location", false);
}
add_action("save_post", "karirpro_sync_location_taxonomy");
