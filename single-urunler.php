<?php
/**
 * Tekil ürün sayfası - HTML taslağına göre düzenlenmiş
 */

get_header(); 

$theme = new AMP_Responsive_Theme();
$is_mobile = $theme->is_mobile();
$is_amp = $theme->is_amp();

if (have_posts()): 
    while (have_posts()): the_post();
        $post_id = get_the_ID();
        $title = get_the_title();
        $content = get_the_content();
        $phone = get_product_phone($post_id);
        $location = get_product_location($post_id);
        $age = get_product_age($post_id);
        $price = get_product_price($post_id);
        $boy = get_product_meta($post_id, 'urun_boy', '');
        $kilo = get_product_meta($post_id, 'urun_kilo', '');
        $yer = get_product_meta($post_id, 'urun_yer', '');
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
        $categories = get_the_terms($post_id, 'urun_kategori');
        
        // Ek görseller için gallery images dizisi
        $gallery_images = array();
        for ($i = 1; $i <= 10; $i++) {
            $image_id = get_post_meta($post_id, 'urun_gorsel_' . $i, true);
            if ($image_id) {
                $gallery_images[] = wp_get_attachment_image_url($image_id, 'large');
            }
        }
        
        // Eğer featured image varsa gallery'ye ekle
        if ($featured_image) {
            array_unshift($gallery_images, $featured_image);
        }
?>

<div class="wrap">
    <!-- Site logosu -->
    <div class="text-center">
        <a href="<?php echo home_url(); ?>">
            <?php if ($is_amp): ?>
                <amp-img src="<?php echo get_template_directory_uri(); ?>/logo.jpg" 
                         alt="<?php bloginfo('name'); ?>" 
                         width="300" height="100" layout="responsive">
                </amp-img>
            <?php else: ?>
                <img src="<?php echo get_template_directory_uri(); ?>/logo.jpg" 
                     alt="<?php bloginfo('name'); ?>" class="site-logo">
            <?php endif; ?>
        </a>
    </div>
    
    <!-- Ana başlık -->
    <div class="text-center sitebaslik">
        <h1><?php echo esc_html($title); ?></h1>
    </div>
    
    <div class="margintop20 clearfix">
        <!-- Carousel Gallery -->
        <?php if (!empty($gallery_images)): ?>
            <?php if ($is_amp): ?>
                <amp-carousel autoplay delay="3000" height="400" layout="responsive" lightbox type="slides" width="600">
                    <?php foreach ($gallery_images as $gallery_image): ?>
                        <amp-img alt="<?php echo esc_attr($title); ?>" 
                                 height="400" layout="responsive" 
                                 src="<?php echo esc_url($gallery_image); ?>" 
                                 title="<?php echo esc_attr($title); ?>" 
                                 width="600">
                        </amp-img>
                    <?php endforeach; ?>
                </amp-carousel>
            <?php else: ?>
                <div class="product-image-slider">
                    <div class="main-image-container">
                        <img src="<?php echo esc_url($gallery_images[0]); ?>" alt="<?php echo esc_attr($title); ?>" class="main-product-image" id="mainImage">
                    </div>
                    
                    <?php if (count($gallery_images) > 1): ?>
                        <div class="gallery-thumbs">
                            <?php foreach ($gallery_images as $index => $gallery_image): ?>
                                <img src="<?php echo esc_url($gallery_image); ?>" 
                                     alt="<?php echo esc_attr($title); ?>" 
                                     class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>"
                                     onclick="changeMainImage('<?php echo esc_url($gallery_image); ?>', this)">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="clearfix"></div>
        
        <!-- WhatsApp İletişim Butonu -->
        <?php if ($phone): ?>
            <div class="nav-links text-center">
                <h2>
                    <a class="buttons whatsapp" 
                       href="https://api.whatsapp.com/send?phone=<?php echo esc_attr(str_replace(['+', ' ', '-', '(', ')'], '', $phone)); ?>&text=Merhaba, <?php echo urlencode($title); ?> ilanınızı gördüm." 
                       target="_blank">
                        <span><i class="fa fa-whatsapp"></i></span> WhatsApp'tan Ulaş
                    </a>
                </h2>
            </div>
        <?php endif; ?>
        
        <div class="clearfix"></div>
        
        <!-- Kişisel Bilgiler Tablosu -->
        <section id="kisiselBilgi">
            <ul>
                <li class="baslik"><b>İsim</b></li>
                <li class="veri"><?php echo esc_html($title); ?></li>
                <p class="ayir"></p>
                
                <?php if ($age): ?>
                    <li class="baslik2"><b>Yaş</b></li>
                    <li class="veri2"><?php echo esc_html($age); ?></li>
                    <p class="ayir"></p>
                <?php endif; ?>
                
                <li class="baslik"><b>Bölge</b></li>
                <li class="veri"><?php echo $location ? esc_html($location) : 'İstanbul'; ?></li>
                <p class="ayir"></p>
                
                <?php if ($boy): ?>
                    <li class="baslik2"><b>Boy</b></li>
                    <li class="veri2"><?php echo esc_html($boy); ?></li>
                    <p class="ayir"></p>
                <?php endif; ?>
                
                <li class="baslik"><b>Yer</b></li>
                <li class="veri"><?php echo $yer ? esc_html($yer) : 'Ev, Otel, Rezidans'; ?></li>
                <p class="temizle"></p>
                
                <?php if ($kilo): ?>
                    <li class="baslik2"><b>Kilo</b></li>
                    <li class="veri2"><?php echo esc_html($kilo); ?></li>
                    <p class="temizle"></p>
                <?php endif; ?>
                
                <?php if ($price): ?>
                    <li class="baslik"><b>Fiyat</b></li>
                    <li class="veri"><?php echo esc_html($price); ?></li>
                    <p class="temizle"></p>
                <?php endif; ?>
            </ul>
        </section>
        
        <div class="margintop20 clearfix"><br></div>
        
        <!-- Ürün Açıklaması -->
        <?php if ($content): ?>
            <div class="product-description">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php else: ?>
            <div class="default-description">
                <p>Merhaba ben <?php echo esc_html($title); ?>.</p>
                <br>
                <?php if ($age): ?>
                    <p><?php echo esc_html($age); ?> yaşındayım.</p>
                    <br>
                <?php endif; ?>
                <p>Görüşmelerimi <?php echo $location ? esc_html($location) : 'İstanbul'; ?> genelinde <?php echo $yer ? esc_html($yer) : 'Ev, Otel ve Rezidanslara gelerek'; ?> yapıyorum.</p>
            </div>
        <?php endif; ?>
        
        <!-- WhatsApp İletişim (Alt) -->
        <?php if ($phone): ?>
            <div class="nav-links text-center">
                <h2>
                    <a class="buttons whatsapp" 
                       href="https://api.whatsapp.com/send?phone=<?php echo esc_attr(str_replace(['+', ' ', '-', '(', ')'], '', $phone)); ?>&text=Merhaba, <?php echo urlencode($title); ?> ilanınızı gördüm." 
                       target="_blank">
                        <span><i class="fa fa-whatsapp"></i></span>WhatsApp : <?php echo esc_html($phone); ?>
                    </a>
                </h2>
            </div>
        <?php endif; ?>
        
        <div class="margintop20 clearfix"></div>
        

<!-- Footer - Ana sayfaya dönüş linki -->
<footer class="istanbul-amp-footer">
    <div class="istanbul-amp-copyright">
        <h2>
            <a href="<?php echo home_url(); ?>" target="_top">
                <i class="fa fa-external-link-square"></i> İlanlara geri dönmek için tıkla
            </a>
        </h2>
    </div>
</footer>

<!-- Uyarı Bölümleri -->
<div class="warning-section" style="padding: 15px; margin: 20px 0;">
    <h4><b>Dikkat :</b> Görüşme öncesi sizden ödeme talep eden kişilere itibar etmeyiniz, sitemiz sorumluluk kabul etmemektedir. Eğer böyle bir durumla karşılaşırsanız ödeme talep eden kişiyi lütfen şikayet ediniz.</h4>
    <h5>Not : İlanlarda yayımlanan fotoğraflar profil sahipleri tarafından eklemektedir. Fotoğraflarla ilgili şikayetinizi önemsiyoruz, herhangi bir sorunda kısa sürede fotoğraflar silinmektedir.</h5>
</div>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?php echo esc_js($title); ?>",
    "description": "<?php echo esc_js(wp_strip_all_tags(get_the_excerpt())); ?>",
    "image": "<?php echo esc_url($featured_image); ?>",
    "offers": {
        "@type": "Offer",
        "availability": "https://schema.org/InStock",
        "priceCurrency": "TRY",
        <?php if ($price): ?>
        "price": "<?php echo esc_js(preg_replace('/[^0-9.]/', '', $price)); ?>",
        <?php endif; ?>
        "seller": {
            "@type": "Person",
            "name": "<?php echo esc_js($title); ?>"
        }
    },
    "brand": {
        "@type": "Brand",
        "name": "<?php bloginfo('name'); ?>"
    },
    "category": "<?php echo !empty($categories) ? esc_js($categories[0]->name) : 'Genel'; ?>",
    "url": "<?php echo get_permalink(); ?>"
}
</script>

<?php if (!$is_amp): ?>
<script>
// Gallery resim değiştirme fonksiyonu
function changeMainImage(imageSrc, thumbElement) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Aktif thumb'ı güncelle
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbElement.classList.add('active');
}
</script>
<?php endif; ?>

<?php
    endwhile;
endif;

get_footer(); 
?>