<?php get_header(); ?>

<div class="category-archive">
    <!-- Location Header -->
    <div class="category-header">
        <div class="location-header-inner">
            <!-- title --><h1>📍 Lowongan Kerja di <?php single_term_title(); ?></h1>
            <?php if (term_description()) : ?>
                <p class="location-description"><?php echo term_description(); ?></p>
            <?php endif; ?>
            <div class="location-count"><?php global $wp_query; echo $wp_query->found_posts; ?> lowongan ditemukan</div>
        </div>
    </div>

    <div class="content-with-sidebar">
        <div class="job-listings">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php 
                $company = get_post_meta(get_the_ID(), '_company_name', true);
                $location = get_post_meta(get_the_ID(), '_job_location', true);
                $salary = get_post_meta(get_the_ID(), '_job_salary', true);
                $type = get_post_meta(get_the_ID(), '_job_type', true);
                $source = get_post_meta(get_the_ID(), '_job_source', true);
                $apply_url = get_post_meta(get_the_ID(), '_job_source_url', true);
                $post_date = get_the_date('Y-m-d');
                $today = current_time('Y-m-d');
                $is_new = ($post_date === $today);
                if ($company === get_the_title() || empty($company)) { $company = ''; }
                ?>
                
                <article class="job-card">
                    <div class="job-listing-header">
                        <?php if ($is_new): ?>
                            <span class="new-badge">NEW</span>
                        <?php endif; ?>
                        <?php if ($source): ?>
                            <div class="source-badge"><?php echo esc_html($source); ?></div>
                        <?php endif; ?>
                        <time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date('j M Y'); ?>
                        </time>
                    </div>
                    
                    <h3 class="job-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    
                    <div class="job-info-meta">
                        <?php if ($company): ?>
                            <span class="job-meta-item company-meta"><span class="meta-icon">🏢</span><?php echo esc_html($company); ?></span>
                        <?php endif; ?>
                        <?php if ($location): ?>
                            <span class="job-meta-item location-meta"><span class="meta-icon">📍</span><?php echo esc_html($location); ?></span>
                        <?php endif; ?>
                        <?php if ($salary): ?>
                            <span class="job-meta-item salary-meta"><span class="meta-icon">💰</span><?php echo esc_html($salary); ?></span>
                        <?php endif; ?>
                        <?php if ($type): ?>
                            <span class="job-meta-item type-meta"><span class="meta-icon">⏰</span><?php echo esc_html($type); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="job-apply-section">
                        <?php if ($apply_url): ?>
                            <a href="<?php echo esc_url($apply_url); ?>" class="apply-link" target="_blank" rel="noopener">Lamar Sekarang →</a>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="apply-link apply-detail">Lihat Detail →</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
            
            <?php if (paginate_links()): ?>
            <div class="pagination">
                <?php echo paginate_links(array(
                    'prev_text' => '← Sebelumnya',
                    'next_text' => 'Selanjutnya →',
                )); ?>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
                <div class="no-jobs">
                    <h3>Belum ada lowongan di lokasi ini</h3>
                    <p>Saat ini belum ada lowongan kerja di <?php single_term_title(); ?>.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3>Lokasi Populer</h3>
                <ul class="category-list">
                    <?php
                    $locations = get_terms(array('taxonomy' => 'job_location', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10));
                    if (!is_wp_error($locations)) {
                        foreach ($locations as $loc) {
                            $is_current = (get_queried_object_id() === $loc->term_id);
                            echo '<li><a href="' . esc_url(get_term_link($loc)) . '" class="' . ($is_current ? 'current' : '') . '">📍 ' . esc_html($loc->name) . ' <span class="category-count">' . $loc->count . '</span></a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            
            <div class="sidebar-widget">
                <h3>Kategori Populer</h3>
                <ul class="category-list">
                    <?php
                    $categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 10));
                    $icons = array(
                        'teknologi-informasi' => '💻', 'desain-kreatif' => '🎨', 'keuangan' => '💰',
                        'marketing' => '📈', 'sales' => '🤝', 'administrasi' => '📋',
                        'engineering' => '⚙️', 'lowongan-kerja' => '💼',
                        'kesehatan' => '🏥', 'pendidikan' => '🎓',
                    );
                    foreach ($categories as $category) :
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                <span><?php echo ($icons[$category->slug] ?? '📁') . ' ' . esc_html($category->name); ?></span>
                                <span class="category-count"><?php echo $category->count; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
