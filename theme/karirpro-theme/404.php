<?php get_header(); ?>

<div class="page-header">
    <h1>404 - Halaman Tidak Ditemukan</h1>
</div>

<div class="page-content" style="text-align: center; padding: 60px 20px;">
    <h2>Oops! Halaman yang Anda cari tidak ditemukan.</h2>
    <p style="margin: 20px 0;">Halaman mungkin telah dipindahkan, dihapus, atau tidak pernah ada.</p>
    
    <div style="margin: 40px 0;">
        <h3>Apa yang bisa Anda lakukan?</h3>
        <ul style="list-style: none; margin-top: 20px;">
            <li style="margin-bottom: 10px;">🔍 Gunakan kotak pencarian di bawah untuk menemukan lowongan kerja</li>
            <li style="margin-bottom: 10px;">🏠 Kembali ke <a href="<?php echo esc_url(home_url('/')); ?>">halaman utama</a></li>
            <li style="margin-bottom: 10px;">📋 Lihat <a href="<?php echo esc_url(home_url('/category/lowongan-kerja/')); ?>">semua lowongan kerja</a></li>
        </ul>
    </div>
    
    <form class="search-box" role="search" action="<?php echo esc_url(home_url('/')); ?>" style="max-width: 500px; margin: 30px auto;">
        <input type="search" name="s" placeholder="Cari lowongan kerja..." aria-label="Cari lowongan kerja">
        <button type="submit">🔍 Cari</button>
    </form>
</div>

<?php get_footer(); ?>
