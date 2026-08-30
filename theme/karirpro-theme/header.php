<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="theme-color" content="#1a73e8">
    <meta name="msapplication-TileColor" content="#1a73e8">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" role="banner">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home" aria-label="KarirPro Beranda">
            <span class="logo-icon">💼</span>
            <span>KarirPro</span>
        </a>
        
        <nav class="main-nav" role="navigation" aria-label="Menu Utama">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php if (is_front_page()) echo 'class="active"'; ?>>Beranda</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/lowongan-kerja/')); ?>" <?php if (is_category('lowongan-kerja')) echo 'class="active"'; ?>>Lowongan Kerja</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/teknologi-informasi/')); ?>" <?php if (is_category('teknologi-informasi')) echo 'class="active"'; ?>>IT & Teknologi</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/marketing/')); ?>" <?php if (is_category('marketing')) echo 'class="active"'; ?>>Marketing</a></li>
                <li><a href="<?php echo esc_url(home_url('/tentang-kami/')); ?>" <?php if (is_page('tentang-kami')) echo 'class="active"'; ?>>Tentang</a></li>
                <li><a href="<?php echo esc_url(home_url('/kontak/')); ?>" <?php if (is_page('kontak')) echo 'class="active"'; ?>>Kontak</a></li>
            </ul>
        </nav>
        
        <button class="mobile-menu-btn" aria-label="Toggle Menu" aria-expanded="false">
            ☰
        </button>
    </div>
</header>

<main id="main-content" class="site-main" role="main">
    <?php karirpro_breadcrumbs(); ?>
