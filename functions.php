<?php
/**
 * Theme Name: AMP Responsive Theme
 * Description: Modern AMP uyumlu tema. Mobilde ürün listesi, PC'de makale listesi gösterir.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: amp-responsive
 */

// functions.php

// Güvenlik kontrolü
if (!defined('ABSPATH')) {
    exit;
}

class AMP_Responsive_Theme {
    
    public function __construct() {
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'register_post_types'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_filter('body_class', array($this, 'body_classes'));
        add_action('wp_head', array($this, 'add_amp_boilerplate'));
    }
    
    /**
     * Tema kurulumu
     */
    public function theme_setup() {
        // Tema desteği
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        
        // Menü konumları
        register_nav_menus(array(
            'primary' => __('Ana Menü', 'amp-responsive'),
            'mobile' => __('Mobil Menü', 'amp-responsive'),
        ));
        
        // Görsel boyutları
        add_image_size('product-thumb', 150, 275, true);
        add_image_size('article-thumb', 300, 200, true);
        add_image_size('gallery-thumb', 150, 150, true);
        add_image_size('gallery-large', 800, 600, true);
    }
    
    /**
     * Script ve style dosyaları
     */
    public function enqueue_scripts() {
        // AMP için sadece gerekli CSS'ler
        if ($this->is_amp()) {
            // AMP için inline CSS kullanacağız
            return;
        }
        
        wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), '1.0.0');
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&family=Khand:wght@400;500&display=swap', array(), null);
        
        // Admin için media uploader
        if (is_admin()) {
            wp_enqueue_media();
            wp_enqueue_script('theme-admin-js', get_template_directory_uri() . '/js/admin.js', array('jquery'), '1.0.0', true);
        }
    }
    
    /**
     * Özel post type'ları kaydet
     */
    public function register_post_types() {
        // Ürünler post type
        register_post_type('urunler', array(
            'labels' => array(
                'name' => 'Ürünler',
                'singular_name' => 'Ürün',
                'add_new' => 'Yeni Ürün',
                'add_new_item' => 'Yeni Ürün Ekle',
                'edit_item' => 'Ürünü Düzenle',
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-products',
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'show_in_rest' => true,
        ));
        
        // Ürün kategorileri
        register_taxonomy('urun_kategori', 'urunler', array(
            'labels' => array(
                'name' => 'Ürün Kategorileri',
                'singular_name' => 'Ürün Kategorisi',
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
        ));
    }
    
    /**
     * Meta etiketleri ekle
     */
    public function add_meta_tags() {
        echo '<meta charset="utf-8">' . "\n";
        echo '<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">' . "\n";
        
        if ($this->is_amp()) {
            echo '<link rel="canonical" href="' . get_permalink() . '">' . "\n";
        }
    }
    
    /**
     * AMP boilerplate ekle
     */
    public function add_amp_boilerplate() {
        if (!$this->is_amp()) {
            return;
        }
        ?>
        <script async src="https://cdn.ampproject.org/v0.js"></script>
        <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
        <script async custom-element="amp-lightbox-gallery" src="https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js"></script>
        
        <style amp-boilerplate>
            body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
        </style>
        <noscript>
            <style amp-boilerplate>
                body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}
            </style>
        </noscript>
        <?php
    }
    
    /**
     * Body class'ları ekle
     */
    public function body_classes($classes) {
        if ($this->is_mobile()) {
            $classes[] = 'mobile-device';
        } else {
            $classes[] = 'desktop-device';
        }
        
        if ($this->is_amp()) {
            $classes[] = 'amp-page';
        }
        
        return $classes;
    }
    
    /**
     * Mobil cihaz kontrolü
     */
    public function is_mobile() {
        return wp_is_mobile();
    }
    
    /**
     * AMP sayfası kontrolü
     */
    public function is_amp() {
        return wp_is_mobile();
    }
    
    /**
     * İçerik tipini belirle
     */
    public function get_content_type() {
        if ($this->is_mobile()) {
            return 'products'; // Mobilde ürünler
        } else {
            return 'articles'; // PC'de makaleler
        }
    }
}

// Tema sınıfını başlat
new AMP_Responsive_Theme();

/**
 * Özel fonksiyonlar
 */

// Ürün meta bilgilerini al
function get_product_meta($post_id, $key, $default = '') {
    $meta = get_post_meta($post_id, $key, true);
    return !empty($meta) ? $meta : $default;
}

// Ürün fiyatını al
function get_product_price($post_id) {
    return get_product_meta($post_id, 'urun_fiyat', '');
}

// Ürün telefon numarasını al
function get_product_phone($post_id) {
    return get_product_meta($post_id, 'urun_telefon', '');
}

// Ürün konumunu al
function get_product_location($post_id) {
    return get_product_meta($post_id, 'urun_konum', '');
}

// Ürün yaşını al
function get_product_age($post_id) {
    return get_product_meta($post_id, 'urun_yas', '');
}

// Truncate text fonksiyonu
function truncate_text($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Ürün galeri resimlerini al
 */
function get_product_gallery_images($post_id) {
    $gallery_images = get_post_meta($post_id, 'product_gallery_images', true);
    
    if (empty($gallery_images) || !is_array($gallery_images)) {
        return array();
    }
    
    $images = array();
    foreach ($gallery_images as $image_id) {
        if ($image_id && is_numeric($image_id)) {
            $image_url = wp_get_attachment_image_url($image_id, 'gallery-large');
            $thumb_url = wp_get_attachment_image_url($image_id, 'gallery-thumb');
            $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            
            if ($image_url) {
                $images[] = array(
                    'id' => $image_id,
                    'url' => $image_url,
                    'thumb' => $thumb_url,
                    'alt' => $alt_text ?: get_the_title($post_id)
                );
            }
        }
    }
    
    return $images;
}

/**
 * Widget alanları
 */
function amp_responsive_widgets_init() {
    register_sidebar(array(
        'name' => 'Ana Sidebar',
        'id' => 'sidebar-main',
        'description' => 'Ana sidebar alanı',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'amp_responsive_widgets_init');

/**
 * Meta box'lar
 */
function add_product_meta_boxes() {
    add_meta_box(
        'product-details',
        'Ürün Detayları',
        'product_details_callback',
        'urunler',
        'normal',
        'high'
    );
    
    add_meta_box(
        'product-gallery',
        'Ürün Galeri Resimleri',
        'product_gallery_callback',
        'urunler',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_product_meta_boxes');

function product_details_callback($post) {
    wp_nonce_field('save_product_details', 'product_details_nonce');
    
    $fiyat = get_post_meta($post->ID, 'urun_fiyat', true);
    $telefon = get_post_meta($post->ID, 'urun_telefon', true);
    $konum = get_post_meta($post->ID, 'urun_konum', true);
    $yas = get_post_meta($post->ID, 'urun_yas', true);
    $boy = get_post_meta($post->ID, 'urun_boy', true);
    $kilo = get_post_meta($post->ID, 'urun_kilo', true);
    $yer = get_post_meta($post->ID, 'urun_yer', true);
    $sira = get_post_meta($post->ID, 'urun_sira', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="urun_sira">Sıra (Listeleme sırası - küçük sayı önce gösterilir)</label></th>
            <td><input type="number" id="urun_sira" name="urun_sira" value="<?php echo esc_attr($sira); ?>" min="1" max="999" /></td>
        </tr>
        <tr>
            <th><label for="urun_fiyat">Fiyat</label></th>
            <td><input type="text" id="urun_fiyat" name="urun_fiyat" value="<?php echo esc_attr($fiyat); ?>" /></td>
        </tr>
        <tr>
            <th><label for="urun_telefon">Telefon (WhatsApp)</label></th>
            <td><input type="text" id="urun_telefon" name="urun_telefon" value="<?php echo esc_attr($telefon); ?>" placeholder="+905xxxxxxxxx" /></td>
        </tr>
        <tr>
            <th><label for="urun_konum">Konum</label></th>
            <td><input type="text" id="urun_konum" name="urun_konum" value="<?php echo esc_attr($konum); ?>" placeholder="İstanbul, Ankara vb." /></td>
        </tr>
        <tr>
            <th><label for="urun_yas">Yaş</label></th>
            <td><input type="text" id="urun_yas" name="urun_yas" value="<?php echo esc_attr($yas); ?>" placeholder="22" /></td>
        </tr>
        <tr>
            <th><label for="urun_boy">Boy</label></th>
            <td><input type="text" id="urun_boy" name="urun_boy" value="<?php echo esc_attr($boy); ?>" placeholder="165 cm" /></td>
        </tr>
        <tr>
            <th><label for="urun_kilo">Kilo</label></th>
            <td><input type="text" id="urun_kilo" name="urun_kilo" value="<?php echo esc_attr($kilo); ?>" placeholder="55 kg" /></td>
        </tr>
        <tr>
            <th><label for="urun_yer">Yer</label></th>
            <td><input type="text" id="urun_yer" name="urun_yer" value="<?php echo esc_attr($yer); ?>" placeholder="Ev, Otel, Rezidans" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Ürün galeri meta box callback
 */
function product_gallery_callback($post) {
    wp_nonce_field('save_product_gallery', 'product_gallery_nonce');
    
    $gallery_images = get_post_meta($post->ID, 'product_gallery_images', true);
    if (!is_array($gallery_images)) {
        $gallery_images = array();
    }
    ?>
    <div class="product-gallery-meta">
        <p><strong>Not:</strong> Öne çıkan görsel sadece ana sayfada görünür. Burada eklediğiniz resimler ürün sayfasındaki galeride gösterilir.</p>
        
        <div class="gallery-images-container">
            <div id="gallery-images-list">
                <?php if (!empty($gallery_images)): ?>
                    <?php foreach ($gallery_images as $index => $image_id): ?>
                        <?php if ($image_id): ?>
                            <?php $image_url = wp_get_attachment_image_url($image_id, 'thumbnail'); ?>
                            <div class="gallery-image-item" data-index="<?php echo $index; ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="hidden" name="product_gallery_images[]" value="<?php echo esc_attr($image_id); ?>">
                                <button type="button" class="remove-gallery-image button">Kaldır</button>
                                <div class="image-handle">⋮⋮</div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <p>
            <button type="button" id="add-gallery-images" class="button button-secondary">Galeri Resmi Ekle</button>
            <span class="description">Birden fazla resim seçebilirsiniz. Resimleri sürükleyerek sıralayabilirsiniz.</span>
        </p>
    </div>
    
    <style>
        .product-gallery-meta .gallery-images-container {
            margin: 15px 0;
        }
        
        #gallery-images-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .gallery-image-item {
            position: relative;
            border: 1px solid #ddd;
            padding: 5px;
            background: #f9f9f9;
            cursor: move;
        }
        
        .gallery-image-item img {
            display: block;
        }
        
        .gallery-image-item .remove-gallery-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3232;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        
        .gallery-image-item .image-handle {
            position: absolute;
            top: 2px;
            left: 2px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 2px 4px;
            font-size: 12px;
            cursor: move;
        }
        
        .gallery-image-item.ui-sortable-helper {
            transform: rotate(5deg);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
    
    <script>
        jQuery(document).ready(function($) {
            // Resim seçici
            $('#add-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                var mediaUploader = wp.media({
                    title: 'Galeri Resimleri Seç',
                    button: {
                        text: 'Resimleri Seç'
                    },
                    multiple: true
                });
                
                mediaUploader.on('select', function() {
                    var attachments = mediaUploader.state().get('selection').toJSON();
                    
                    attachments.forEach(function(attachment) {
                        var imageHtml = '<div class="gallery-image-item" data-index="' + Date.now() + '">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" alt="" style="width: 100px; height: 100px; object-fit: cover;">' +
                            '<input type="hidden" name="product_gallery_images[]" value="' + attachment.id + '">' +
                            '<button type="button" class="remove-gallery-image button">Kaldır</button>' +
                            '<div class="image-handle">⋮⋮</div>' +
                            '</div>';
                        
                        $('#gallery-images-list').append(imageHtml);
                    });
                });
                
                mediaUploader.open();
            });
            
            // Resim kaldırma
            $(document).on('click', '.remove-gallery-image', function(e) {
                e.preventDefault();
                $(this).closest('.gallery-image-item').remove();
            });
            
            // Sürükle bırak sıralama
            $('#gallery-images-list').sortable({
                handle: '.image-handle',
                placeholder: 'gallery-image-placeholder',
                tolerance: 'pointer'
            });
        });
    </script>
    <?php
}

function save_product_details($post_id) {
    if (!isset($_POST['product_details_nonce']) || !wp_verify_nonce($_POST['product_details_nonce'], 'save_product_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('urun_fiyat', 'urun_telefon', 'urun_konum', 'urun_yas', 'urun_boy', 'urun_kilo', 'urun_yer', 'urun_sira');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === 'urun_sira') {
                $value = intval($_POST[$field]);
                if ($value <= 0) $value = 999; // Varsayılan sıra
                update_post_meta($post_id, $field, $value);
            } else {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    // Galeri resimlerini kaydet
    if (isset($_POST['product_gallery_nonce']) && wp_verify_nonce($_POST['product_gallery_nonce'], 'save_product_gallery')) {
        $gallery_images = array();
        
        if (isset($_POST['product_gallery_images']) && is_array($_POST['product_gallery_images'])) {
            foreach ($_POST['product_gallery_images'] as $image_id) {
                $image_id = intval($image_id);
                if ($image_id > 0) {
                    $gallery_images[] = $image_id;
                }
            }
        }
        
        update_post_meta($post_id, 'product_gallery_images', $gallery_images);
    }
}
add_action('save_post', 'save_product_details');

// Ek helper fonksiyonlar
function get_product_boy($post_id) {
    return get_product_meta($post_id, 'urun_boy', '');
}

function get_product_kilo($post_id) {
    return get_product_meta($post_id, 'urun_kilo', '');
}

function get_product_yer($post_id) {
    return get_product_meta($post_id, 'urun_yer', '');
}

function get_product_sira($post_id) {
    return get_product_meta($post_id, 'urun_sira', '999');
}

/**
 * Admin menüsü ekle
 */
function add_theme_admin_menu() {
    add_menu_page(
        'Sıralama Yönetimi',
        'Sıralama Yönetimi', 
        'manage_options',
        'siralama-yonetimi',
        'siralama_yonetimi_page',
        'dashicons-sort',
        30
    );
}
add_action('admin_menu', 'add_theme_admin_menu');

/**
 * Sıralama yönetimi sayfası
 */
function siralama_yonetimi_page() {
    // Sıralama güncelleme işlemi
    if (isset($_POST['update_sira']) && wp_verify_nonce($_POST['siralama_nonce'], 'update_siralama')) {
        foreach ($_POST['urun_sira'] as $post_id => $sira) {
            $sira = intval($sira);
            if ($sira > 0 && $sira <= 100) {
                update_post_meta($post_id, 'urun_sira', $sira);
            }
        }
        echo '<div class="notice notice-success"><p>Sıralama başarıyla güncellendi!</p></div>';
    }
    
    // Mevcut ürünleri al
    $products = get_posts(array(
        'post_type' => 'urunler',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'urun_sira',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    ));
    
    // Kullanılan sıra numaralarını topla
    $used_numbers = array();
    foreach ($products as $product) {
        $sira = get_post_meta($product->ID, 'urun_sira', true);
        if ($sira && $sira != '999') {
            $used_numbers[intval($sira)] = $product;
        }
    }
    
    ?>
    <div class="wrap">
        <h1>Sıralama Yönetimi (1-100)</h1>
        <p>Yeşil: Boş sıra | Kırmızı: Dolu sıra | Ürünlerin sıra numaralarını değiştirebilirsiniz.</p>
        
        <!-- Sıra Numarası Görselleştirme -->
        <div class="siralama-grid">
            <?php for ($i = 1; $i <= 100; $i++): ?>
                <div class="sira-box <?php echo isset($used_numbers[$i]) ? 'dolu' : 'bos'; ?>">
                    <span class="sira-number"><?php echo $i; ?></span>
                    <?php if (isset($used_numbers[$i])): ?>
                        <span class="sira-product"><?php echo esc_html($used_numbers[$i]->post_title); ?></span>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
        
        <br><br>
        
        <!-- Ürün Sıralama Formu -->
        <h2>Ürün Sıralarını Düzenle</h2>
        <form method="post">
            <?php wp_nonce_field('update_siralama', 'siralama_nonce'); ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Mevcut Sıra</th>
                        <th>Yeni Sıra (1-100)</th>
                        <th>Telefon</th>
                        <th>Konum</th>
                        <th>Galeri</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $current_sira = get_post_meta($product->ID, 'urun_sira', true);
                        $telefon = get_post_meta($product->ID, 'urun_telefon', true);
                        $konum = get_post_meta($product->ID, 'urun_konum', true);
                        $gallery_images = get_product_gallery_images($product->ID);
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($product->post_title); ?></strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo get_edit_post_link($product->ID); ?>">Düzenle</a> | 
                                    </span>
                                    <span class="view">
                                        <a href="<?php echo get_permalink($product->ID); ?>" target="_blank">Görüntüle</a>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="current-sira <?php echo ($current_sira && $current_sira != '999') ? 'has-sira' : 'no-sira'; ?>">
                                    <?php echo ($current_sira && $current_sira != '999') ? esc_html($current_sira) : 'Atanmamış'; ?>
                                </span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="urun_sira[<?php echo $product->ID; ?>]" 
                                       value="<?php echo ($current_sira && $current_sira != '999') ? esc_attr($current_sira) : ''; ?>" 
                                       min="1" 
                                       max="100" 
                                       class="small-text" 
                                       placeholder="1-100">
                            </td>
                            <td><?php echo esc_html($telefon); ?></td>
                            <td><?php echo esc_html($konum); ?></td>
                            <td>
                                <?php if (!empty($gallery_images)): ?>
                                    <span style="color: green;">✓ <?php echo count($gallery_images); ?> resim</span>
                                <?php else: ?>
                                    <span style="color: #999;">Galeri boş</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p class="submit">
                <input type="submit" name="update_sira" class="button-primary" value="Sıralamaları Güncelle">
            </p>
        </form>
        
        <style>
            .siralama-grid {
                display: grid;
                grid-template-columns: repeat(20, 1fr);
                gap: 2px;
                margin: 20px 0;
                max-width: 1000px;
            }
            
            .sira-box {
                padding: 8px 4px;
                text-align: center;
                font-size: 11px;
                border: 1px solid #ddd;
                border-radius: 3px;
                min-height: 50px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            
            .sira-box.bos {
                background-color: #d4edda;
                border-color: #28a745;
            }
            
            .sira-box.dolu {
                background-color: #f8d7da;
                border-color: #dc3545;
            }
            
            .sira-number {
                font-weight: bold;
                font-size: 12px;
            }
            
            .sira-product {
                font-size: 9px;
                margin-top: 2px;
                word-break: break-word;
            }
            
            .current-sira.has-sira {
                color: #dc3545;
                font-weight: bold;
            }
            
            .current-sira.no-sira {
                color: #6c757d;
                font-style: italic;
            }
            
            .wp-list-table th {
                background: #f1f1f1;
            }
            
            .wp-list-table td {
                vertical-align: middle;
            }
            
            @media (max-width: 768px) {
                .siralama-grid {
                    grid-template-columns: repeat(10, 1fr);
                }
            }
        </style>
    </div>
    <?php
}