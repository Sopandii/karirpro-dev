<?php get_header(); ?>

<div class="category-archive">
    <!-- Category Header -->
    <div class="category-header">
        <div class="category-header-inner">
            <h1><?php single_cat_title(''); ?></h1>
            <?php if (category_description()) : ?>
                <p class="category-header-desc"><?php echo category_description(); ?></p>
            <?php endif; ?>
            <div class="category-meta">
                <span class="category-post-count"><?php global $wp_query; echo $wp_query->found_posts; ?> lowongan</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
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
                    <!-- Header: Badges + Date -->
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
                    
                    <!-- Title -->
                    <h3 class="job-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    
                    <!-- Job Info Meta -->
                    <div class="job-info-meta">
                        <?php if ($company): ?>
                            <span class="job-meta-item company-meta">
                                <span class="meta-icon">🏢</span><?php echo esc_html($company); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($location): ?>
                            <span class="job-meta-item location-meta">
                                <span class="meta-icon">📍</span><?php echo esc_html($location); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($salary): ?>
                            <span class="job-meta-item salary-meta">
                                <span class="meta-icon">💰</span><?php echo esc_html($salary); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($type): ?>
                            <span class="job-meta-item type-meta">
                                <span class="meta-icon">⏰</span><?php echo esc_html($type); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="job-apply-section">
                        <?php if ($apply_url): ?>
                            <a href="<?php echo esc_url($apply_url); ?>" class="apply-link" target="_blank" rel="noopener">Lamar Sekarang →</a>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="apply-link apply-detail">Lihat Detail →</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
            
            <!-- Pagination -->
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
                    <h3>Belum ada lowongan kerja</h3>
                    <p>Saat ini belum ada lowongan kerja dalam kategori ini. Cek kembali nanti.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <aside class="sidebar">
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
                        $is_current = is_category($category->term_id);
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="<?php echo $is_current ? 'current' : ''; ?>">
                                <span><?php echo ($icons[$category->slug] ?? '📁') . ' ' . esc_html($category->name); ?></span>
                                <span class="category-count"><?php echo $category->count; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="sidebar-widget">
                <h3>Lokasi Populer</h3>
                <ul class="category-list">
                    <?php foreach (array('Jakarta','Bandung','Surabaya','Medan','Yogyakarta') as $loc): ?>
                    <li><a href="<?php echo esc_url(get_term_link($loc, 'job_location')); ?>">📍 <?php echo $loc; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
