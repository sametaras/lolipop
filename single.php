<?php
/**
 * Tekil makale sayfası
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
        $date = get_the_date();
        $author = get_the_author();
        $categories = get_the_category();
        $tags = get_the_tags();
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
?>

<div class="single-article-container">
    <article class="single-article">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?php echo home_url(); ?>">Ana Sayfa</a> > 
            <?php if (!empty($categories)): ?>
                <a href="<?php echo get_category_link($categories[0]->term_id); ?>"><?php echo esc_html($categories[0]->name); ?></a> > 
            <?php endif; ?>
            <span><?php echo esc_html($title); ?></span>
        </nav>
        
        <!-- Makale başlığı -->
        <header class="article-header">
            <h1 class="article-title"><?php echo esc_html($title); ?></h1>
            
            <div class="article-meta">
                <span class="publish-date">📅 <?php echo esc_html($date); ?></span>
                <span class="author">👤 <?php echo esc_html($author); ?></span>
                
                <?php if (!empty($categories)): ?>
                    <div class="categories">
                        📂 
                        <?php foreach ($categories as $category): ?>
                            <a href="<?php echo get_category_link($category->term_id); ?>" class="category-link">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        
        <!-- Öne çıkan görsel -->
        <?php if ($featured_image): ?>
            <div class="featured-image-container">
                <?php if ($is_amp): ?>
                    <amp-img 
                        src="<?php echo esc_url($featured_image); ?>" 
                        alt="<?php echo esc_attr($title); ?>"
                        width="800" 
                        height="400" 
                        layout="responsive">
                    </amp-img>
                <?php else: ?>
                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($title); ?>" class="featured-image">
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Makale içeriği -->
        <div class="article-content">
            <?php echo wp_kses_post($content); ?>
        </div>
        
        <!-- Etiketler -->
        <?php if (!empty($tags)): ?>
            <div class="article-tags">
                <strong>Etiketler:</strong>
                <?php foreach ($tags as $tag): ?>
                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag-link">
                        #<?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Sosyal paylaşım -->
        <div class="social-share">
            <h4>Bu makaleyi paylaş:</h4>
            <div class="share-buttons">
                <?php 
                $current_url = urlencode(get_permalink());
                $title_encoded = urlencode($title);
                ?>
                
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" 
                   target="_blank" class="share-btn facebook">
                    📘 Facebook
                </a>
                
                <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $title_encoded; ?>" 
                   target="_blank" class="share-btn twitter">
                    🐦 Twitter
                </a>
                
                <a href="https://wa.me/?text=<?php echo $title_encoded; ?>%20<?php echo $current_url; ?>" 
                   target="_blank" class="share-btn whatsapp">
                    💬 WhatsApp
                </a>
                
                <a href="mailto:?subject=<?php echo $title_encoded; ?>&body=<?php echo $current_url; ?>" 
                   class="share-btn email">
                    ✉️ E-posta
                </a>
            </div>
        </div>
        
        <!-- Benzer makaleler -->
        <div class="related-articles">
            <h3>Benzer Makaleler</h3>
            <div class="related-grid">
                <?php
                $related_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => array($post_id),
                    'category__in' => wp_get_post_categories($post_id),
                    'orderby' => 'rand'
                ));
                
                if ($related_posts->have_posts()):
                    while ($related_posts->have_posts()): $related_posts->the_post();
                        $related_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        $related_title = get_the_title();
                        $related_excerpt = get_the_excerpt();
                        ?>
                        <div class="related-article">
                            <?php if ($related_image): ?>
                                <div class="related-image">
                                    <?php if ($is_amp): ?>
                                        <amp-img 
                                            src="<?php echo esc_url($related_image); ?>" 
                                            alt="<?php echo esc_attr($related_title); ?>"
                                            width="200" 
                                            height="150" 
                                            layout="responsive">
                                        </amp-img>
                                    <?php else: ?>
                                        <img src="<?php echo esc_url($related_image); ?>" alt="<?php echo esc_attr($related_title); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="related-content">
                                <h4><a href="<?php the_permalink(); ?>"><?php echo esc_html($related_title); ?></a></h4>
                                <p><?php echo esc_html(truncate_text($related_excerpt, 80)); ?></p>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </article>
    
    <!-- Navigasyon -->
    <nav class="post-navigation">
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        ?>
        
        <div class="nav-links">
            <?php if ($prev_post): ?>
                <div class="nav-previous">
                    <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-link">
                        ← <?php echo esc_html($prev_post->post_title); ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post): ?>
                <div class="nav-next">
                    <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-link">
                        <?php echo esc_html($next_post->post_title); ?> →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</div>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo esc_js($title); ?>",
    "description": "<?php echo esc_js(get_the_excerpt()); ?>",
    "image": "<?php echo esc_url($featured_image); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_js($author); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php bloginfo('name'); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo get_template_directory_uri(); ?>/assets/images/logo.png"
        }
    },
    "datePublished": "<?php echo get_the_date('c'); ?>",
    "dateModified": "<?php echo get_the_modified_date('c'); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo get_permalink(); ?>"
    }
}
</script>

<?php
    endwhile;
endif;

get_footer(); 
?>