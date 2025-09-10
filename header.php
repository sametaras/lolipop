<?php
/**
 * Header template
 */

$theme = new AMP_Responsive_Theme();
$is_mobile = $theme->is_mobile();
$is_amp = $theme->is_amp();
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php echo $is_amp ? 'amp' : ''; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <meta name="theme-color" content="#1B83A0">
    
    <?php if ($is_amp): ?>
        <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>"/>
        
        <!-- Google Fonts -->
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Quicksand:400,500,700&subset=latin-ext' type='text/css' media='all' />
        <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Khand:wght@400;500&display=swap" rel="stylesheet">
        
        <!-- AMP Scripts -->
        <script type='text/javascript' async src='https://cdn.ampproject.org/v0.js'></script>
        <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
        <script type='text/javascript' custom-element="amp-sidebar" async src='https://cdn.ampproject.org/v0/amp-sidebar-0.1.js'></script>
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
        
        <!-- AMP Boilerplate -->
        <style amp-boilerplate>
            body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
        </style>
        <noscript>
            <style amp-boilerplate>
                body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}
            </style>
        </noscript>
        
        <!-- AMP Custom Styles -->
        <style amp-custom>
            /* Reset and Base Styles */
            *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
            body{margin:0;background:#f5f5f5;font-family:Quicksand,sans-serif;font-weight:400;color:#363636;line-height:1.44;font-size:15px}
            
            /* Container */
            .main-wrapper{background:#fff;max-width:780px;margin:0 auto;min-height:100vh}
            
            /* Header Styles */
            .site-header{height:52px;width:100%;position:relative;margin:0;color:#fff;background:#1B83A0}
            .site-header .branding{display:block;text-align:center;font-size:20px;font-weight:400;text-decoration:none;font-family:Quicksand,sans-serif;color:#fff;position:absolute;top:0;width:100%;padding:10px 55px;z-index:9;height:52px;line-height:32px}
            .navbar-toggle,.navbar-search{color:#fff;font-weight:400;font-size:18px;position:absolute;top:0;z-index:99;border:none;background:rgba(0,0,0,.1);height:52px;line-height:50px;margin:0;padding:0;width:52px;text-align:center;outline:0;cursor:pointer;transition:all .6s ease}
            .navbar-toggle{font-size:21px;left:0}
            .navbar-search{font-size:18px;right:0;line-height:48px}
            .navbar-toggle:hover,.navbar-search:hover{background:rgba(0,0,0,.2)}
            
            /* Sidebar */
            .amp-sidebar{background:#fff;max-width:350px;min-width:270px;padding-bottom:30px}
            .close-sidebar{font-size:25px;border:none;color:#fff;position:absolute;top:10px;right:0px;background:0 0;width:32px;height:32px;line-height:32px;text-align:center;padding:0;outline:0;cursor:pointer}
            .sidebar-brand{color:#fff;padding:0px 50px 10px 5px;text-align:left;font-family:Quicksand,sans-serif;line-height:2;background:#1B83A0}
            .sidebar-brand .brand-name{font-weight:500;font-size:18px}
            .sidebar-brand .brand-description{font-weight:400;font-size:14px;line-height:1.4;margin-top:4px}
            
            /* Mobile Products Grid */
            .mobile-products-container{padding:10px}
            .grid-container{display:grid;grid-template-columns:repeat(5,1fr);gap:3px}
            
            @media only screen and (max-width:399px){
                .grid-container{grid-template-columns:repeat(5,1fr)}
            }
            @media only screen and (min-width:400px){
                .grid-container{grid-template-columns:repeat(5,1fr)}
            }
            
            /* Product Cards */
            .product-card{position:relative;overflow:hidden;border-radius:3px;box-shadow:0px 0px 2px 0px #555;background:#fff}
            .product-image-container{position:relative;width:100%;height:275px}
            .product-image,.no-image-placeholder{width:100%;height:100%;object-fit:cover}
            .no-image-placeholder{background:#eee;display:flex;align-items:center;justify-content:center;color:#999;font-size:12px}
            
            /* Product Overlay */
            .product-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(0deg,rgba(0,145,186,0.9) 0%,rgba(255,255,255,0) 100%);color:#fff;padding:10px 5px}
            .product-location{position:absolute;top:-60px;left:0;right:0;background:linear-gradient(1deg,rgba(0,0,0,0) 21%,#000 98%);color:#fff;font-size:10px;text-align:center;padding:2px;font-family:Khand,sans-serif}
            .product-title{font-size:12px;font-weight:600;margin-bottom:3px;font-family:Khand,sans-serif}
            .product-phone{font-size:14px;background:rgba(0,0,0,0.1);padding:2px 4px;text-align:center;margin:2px 0;font-family:Khand,sans-serif}
            .product-meta{font-size:10px;display:flex;justify-content:space-between}
            .product-age,.product-price{background:rgba(0,0,0,0.2);padding:1px 3px;border-radius:2px}
            
            /* Desktop Articles */
            .desktop-articles-container{padding:20px;max-width:1200px;margin:0 auto}
            .articles-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px}
            .article-card{background:#fff;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);overflow:hidden;transition:transform 0.3s ease}
            .article-card:hover{transform:translateY(-5px)}
            .article-image-container{width:100%;height:200px;overflow:hidden}
            .article-image{width:100%;height:100%;object-fit:cover}
            .article-content{padding:20px}
            .article-meta{font-size:12px;color:#999;margin-bottom:10px}
            .article-date,.article-author{margin-right:10px}
            .category-tag{background:#1B83A0;color:#fff;padding:2px 6px;border-radius:3px;font-size:10px;margin-right:5px}
            .article-title{font-size:18px;margin:10px 0;line-height:1.4}
            .article-title a{color:#333;text-decoration:none}
            .article-title a:hover{color:#1B83A0}
            .article-excerpt{color:#666;line-height:1.6;margin:10px 0}
            .read-more-btn{background:#1B83A0;color:#fff;padding:8px 16px;border-radius:4px;text-decoration:none;font-size:14px;display:inline-block;transition:background 0.3s ease}
            .read-more-btn:hover{background:#155a70}
            
            /* Pagination */
            .pagination-container{margin:30px 0;text-align:center}
            .pagination-container a,.pagination-container span{display:inline-block;padding:8px 12px;margin:0 2px;border:1px solid #ddd;color:#333;text-decoration:none}
            .pagination-container .current{background:#1B83A0;color:#fff;border-color:#1B83A0}
            .pagination-container a:hover{background:#f5f5f5}
            
            /* Sidebar */
            .main-sidebar{width:300px;padding:20px;background:#f9f9f9}
            .widget{margin-bottom:30px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1)}
            .widget-title{color:#333;font-size:16px;margin-bottom:15px;border-bottom:2px solid #1B83A0;padding-bottom:5px}
            
            /* Footer */
            .site-footer{background:#333;color:#fff;text-align:center;padding:20px}
            .copyright{font-size:13px;margin:0}
            
            /* Responsive */
            @media (max-width:768px){
                .desktop-articles-container{padding:10px}
                .articles-grid{grid-template-columns:1fr}
                .main-sidebar{display:none}
            }
            
            /* Links */
            a{color:#1B83A0;text-decoration:none;transition:color 0.3s ease}
            a:hover{color:#155a70}
            
            /* No content messages */
            .no-products,.no-articles{text-align:center;padding:40px;color:#666}
        </style>
    <?php else: ?>
        <?php wp_head(); ?>
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>
    <div class="main-wrapper">
        
        <!-- AMP Sidebar -->
        <?php if ($is_amp): ?>
            <amp-sidebar id="sidebar" class="amp-sidebar" layout="nodisplay" side="left">
                <div class="sidebar-container">
                    <button on="tap:sidebar.close" class="close-sidebar" tabindex="11" role="button">×</button>
                    
                    <div class="sidebar-brand">
                        <div class="brand-name"><?php bloginfo('name'); ?></div>
                        <div class="brand-description"><?php bloginfo('description'); ?></div>
                    </div>
                    
                    <nav class="sidebar-nav">
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
        <header class="site-header">
            <?php if ($is_amp): ?>
                <button class="navbar-toggle" on="tap:sidebar.toggle" tabindex="10" role="button">☰</button>
            <?php else: ?>
                <button class="navbar-toggle" onclick="toggleSidebar()">☰</button>
            <?php endif; ?>
            
            <a href="<?php echo home_url(); ?>" class="branding">
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <?php bloginfo('name'); ?>
                <?php endif; ?>
            </a>
            
            <a href="<?php echo home_url(); ?>" class="navbar-search">🔍</a>
        </header>