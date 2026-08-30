
/* ════════════════════════════════════════
   SEO / INDEXING BOOST (Page 1 Target)
   ════════════════════════════════════════ */

// 1. Preconnect to critical origins for speed
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" />';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />';
    echo '<link rel="dns-prefetch" href="//karirpro.biz.id" />';
}, 1);

// 2. Lazy load images
add_filter('post_thumbnail_html', function($html) {
    if (str_contains($html, 'loading=')) return $html;
    return str_replace('<img ', '<img loading="lazy" ', $html);
}, 10, 1);

// 3. Internal linking: "Baca Juga" in every single post
add_filter('the_content', function($content) {
    if (!is_singular('post') || is_admin() || !in_the_loop()) return $content;
    global $post;
    $cats = wp_get_post_categories($post->ID);
    if (empty($cats)) return $content;
    $related = new WP_Query(array(
        'category__in' => $cats,
        'post__not_in' => array($post->ID),
        'posts_per_page' => 3,
        'orderby' => 'rand',
        'post_status' => 'publish',
    ));
    if (!$related->have_posts()) return $content;
    $html = '<div class="baca-juga-section"><h3 class="baca-juga-title">📚 Baca Juga</h3><ul class="baca-juga-list">';
    while ($related->have_posts()) { $related->the_post();
        $company = get_post_meta(get_the_ID(), '_company_name', true);
        $loc = get_post_meta(get_the_ID(), '_job_location', true);
        $html .= '<li class="baca-juga-item"><a href="'.get_permalink().'" class="baca-juga-link">'.get_the_title().'</a>';
        if ($company || $loc) {
            $html .= '<span class="baca-juga-meta">';
            if ($company) $html .= '🏢 '.esc_html($company);
            if ($company && $loc) $html .= ' · ';
            if ($loc) $html .= '📍 '.esc_html($loc);
            $html .= '</span>';
        }
        $html .= '</li>';
    }
    wp_reset_postdata();
    $html .= '</ul></div>';
    return $content . $html;
}, 99);

// 4. JobPosting schema (detailed) for single posts
add_action('wp_head', function() {
    if (!is_singular('post')) return;
    global $post;
    $company = get_post_meta($post->ID, '_company_name', true);
    $location = get_post_meta($post->ID, '_job_location', true);
    $salary = get_post_meta($post->ID, '_job_salary', true);
    $type = get_post_meta($post->ID, '_job_type', true);
    $src = get_post_meta($post->ID, '_job_source', true);
    $src_url = get_post_meta($post->ID, '_job_source_url', true);
    $expiry = date('Y-m-d', strtotime('+30 days', strtotime(get_the_date('Y-m-d'))));
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => get_the_title(),
        'description' => wp_strip_all_tags(wp_trim_words($post->post_content, 60, '...')),
        'datePosted' => get_the_date('c'),
        'validThrough' => $expiry.'T23:59:59+07:00',
        'employmentType' => $type ?: 'FULL_TIME',
        'hiringOrganization' => array(
            '@type' => 'Organization',
            'name' => $company ?: get_bloginfo('name'),
            'sameAs' => home_url('/'),
        ),
        'jobLocation' => array(
            '@type' => 'Place',
            'address' => array(
                '@type' => 'PostalAddress',
                'addressLocality' => $location ?: 'Indonesia',
                'addressCountry' => 'ID',
            ),
        ),
    );
    if ($salary) {
        $schema['baseSalary'] = array(
            '@type' => 'MonetaryAmount',
            'currency' => 'IDR',
            'value' => array('@type' => 'QuantitativeValue', 'value' => $salary, 'unitText' => 'MONTH'),
        );
    }
    if ($src_url) {
        $schema['applicationUrl'] = $src_url;
        $schema['url'] = get_permalink();
    }
    echo '<script type="application/ld+json">'.json_encode($schema, JSON_UNESCAPED_SLASHES).'</script>';
}, 5);

// 5. BreadcrumbList schema
add_action('wp_head', function() {
    if (is_front_page()) return;
    $items = array(array('@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => home_url('/')));
    if (is_category() || is_singular('post')) {
        if (is_singular('post')) {
            $cats = get_the_category();
            if ($cats) {
                $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => $cats[0]->name, 'item' => get_category_link($cats[0]->term_id));
                $items[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title());
            }
        } elseif (is_category()) {
            $cat = get_queried_object();
            $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => $cat->name);
        }
    } elseif (is_page()) {
        $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => get_the_title());
    }
    $schema = array('@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items);
    echo '<script type="application/ld+json">'.json_encode($schema, JSON_UNESCAPED_SLASHES).'</script>';
}, 6);

// 6. WebSite schema with SearchAction (Sitelinks Search Box)
add_action('wp_head', function() {
    if (!is_front_page()) return;
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array('@type' => 'EntryPoint', 'urlTemplate' => home_url('/?s={search_term_string}')),
            'query-input' => 'required name=search_term_string',
        ),
    );
    echo '<script type="application/ld+json">'.json_encode($schema, JSON_UNESCAPED_SLASHES).'</script>';
}, 7);

// 7. Force Yoast meta description for homepage if empty
add_filter('wpseo_frontend_presenters', '__return_empty_array', 99);
// 8. Default meta description via Yoast filter
add_filter('wpseo_metadesc', function($desc) {
    if (is_front_page() && empty($desc)) {
        return 'Cari lowongan kerja terbaru di Indonesia dari ribuan perusahaan terpercaya. Update setiap hari, kategori lengkap (IT, Marketing, Keuangan, dll) di KarirPro.';
    }
    return $desc;
});
