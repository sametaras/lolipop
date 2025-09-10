<?php
/**
 * Footer template
 */

$theme = new AMP_Responsive_Theme();
$is_mobile = $theme->is_mobile();
$is_amp = $theme->is_amp();
?>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-content">
                <div class="footer-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'footer-menu',
                        'container' => false,
                        'fallback_cb' => false,
                    ));
                    ?>
                </div>
                
                <div class="footer-info">
                    <p class="copyright">
                        <?php bloginfo('name'); ?>
                    </p>
                    
                </div>
                
            </div>
        </footer>
        
        <!-- Schema.org Structured Data -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "<?php bloginfo('name'); ?>",
            "url": "<?php echo home_url(); ?>",
            "description": "<?php bloginfo('description'); ?>",
            "logo": "<?php echo get_template_directory_uri(); ?>/assets/images/logo.png",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+90-555-555-5555",
                "contactType": "customer service"
            },
            "sameAs": [
                "https://www.facebook.com/yourpage",
                "https://www.twitter.com/yourpage",
                "https://www.instagram.com/yourpage"
            ]
        }
        </script>
        
        <?php if ($is_amp): ?>
            <!-- AMP Analytics -->
            <amp-analytics type="gtag" data-credentials="include">
                <script type="application/json">
                {
                    "vars": {
                        "gtag_id": "GA_MEASUREMENT_ID",
                        "config": {
                            "GA_MEASUREMENT_ID": {
                                "groups": "default"
                            }
                        }
                    }
                }
                </script>
            </amp-analytics>
            
            <!-- AMP pozisyonlu reklam alanı (isteğe bağlı) -->
            <?php if ($is_mobile): ?>
                <div class="amp-ad-container">
                    <!-- AMP reklam kodu buraya eklenebilir -->
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Normal JavaScript dosyaları -->
            <script>
                // Sidebar toggle fonksiyonu
                function toggleSidebar() {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar) {
                        sidebar.classList.toggle('active');
                    }
                }
                
                // Smooth scroll
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });
                
                // Lazy loading for images (non-AMP)
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        });
                    });
                    
                    document.querySelectorAll('img[data-src]').forEach(img => {
                        imageObserver.observe(img);
                    });
                }
            </script>
            
            <?php wp_footer(); ?>
        <?php endif; ?>
        
    </div> <!-- .main-wrapper kapatma -->
</body>
</html>