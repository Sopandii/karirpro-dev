<?php get_header(); ?>

<div class="content-grid">
    <div class="job-listings">
        <h2 class="section-title">Hasil Pencarian: "<?php echo get_search_query(); ?>"</h2>
        
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="job-card">
                    <div class="job-card-header">
                        <div>
                            <div class="job-company">
                                <div class="job-company-logo">
                                    <?php echo strtoupper(substr(get_the_title(), 0, 1)); ?>
                                </div>
                                <div>
                                    <span class="job-company-name">
                                        <?php echo esc_html(get_post_meta(get_the_ID(), '_company_name', true) ?: 'KarirPro'); ?>
                                    </span>
                                </div>
                            </div>
                            <h3>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        </div>
                        
                        <?php if ($salary = get_post_meta(get_the_ID(), '_job_salary', true)) : ?>
                            <span class="job-salary"><?php echo esc_html($salary); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <p><?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?></p>
                    
                    <div class="job-meta">
                        <?php if ($location = get_post_meta(get_the_ID(), '_job_location', true)) : ?>
                            <span>📍 <?php echo esc_html($location); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($job_type = get_post_meta(get_the_ID(), '_job_type', true)) : ?>
                            <span>💼 <?php echo esc_html($job_type); ?></span>
                        <?php endif; ?>
                        
                        <span>📅 <?php echo get_the_date(); ?></span>
                    </div>
                </article>
            <?php endwhile; ?>
            
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← Sebelumnya',
                    'next_text' => 'Selanjutnya →',
                ));
                ?>
            </div>
            
        <?php else : ?>
            <div class="job-card">
                <h3>Tidak ada hasil ditemukan</h3>
                <p>Maaf, tidak ada lowongan kerja yang sesuai dengan pencarian Anda. Silakan coba dengan kata kunci yang berbeda.</p>
                
                <form class="search-box" role="search" action="<?php echo esc_url(home_url('/')); ?>" style="margin-top: 20px;">
                    <input type="search" name="s" placeholder="Cari lowongan kerja..." aria-label="Cari lowongan kerja">
                    <button type="submit">🔍 Cari</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <aside class="sidebar" role="complementary">
        <div class="sidebar-widget">
            <h3>Kategori Populer</h3>
            <ul class="category-list">
                <?php
                $categories = get_categories(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 10,
                ));
                foreach ($categories as $category) :
                ?>
                    <li>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                            <?php echo esc_html($category->name); ?>
                            <span class="category-count"><?php echo $category->count; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
</div>

<?php get_footer(); ?>
