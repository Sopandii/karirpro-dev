<?php get_header(); ?>

<div class="single-job-wrapper">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        $company  = get_post_meta(get_the_ID(), '_company_name', true);
        $location = get_post_meta(get_the_ID(), '_job_location', true);
        $salary   = get_post_meta(get_the_ID(), '_job_salary', true);
        $type     = get_post_meta(get_the_ID(), '_job_type', true);
        $source   = get_post_meta(get_the_ID(), '_job_source', true);
        $src_url  = get_post_meta(get_the_ID(), '_job_source_url', true);
        $cats     = get_the_category();
        
        // Clean company name
        if ($company === get_the_title() || empty($company)) {
            $company = '';
        }
    ?>

    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="<?php echo home_url('/'); ?>">Beranda</a>
        <span>›</span>
        <?php if ($cats): ?>
            <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"><?php echo esc_html($cats[0]->name); ?></a>
            <span>›</span>
        <?php endif; ?>
        <span><?php the_title(); ?></span>
    </nav>

    <!-- Job Header -->
    <div class="job-detail-header" itemscope itemtype="https://schema.org/JobPosting">
        <!-- Company + Source Row -->
        <div class="job-detail-top">
            <div class="job-company-info">
                <div class="company-logo-lg">
                    <?php echo $company ? strtoupper(mb_substr($company, 0, 1)) : '💼'; ?>
                </div>
                <div class="company-details">
                    <?php if ($company): ?>
                        <div itemprop="hiringOrganization" itemscope itemtype="https://schema.org/Organization">
                            <strong itemprop="name" class="company-name-lg"><?php echo esc_html($company); ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="company-badges">
                        <?php if ($cats): ?>
                            <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>" class="category-badge">
                                <?php echo esc_html($cats[0]->name); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($source): ?>
                            <span class="source-badge"><?php echo esc_html($source); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Title -->
        <h1 itemprop="title" class="job-detail-title"><?php the_title(); ?></h1>

        <!-- Date -->
        <time itemprop="datePosted" datetime="<?php echo get_the_date('c'); ?>" class="job-detail-date">
            Dipublikasikan <?php echo get_the_date('j F Y'); ?>
        </time>

        <!-- Meta Grid -->
        <div class="job-meta-grid">
            <?php if ($location): ?>
                <div class="meta-card" itemprop="jobLocation" itemscope itemtype="https://schema.org/Place">
                    <strong>📍 Lokasi</strong>
                    <span itemprop="address"><?php echo esc_html($location); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($type): ?>
                <div class="meta-card">
                    <strong>💼 Tipe Pekerjaan</strong>
                    <?php echo esc_html($type); ?>
                </div>
            <?php endif; ?>
            <?php if ($salary): ?>
                <div class="meta-card" itemprop="baseSalary" itemscope itemtype="https://schema.org/MonetaryAmount">
                    <strong>💰 Gaji</strong>
                    <span itemprop="value"><?php echo esc_html($salary); ?></span>
                </div>
            <?php endif; ?>
            <div class="meta-card">
                <strong>📅 Deadline</strong>
                <?php echo date('j F Y', strtotime('+30 days', strtotime(get_the_date('Y-m-d')))); ?>
            </div>
        </div>
    </div>

    <!-- Job Content -->
    <div class="job-content">
        <?php the_content(); ?>
    </div>

    <!-- Apply Section -->
    <div class="apply-section">
        <h3>Tertarik dengan posisi ini?</h3>
        <p><?php echo $source ? 'Lamar langsung di ' . esc_html($source) : 'Ajukan lamaran Anda sekarang'; ?></p>
        <?php if ($src_url): ?>
            <a href="<?php echo esc_url($src_url); ?>" class="apply-btn" target="_blank" rel="noopener noreferrer">
                🚀 Lamar Sekarang<?php echo $source ? ' di ' . esc_html($source) : ''; ?> →
            </a>
        <?php else: ?>
            <a href="#" class="apply-btn disabled" aria-disabled="true">
                🔗 Link Lamar Belum Tersedia
            </a>
        <?php endif; ?>
    </div>

    <!-- Share -->
    <div class="share-section">
        <h3>📤 Bagikan Lowongan Ini</h3>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" class="share-btn share-fb" target="_blank" rel="noopener">Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" class="share-btn share-tw" target="_blank" rel="noopener">Twitter</a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" class="share-btn share-li" target="_blank" rel="noopener">LinkedIn</a>
            <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" class="share-btn share-wa" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </div>

    <!-- Related Jobs -->
    <?php
    $related = new WP_Query(array(
        'category__in'   => wp_get_post_categories(get_the_ID()),
        'post__not_in'   => array(get_the_ID()),
        'posts_per_page' => 4,
        'orderby'        => 'rand',
    ));
    if ($related->have_posts()):
    ?>
    <div class="related-jobs">
        <h2>Lowongan Terkait</h2>
        <div class="related-jobs-grid">
        <?php while ($related->have_posts()): $related->the_post();
            $r_company = get_post_meta(get_the_ID(), '_company_name', true);
            $r_location = get_post_meta(get_the_ID(), '_job_location', true);
            $r_salary = get_post_meta(get_the_ID(), '_job_salary', true);
            $r_source = get_post_meta(get_the_ID(), '_job_source', true);
            $r_apply = get_post_meta(get_the_ID(), '_job_source_url', true);
            
            if ($r_company === get_the_title() || empty($r_company)) {
                $r_company = '';
            }
        ?>
            <article class="job-listing related-job-card">
                <h3 class="job-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="job-info-meta">
                    <?php if ($r_company): ?>
                        <span class="job-meta-item company-meta"><span class="meta-icon">🏢</span> <?php echo esc_html($r_company); ?></span>
                    <?php endif; ?>
                    <?php if ($r_location): ?>
                        <span class="job-meta-item location-meta"><span class="meta-icon">📍</span> <?php echo esc_html($r_location); ?></span>
                    <?php endif; ?>
                    <?php if ($r_salary): ?>
                        <span class="job-meta-item salary-meta"><span class="meta-icon">💰</span> <?php echo esc_html($r_salary); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($r_apply): ?>
                    <a href="<?php echo esc_url($r_apply); ?>" class="apply-link" target="_blank" rel="noopener">Lamar Sekarang →</a>
                <?php else: ?>
                    <a href="<?php the_permalink(); ?>" class="apply-link apply-detail">Lihat Detail →</a>
                <?php endif; ?>
            </article>
        <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bottom Widgets -->
    <div class="bottom-widgets">
        <div class="sidebar-widget">
            <h3>Kategori Populer</h3>
            <ul>
                <?php
                $cats = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 8));
                $icons = array(
                    'teknologi-informasi' => '💻', 'desain-kreatif' => '🎨', 'keuangan' => '💰',
                    'marketing' => '📈', 'sales' => '🤝', 'administrasi' => '📋',
                    'engineering' => '⚙️', 'lowongan-kerja' => '💼',
                    'kesehatan' => '🏥', 'pendidikan' => '🎓',
                );
                foreach ($cats as $c):
                ?>
                <li>
                    <a href="<?php echo esc_url(get_category_link($c->term_id)); ?>">
                        <span><?php echo ($icons[$c->slug] ?? '📁') . ' ' . esc_html($c->name); ?></span>
                        <span class="count"><?php echo $c->count; ?></span>
                    </a>
                </li>
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
    </div>

    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
