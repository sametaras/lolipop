<?php
/**
 * Header template - SEO Optimized with AMP CSS
 */

$theme = new AMP_Responsive_Theme();
$is_mobile = $theme->is_mobile();
$is_amp = $theme->is_amp();

// SEO Meta bilgileri
$site_name = get_bloginfo('name');
$site_description = get_bloginfo('description');
$current_url = home_url($_SERVER['REQUEST_URI']);
$canonical_url = $is_amp ? str_replace('?amp=1', '', $current_url) : $current_url;

// Sayfa spesifik SEO bilgileri
$page_title = '';
$page_description = '';
$page_image = '';
$page_type = 'website';

if (is_singular()) {
    global $post;
    $page_title = get_the_title();
    $page_description = get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : wp_strip_all_tags(substr(get_the_content(), 0, 160));
    $page_image = get_the_post_thumbnail_url($post->ID, 'large');
    $page_type = is_singular('urunler') ? 'product' : 'article';
} elseif (is_home() || is_front_page()) {
    $page_title = $site_name;
    $page_description = $site_description;
    $page_image = get_template_directory_uri() . '/assets/images/logo.png';
} elseif (is_category()) {
    $page_title = single_cat_title('', false);
    $page_description = category_description();
    $page_type = 'website';
} elseif (is_tag()) {
    $page_title = single_tag_title('', false);
    $page_description = tag_description();
    $page_type = 'website';
}

// Varsayılan resim
if (!$page_image) {
    $page_image = get_template_directory_uri() . '/assets/images/default-og-image.jpg';
}

// Title optimizasyonu
$seo_title = $page_title;
if (!is_front_page()) {
    $seo_title .= ' | ' . $site_name;
}
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php echo $is_amp ? 'amp i-amphtml-layout i-amphtml-no-boilerplate' : ''; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title><?php echo esc_html($seo_title); ?></title>
    <meta name="description" content="<?php echo esc_attr($page_description); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#1B83A0">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="<?php echo esc_attr($page_type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($page_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($page_description); ?>">
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:image" content="<?php echo esc_url($page_image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="tr_TR">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($page_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($page_description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($page_image); ?>">
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="TR">
    <meta name="geo.placename" content="Türkiye">
    
    <!-- Language -->
    <meta http-equiv="content-language" content="tr">
    
    <?php if ($is_amp): ?>
        <!-- AMP Scripts -->
        <script async src="https://cdn.ampproject.org/v0.js"></script>
        <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
        <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
        <script async custom-element="amp-lightbox-gallery" src="https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js"></script>
        
        <!-- Google Fonts for AMP -->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!-- AMP Boilerplate -->
        <style amp-boilerplate>
            body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
        </style>
        <noscript>
            <style amp-boilerplate>
                body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}
            </style>
        </noscript>
        
        <!-- AMP Custom CSS - Style.css içeriğini buraya dahil et -->
        <style amp-custom>
            <?php
            // Style.css dosyasının içeriğini inline olarak dahil et
            $style_path = get_template_directory() . '/style.css';
            if (file_exists($style_path)) {
                $css_content = file_get_contents($style_path);
                
                // AMP için CSS temizleme
                $css_content = preg_replace('/\/\*.*?\*\//s', '', $css_content); // Yorumları temizle
                $css_content = preg_replace('/\s+/', ' ', $css_content); // Fazla boşlukları temizle
                $css_content = str_replace('!important', '', $css_content); // !important'ları temizle (AMP'de yasak)
                
                echo trim($css_content);
            } else {
                // Fallback critical CSS
                echo '
                *{box-sizing:border-box}
                body{margin:0;background:#e5e5e5;font-family:"Quicksand",sans-serif;font-weight:400;color:#363636;line-height:1.44;font-size:15px}
                .main-wrapper{background:#fff;max-width:780px;margin:0 auto;min-height:100vh}
                .site-header{height:52px;width:100%;position:relative;margin:0;color:#fff;background:linear-gradient(to right,#004a7f,#004a7f)}
                .site-header .branding{display:block;text-align:center;font-size:20px;font-weight:400;text-decoration:none;font-family:Quicksand,sans-serif;color:#fff;position:absolute;top:0;width:100%;padding:10px 55px;z-index:9;height:52px;line-height:32px}
                .navbar-toggle,.navbar-search{color:#fff;font-weight:400;font-size:18px;position:absolute;top:0;z-index:99;border:none;background:rgba(0,0,0,.1);height:52px;line-height:50px;margin:0;padding:0;width:52px;text-align:center;outline:0;cursor:pointer;transition:all .6s ease}
                .navbar-toggle{font-size:21px;left:0}
                .navbar-search{font-size:18px;right:0;line-height:48px}
                .navbar-toggle:hover,.navbar-search:hover{background:rgba(0,0,0,.2)}
                .mobile-simple-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:2px;padding:2px;background:#fbfbfb}
                .simple-grid-item{position:relative;aspect-ratio:150/275;overflow:hidden}
                .simple-product-link{display:block;width:100%;height:100%}
                .simple-product-image{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
                .simple-product-link:hover .simple-product-image{transform:scale(1.02)}
                .wrap{padding:2%;height:100%;background:#fbfbfb}
                .text-center{text-align:center}
                .margintop20{margin-top:20px}
                .clearfix:after{content:"";display:table;clear:both}
                a{color:#353233;text-decoration:none;transition:color .3s ease}
                a:hover{color:#1B83A0}
                @media (max-width:399px){.mobile-simple-grid{grid-template-columns:repeat(4,1fr)}}
                ';
            }
            ?>
        </style>
        
    <?php else: ?>
        <!-- Normal WordPress header -->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!-- Preload critical resources -->
        <link rel="preload" href="<?php echo get_stylesheet_uri(); ?>" as="style">
        
        <?php wp_head(); ?>
    <?php endif; ?>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php echo esc_js($site_name); ?>",
        "url": "<?php echo esc_url(home_url()); ?>",
        "description": "<?php echo esc_js($site_description); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "areaServed": "TR",
            "availableLanguage": "Turkish"
        },
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "TR"
        },
        "sameAs": [
            "https://www.facebook.com/yourpage",
            "https://www.instagram.com/yourpage"
        ]
    }
    </script>
    
    <?php if (is_singular('urunler')): ?>
    <!-- Product Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "<?php echo esc_js(get_the_title()); ?>",
        "description": "<?php echo esc_js($page_description); ?>",
        "image": "<?php echo esc_url($page_image); ?>",
        "brand": {
            "@type": "Brand",
            "name": "<?php echo esc_js($site_name); ?>"
        },
        "offers": {
            "@type": "Offer",
            "availability": "https://schema.org/InStock",
            "priceCurrency": "TRY",
            "seller": {
                "@type": "Organization",
                "name": "<?php echo esc_js($site_name); ?>"
            }
        }
    }
    </script>
    <?php endif; ?>
    
    <!-- Hreflang for Turkish -->
    <link rel="alternate" hreflang="tr" href="<?php echo esc_url($canonical_url); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo esc_url($canonical_url); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" sizes="any">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
    
    <!-- Manifest -->
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/manifest.json">
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
    <div class="main-wrapper">
        
        <!-- Skip to content for accessibility -->
        <a class="sr-only" href="#main-content">Ana içeriğe git</a>
        
        <!-- AMP Sidebar -->
        <?php if ($is_amp): ?>
            <amp-sidebar id="sidebar" class="amp-sidebar" layout="nodisplay" side="left">
                <div class="sidebar-container">
                    <button on="tap:sidebar.close" class="close-sidebar" tabindex="11" role="button" aria-label="Menüyü kapat">×</button>
                    
                    <div class="sidebar-brand">
                        <div class="brand-name"><?php bloginfo('name'); ?></div>
                        <div class="brand-description"><?php bloginfo('description'); ?></div>
                    </div>
                    
                    <nav class="sidebar-nav" role="navigation" aria-label="Ana menü">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'mobile',
                            'menu_class' => 'mobile-menu',
                            'container' => false,
                            'fallback_cb' => false,
                        ));
                        ?>
                    </nav>
                </div>
            </amp-sidebar>
        <?php endif; ?>
        
        <!-- Header -->
        <header class="site-header" role="banner" itemscope itemtype="https://schema.org/WPHeader">
            <?php if ($is_amp): ?>
                <button class="navbar-toggle" on="tap:sidebar.toggle" tabindex="10" role="button" aria-label="Menüyü aç">☰</button>
            <?php else: ?>
                <button class="navbar-toggle" onclick="toggleSidebar()" aria-label="Menüyü aç">☰</button>
            <?php endif; ?>
            
            <a href="<?php echo home_url(); ?>" class="branding" rel="home" itemprop="url">
                <span itemprop="name">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <?php bloginfo('name'); ?>
                    <?php endif; ?>
                </span>
            </a>
            
            <a href="<?php echo home_url(); ?>?s=" class="navbar-search" aria-label="Arama">🔍</a>
        </header>
        
        <!-- Main content area -->
        <main id="main-content" role="main"><?php // Bu kapanış tag footer.php'de olacak ?>