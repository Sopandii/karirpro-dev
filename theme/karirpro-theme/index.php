<?php
/**
 * Home Template - Premium Job Portal Layout
 */
get_header();
?>

<div class="karirpro-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-inner">
                <h1 class="hero-headline">Temukan Karir Impian Anda</h1>
                <p class="hero-description">Ribuan lowongan kerja terbaru dari perusahaan terbaik di Indonesia</p>
                
                <!-- Search Form -->
                <form method="get" action="/" class="search-form">
                    <div class="search-input-group">
                        <input type="text" name="s" placeholder="Cari posisi, perusahaan, atau lokasi..." 
                               value="<?php echo esc_attr(get_search_query()); ?>" class="search-field">
                        <button type="submit" class="search-button"><?php esc_html_e('Cari', 'karirpro'); ?></button>
                    </div>
                </form>
                
                <!-- Stats -->
                <div class="site-stats">
                    <div class="stat-item"><span class="stat-number">500+</span><span class="stat-label">Perusahaan</span></div>
                    <div class="stat-item"><span class="stat-number">2.500+</span><span class="stat-label">Lowongan</span></div>
                    <div class="stat-item"><span class="stat-number">1.200+</span><span class="stat-label">Pelamar</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content-wrapper">
        <main class="jobs-main">
            <h2 class="page-title">Lowongan Kerja Terbaru</h2>
            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                
                <?php 
                $company = get_post_meta(get_the_ID(), '_company_name', true);
                $location = get_post_meta(get_the_ID(), '_job_location', true);
                $salary = get_post_meta(get_the_ID(), '_job_salary', true);
                $type = get_post_meta(get_the_ID(), '_job_type', true);
                $source = get_post_meta(get_the_ID(), '_job_source', true);
                $apply_url = get_post_meta(get_the_ID(), '_job_source_url', true);
                $cat = get_the_category();
                
                // Check if post is from today
                $post_date = get_the_date('Y-m-d');
                $today = current_time('Y-m-d');
                $is_new = ($post_date === $today);
                
                // Clean company name - if it's same as title, try to extract
                if ($company === get_the_title() || empty($company)) {
                    $company = '';
                }
                ?>
                
                <article class="job-listing">
                    <!-- Header: Badges + Date -->
                    <div class="job-listing-header">
                        <?php if ($is_new): ?>
                            <span class="new-badge">NEW</span>
                        <?php endif; ?>
                        
                        <?php if ($source): ?>
                            <div class="source-badge"><?php echo esc_html($source); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($cat): ?>
                            <span class="category-badge"><?php echo esc_html($cat[0]->name); ?></span>
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
                                <span class="meta-icon">🏢</span>
                                <?php echo esc_html($company); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($location): ?>
                            <span class="job-meta-item location-meta">
                                <span class="meta-icon">📍</span>
                                <?php echo esc_html($location); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($salary): ?>
                            <span class="job-meta-item salary-meta">
                                <span class="meta-icon">💰</span>
                                <?php echo esc_html($salary); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($type): ?>
                            <span class="job-meta-item type-meta">
                                <span class="meta-icon">⏰</span>
                                <?php echo esc_html($type); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="job-apply-section">
                        <?php if ($apply_url): ?>
                            <a href="<?php echo esc_url($apply_url); ?>" class="apply-link" target="_blank" rel="noopener">
                                Lamar Sekarang →
                            </a>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="apply-link apply-detail">
                                Lihat Detail →
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
                
            <?php endwhile; endif; ?>
            
            <!-- Pagination -->
            <?php if (paginate_links()): ?>
            <div class="pagination">
                <?php echo paginate_links(array(
                    'prev_text' => '← Sebelumnya',
                    'next_text' => 'Selanjutnya →',
                )); ?>
            </div>
            <?php endif; ?>
        </main>
        
        <aside class="jobs-sidebar">
            <div class="sidebar-widget">
                <h3>Kategori Populer</h3>
                <ul>
                    <?php
                    $cats = get_categories(array('orderby' => 'count', 'number' => 8));
                    $icons = array(
                        'teknologi-informasi' => '💻', 'desain-kreatif' => '🎨', 'keuangan' => '💰',
                        'marketing' => '📈', 'sales' => '🤝', 'administrasi' => '📋',
                        'engineering' => '⚙️', 'lowongan-kerja' => '💼',
                        'kesehatan' => '🏥', 'pendidikan' => '🎓',
                    );
                    foreach($cats as $cat):
                    ?>
                        <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                            <span><?php echo ($icons[$cat->slug] ?? '📁') . ' ' . esc_html($cat->name); ?></span>
                            <span class="count"><?php echo $cat->count; ?></span>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="sidebar-widget">
                <h3>Lokasi Populer</h3>
                <ul>
                    <?php foreach (array('Jakarta','Bandung','Surabaya','Medan','Yogyakarta','Semarang') as $loc): ?>
                    <li><a href="<?php echo esc_url(get_term_link($loc, 'job_location')); ?>">📍 <?php echo $loc; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
