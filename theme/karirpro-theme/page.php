<?php get_header(); ?>

<div class="single-job-wrapper page-layout">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="<?php echo home_url('/'); ?>">Beranda</a>
        <span>›</span>
        <span><?php the_title(); ?></span>
    </nav>

    <!-- Page Header -->
    <div class="page-header-styled">
        <h1><?php the_title(); ?></h1>
        <?php if (get_the_excerpt()): ?>
            <p class="page-subtitle"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="page-content-styled">
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
