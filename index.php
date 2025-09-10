<?php
/**
 * Ana sayfa şablonu
 * Mobilde ürün listesi, PC'de makale listesi gösterir
 */

get_header(); 

$theme = new AMP_Responsive_Theme();
$is_mobile = $theme->is_mobile();
$is_amp = $theme->is_amp();
?>

<div class="main-wrapper">
    <?php if ($is_mobile): ?>
        <!-- MOBİL ÜRÜN LİSTESİ -->
        <div class="mobile-products-container">
            <div class="grid-container">
                <?php
                $products = new WP_Query(array(
                    'post_type' => 'urunler',
                    'posts_per_page' => 100,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => 'urun_telefon',
                            'value' => '',
                            'compare' => '!='
                        )
                    ),
                    'orderby' => array(
                        'meta_value_num' => 'ASC',
                        'date' => 'DESC'
                    ),
                    'meta_key' => 'urun_sira'
                ));
                
                if ($products->have_posts()):
                    while ($products->have_posts()): $products->the_post();
                        $product_id = get_the_ID();
                        $phone = get_product_phone($product_id);
                        $location = get_product_location($product_id);
                        $age = get_product_age($product_id);
                        $price = get_product_price($product_id);
                        $image = get_the_post_thumbnail_url($product_id, 'product-thumb');
                        $title = get_the_title();
                        $excerpt = get_the_excerpt();
                        ?>
                        <div class="grid-item">
                            <div class="product-card">
                                <a href="<?php the_permalink(); ?>" class="product-link">
                                    <div class="product-image-container">
                                        <?php if ($image): ?>
                                            <?php if ($is_amp): ?>
                                                <amp-img 
                                                    src="<?php echo esc_url($image); ?>" 
                                                    alt="<?php echo esc_attr($title); ?>"
                                                    width="150" 
                                                    height="275" 
                                                    layout="responsive">
                                                </amp-img>
                                            <?php else: ?>
                                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="product-image">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="no-image-placeholder">
                                                <span>Resim Yok</span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Ürün bilgi overlay'i -->
                                        <div class="product-overlay">
                                            <?php if ($location): ?>
                                                <div class="product-location"><?php echo esc_html($location); ?></div>
                                            <?php endif; ?>
                                            
                                            <div class="product-content">
                                                <div class="product-title"><?php echo esc_html($title); ?></div>
                                                
                                                <?php if ($phone): ?>
                                                    <div class="product-phone"><?php echo esc_html($phone); ?></div>
                                                <?php endif; ?>
                                                
                                                <div class="product-meta">
                                                    <?php if ($age): ?>
                                                        <span class="product-age"><?php echo esc_html($age); ?> yaş</span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($price): ?>
                                                        <span class="product-price"><?php echo esc_html($price); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    ?>
                    <div class="no-products">
                        <p>Henüz ürün bulunmuyor.</p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- DESKTOP MAKALE LİSTESİ - BASİT BLOG -->
        <div class="desktop-blog-container">
            <div class="blog-header">
                <h1><?php bloginfo('name'); ?></h1>
                <p><?php bloginfo('description'); ?></p>
            </div>
            
            <div class="articles-list">
                <?php
                $articles = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 10,
                    'post_status' => 'publish'
                ));
                
                if ($articles->have_posts()):
                    while ($articles->have_posts()): $articles->the_post();
                        $article_id = get_the_ID();
                        $image = get_the_post_thumbnail_url($article_id, 'medium');
                        $title = get_the_title();
                        $excerpt = get_the_excerpt();
                        $date = get_the_date('d.m.Y');
                        $author = get_the_author();
                        $categories = get_the_category();
                        ?>
                        <article class="blog-post">
                            <div class="post-content">
                                <div class="post-meta">
                                    <span class="post-date"><?php echo esc_html($date); ?></span>
                                    <span class="post-author">Yazar: <?php echo esc_html($author); ?></span>
                                    
                                    <?php if (!empty($categories)): ?>
                                        <div class="post-categories">
                                            <?php foreach ($categories as $category): ?>
                                                <a href="<?php echo get_category_link($category->term_id); ?>" class="category-link">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a>
                                </h2>
                                
                                <?php if ($image): ?>
                                    <div class="post-thumbnail">
                                        <?php if ($is_amp): ?>
                                            <amp-img 
                                                src="<?php echo esc_url($image); ?>" 
                                                alt="<?php echo esc_attr($title); ?>"
                                                width="300" 
                                                height="200" 
                                                layout="responsive">
                                            </amp-img>
                                        <?php else: ?>
                                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="post-image">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($excerpt): ?>
                                    <div class="post-excerpt">
                                        <p><?php echo wp_kses_post($excerpt); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-actions">
                                    <a href="<?php the_permalink(); ?>" class="read-more">Devamını Oku →</a>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    ?>
                    <div class="no-articles">
                        <h3>Henüz makale bulunmuyor.</h3>
                        <p>İlk makalenizi yayınlamak için admin panelinden yeni bir yazı ekleyin.</p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
            
            <!-- Basit Pagination -->
            <div class="simple-pagination">
                <?php
                $pagination_args = array(
                    'total' => $articles->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'format' => '?paged=%#%',
                    'show_all' => false,
                    'end_size' => 1,
                    'mid_size' => 2,
                    'prev_next' => true,
                    'prev_text' => '← Önceki',
                    'next_text' => 'Sonraki →',
                    'add_args' => false,
                    'add_fragment' => '',
                );
                
                echo paginate_links($pagination_args);
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "<?php echo home_url(); ?>",
    "name": "<?php bloginfo('name'); ?>",
    "description": "<?php bloginfo('description'); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo home_url(); ?>?s={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>

<?php get_footer(); ?>