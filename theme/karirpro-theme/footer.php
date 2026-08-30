</main>

<footer class="site-footer" role="contentinfo">
    <div class="footer-inner">
        <div class="footer-section">
            <h4>KarirPro</h4>
            <p>Portal lowongan kerja terlengkap di Indonesia. Temukan karir impian Anda bersama kami.</p>
        </div>
        
        <div class="footer-section">
            <h4>Kategori</h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/category/teknologi-informasi/')); ?>">Teknologi Informasi</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/marketing/')); ?>">Marketing</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/keuangan/')); ?>">Keuangan</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/kesehatan/')); ?>">Kesehatan</a></li>
                <li><a href="<?php echo esc_url(home_url('/category/pendidikan/')); ?>">Pendidikan</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h4>Lokasi</h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/?s=lowongan+kerja+jakarta')); ?>">Lowongan Kerja Jakarta</a></li>
                <li><a href="<?php echo esc_url(home_url('/?s=lowongan+kerja+bandung')); ?>">Lowongan Kerja Bandung</a></li>
                <li><a href="<?php echo esc_url(home_url('/?s=lowongan+kerja+surabaya')); ?>">Lowongan Kerja Surabaya</a></li>
                <li><a href="<?php echo esc_url(home_url('/?s=lowongan+kerja+medan')); ?>">Lowongan Kerja Medan</a></li>
                <li><a href="<?php echo esc_url(home_url('/?s=lowongan+kerja+yogyakarta')); ?>">Lowongan Kerja Yogyakarta</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h4>Informasi</h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/tentang-kami/')); ?>">Tentang Kami</a></li>
                <li><a href="<?php echo esc_url(home_url('/kontak/')); ?>">Hubungi Kami</a></li>
                <li><a href="<?php echo esc_url(home_url('/kebijakan-privasi/')); ?>">Kebijakan Privasi</a></li>
                <li><a href="<?php echo esc_url(home_url('/syarat-ketentuan/')); ?>">Syarat & Ketentuan</a></li>
                <li><a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>">Sitemap</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> KarirPro. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<!-- Mobile Menu Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const mainNav = document.querySelector('.main-nav');
    
    if (menuBtn && mainNav) {
        menuBtn.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('active');
            this.textContent = isExpanded ? '☰' : '✕';
        });
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
