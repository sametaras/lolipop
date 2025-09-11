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

<div class="wrap">
    <?php if ($is_mobile): ?>
        <!-- MOBİL ÜRÜN LİSTESİ - Responsive Grid Sistemi -->
        <div class="product-showcase">
            <?php
            $products = new WP_Query(array(
                'post_type' => 'urunler',
                'posts_per_page' => 360, // Daha fazla ürün göster
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
                    $title = get_the_title();
                    $image = get_the_post_thumbnail_url($product_id, 'product-thumb');
                    $location = get_product_location($product_id);
                    $age = get_product_age($product_id);
                    $phone = get_product_phone($product_id);
                    
                    // Fallback image yoksa
                    if (!$image) {
                        $image = get_template_directory_uri() . '/assets/images/placeholder.jpg';
                    }
                    ?>
                    <div class="showcase-grid">
                        <ul>
                            <li class="product-card">
                                <a href="<?php the_permalink(); ?>" rel="nofollow" target="_blank">
                                    <?php if ($is_amp): ?>
                                        <amp-img 
                                            alt="<?php echo esc_attr($title); ?>" 
                                            height="275" 
                                            layout="responsive" 
                                            src="<?php echo esc_url($image); ?>" 
                                            width="150">
                                        </amp-img>
                                    <?php else: ?>
                                        <img 
                                            src="<?php echo esc_url($image); ?>" 
                                            alt="<?php echo esc_attr($title); ?>" 
                                            width="150" 
                                            height="275"
                                            loading="lazy">
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                ?>
                <div class="no-products-found">
                    <p>Henüz ürün eklenmemiş.</p>
                </div>
                <?php
            endif;
            ?>
            
            <!-- Float temizleme -->
            <div class="clear-float"></div>
        </div>
        
        <!-- Mobil için ek padding -->
        <div class="margintop20 clearfix"></div>
        <div class="margintop20 clearfix"></div>
        
    </div> <!-- .wrap sonu -->
        
    <?php else: ?>
        <!-- DESKTOP MAKALE LİSTESİ -->
        <div class="desktop-content">
            <div class="content-wrapper">
                <div class="main-content">
                    <div class="blog-header">
                        <h1><?php bloginfo('name'); ?></h1>
                        <p><?php bloginfo('description'); ?></p>
                    </div>
                    
                    <div class="articles-list">
                        <?php
                        $articles = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 10,
                            'post_status' => 'publish',
                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                        ));
                        
                        if ($articles->have_posts()):
                            while ($articles->have_posts()): $articles->the_post();
                                $article_id = get_the_ID();
                                $image = get_the_post_thumbnail_url($article_id, 'medium');
                                $title = get_the_title();
                                $excerpt = get_the_excerpt();
                                $date = get_the_date('d F Y');
                                $author = get_the_author();
                                $categories = get_the_category();
                                ?>
                                <article class="article-item">
                                    <div class="article-meta">
                                        <span class="publish-date">📅 <?php echo esc_html($date); ?></span>
                                        <span class="author-name">✍️ <?php echo esc_html($author); ?></span>
                                        
                                        <?php if (!empty($categories)): ?>
                                            <div class="article-categories">
                                                📂 
                                                <?php foreach ($categories as $category): ?>
                                                    <a href="<?php echo get_category_link($category->term_id); ?>" class="category-tag">
                                                        <?php echo esc_html($category->name); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h2 class="article-title">
                                        <a href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a>
                                    </h2>
                                    
                                    <?php if ($image): ?>
                                        <div class="article-thumbnail">
                                            <?php if ($is_amp): ?>
                                                <amp-img 
                                                    src="<?php echo esc_url($image); ?>" 
                                                    alt="<?php echo esc_attr($title); ?>"
                                                    width="600" 
                                                    height="300" 
                                                    layout="responsive">
                                                </amp-img>
                                            <?php else: ?>
                                                <img src="<?php echo esc_url($image); ?>" 
                                                     alt="<?php echo esc_attr($title); ?>" 
                                                     class="article-image" 
                                                     loading="lazy">
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($excerpt): ?>
                                        <div class="article-excerpt">
                                            <p><?php echo wp_kses_post($excerpt); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="article-actions">
                                        <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                            Devamını Oku →
                                        </a>
                                    </div>
                                </article>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            ?>
                            <div class="no-articles">
                                <h3>Henüz makale yayınlanmamış</h3>
                                <p>İlk makalenizi admin panelinden ekleyebilirsiniz.</p>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($articles->max_num_pages > 1): ?>
                        <div class="pagination-wrapper">
                            <?php
                            echo paginate_links(array(
                                'total' => $articles->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'format' => '?paged=%#%',
                                'show_all' => false,
                                'end_size' => 1,
                                'mid_size' => 2,
                                'prev_next' => true,
                                'prev_text' => '← Önceki',
                                'next_text' => 'Sonraki →',
                                'type' => 'list',
                                'add_args' => false
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="main-sidebar">
                    <?php if (is_active_sidebar('sidebar-main')): ?>
                        <?php dynamic_sidebar('sidebar-main'); ?>
                    <?php else: ?>
                        <div class="widget">
                            <h3 class="widget-title">Kategoriler</h3>
                            <ul>
                                <?php wp_list_categories(array(
                                    'title_li' => '',
                                    'show_count' => true,
                                    'hide_empty' => true
                                )); ?>
                            </ul>
                        </div>
                        
                        <div class="widget">
                            <h3 class="widget-title">Son Yazılar</h3>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish'
                                ));
                                foreach ($recent_posts as $recent_post):
                                ?>
                                    <li>
                                        <a href="<?php echo get_permalink($recent_post['ID']); ?>">
                                            <?php echo esc_html($recent_post['post_title']); ?>
                                        </a>
                                        <small>(<?php echo get_the_date('d.m.Y', $recent_post['ID']); ?>)</small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Schema.org için yapılandırılmış veri -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "<?php echo home_url(); ?>",
    "name": "<?php bloginfo('name'); ?>",
    "description": "<?php bloginfo('description'); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "<?php echo home_url(); ?>?s={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<?php if ($is_mobile && $products->have_posts()): ?>
<!-- Ürün listesi için ek schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Ürün Listesi",
    "numberOfItems": <?php echo $products->found_posts; ?>,
    "itemListElement": [
        <?php
        $counter = 1;
        while ($products->have_posts()): $products->the_post();
            $product_id = get_the_ID();
            $title = get_the_title();
            $image = get_the_post_thumbnail_url($product_id, 'large');
            ?>
            {
                "@type": "ListItem",
                "position": <?php echo $counter; ?>,
                "item": {
                    "@type": "Product",
                    "name": "<?php echo esc_js($title); ?>",
                    "url": "<?php echo get_permalink(); ?>",
                    <?php if ($image): ?>
                    "image": "<?php echo esc_url($image); ?>",
                    <?php endif; ?>
                    "offers": {
                        "@type": "Offer",
                        "availability": "https://schema.org/InStock"
                    }
                }
            }<?php echo ($counter < $products->found_posts) ? ',' : ''; ?>
            <?php
            $counter++;
            if ($counter > 50) break; // Schema için limit
        endwhile;
        wp_reset_postdata();
        ?>
    ]
}
</script>
<?php endif; ?>

<?php get_footer(); ?>