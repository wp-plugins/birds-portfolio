<?php
/**
* Single Project Page
*
* @package    birds-portfolio
* @subpackage birds-portfolio/assets/templates
* @since      1.0.0
*/

get_header(); ?>
<?php
/**
* Check if slick.js is enqueued in theme.
*/
global $wp_scripts;
$scriptlist = $wp_scripts->queue;
$string = 'slick';
$scriptlistencoded = json_encode($scriptlist);

if ( strpos($scriptlistencoded, $string) !== false ) {
    // Script is queued. Do nothing
} else {
    // Script is not queued. Enqueue
    wp_register_style( 'slick-css', plugins_url( '/birds-portfolio/assets/slick/slick.css' ), null, null, 'all'  );
    wp_enqueue_style( 'slick-css' );

    wp_register_script( 'slick-js', plugins_url( '/birds-portfolio/assets/slick/slick.min.js' ), array('jquery'), '1.3.15', true );
    wp_enqueue_script( 'slick-js' );
}
?>
<script>
    var $jq = jQuery.noConflict();
    jQuery(document).ready(function () {
        "use strict";
        /**
         * Slick
         */
        $jq('.portfolio_slick').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1
        });
    });
</script>
<div class="row">

    <div id="primary" class="content-area">

        <div class="large-12 columns">

            <main id="main" class="site-main" role="main">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php
                            $image = array();
                            $video_embed = get_post_meta(get_the_ID(), 'birds_video_embed_portfolio', true);
                            $image_id = get_post_meta(get_the_ID(), 'birds_image_ids', true );
                            $ids = array_filter( explode( ',', $image_id ) );
                            $short_desc = get_post_meta(get_the_ID(), 'birds_portfolio_short_desc', TRUE);
                            $client = get_post_meta(get_the_ID(), 'birds_portfolio_client', TRUE);
                            $testimonial = get_post_meta(get_the_ID(), 'birds_portfolio_testimonial', TRUE);
                            $link = get_post_meta(get_the_ID(), 'birds_portfolio_link', TRUE);
                            $country = get_post_meta(get_the_ID(), 'birds_portfolio_country', TRUE);
                            $manager = get_post_meta(get_the_ID(), 'birds_portfolio_projmanager', TRUE);
                        ?>

                        <p class="portfolio_short_desc">
                            <?php echo $short_desc; ?>
                        </p>

                        <?php if(($image_id != null ) || ($video_embed != null)) { ?>
                            <div class="large-12">
                                <div id="portfolio-carousel" class="portfolio_slick">
                                    <?php if ($video_embed != null) { ?>
                                        <div>
                                            <div class="video-portfolio">
                                                <?php echo stripslashes(htmlspecialchars_decode($video_embed)); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ( $ids ) { ?>
                                        <?php foreach ( $ids as $id ) { ?>
                                            <div>
                                                <?php echo wp_get_attachment_image( $id, 'medium' ); ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="portfolio-entry-meta">
                            <?php if($client != null) : ?>
                            <span class="portfolio-client">
                                <strong><?php _e('Client', 'birds-portfolio'); ?>: </strong>
                                <?php echo $client; ?>
                                <div class="clear"></div>
                            </span><!-- .portfolio-client -->
                            <?php endif; ?>
                            <?php if($country != null) : ?>
                            <span class="portfolio-country">
                                <strong><?php _e('Country', 'birds-portfolio'); ?>: </strong>
                                <?php echo $country; ?>
                                <div class="clear"></div>
                            </span><!-- .portfolio-country -->
                            <?php endif; ?>
                            <?php if($manager != null) : ?>
                            <span class="portfolio-manager">
                                <strong><?php _e('Project Manager', 'birds-portfolio'); ?>: </strong>
                                <?php echo $manager; ?>
                                <div class="clear"></div>
                            </span><!-- .portfolio-manager -->
                            <?php endif; ?>

                            <span class="portfolio-category">
                                <?php $terms = get_the_terms( get_the_ID(), 'portfolio_categories' ); ?>
                                <?php if(is_array($terms)){ ?>
                                <strong><?php _e('Category', 'birds-portfolio'); ?>: </strong>
                                <?php foreach ($terms as $term) :  ?>
                                <?php echo $term->name; ?>,
                                <?php endforeach; ?>
                                <div class="clear"></div>
                                <?php } ?>
                            </span><!-- .portfolio-category -->

                        </div><!-- .portfolio-entry-meta -->

                        <div class="portfolio-entry-content clearfix">
                            <?php the_content(); ?>
                            <span class="portfolio-link">
                                <?php if($link != null) : ?>
                                <strong><?php _e('Visite Website:', 'birds-portfolio'); ?></strong><a target="_blank" href="<?php echo $link; ?>"> <?php echo $link; ?></a>
                                <?php endif; ?>
                            </span><!-- .portfolio-link -->
                            <?php if($testimonial != null) : ?>
                            <div class="testimonial">
                                <blockquote>
                                    <p><?php echo $testimonial; ?></p>
                                </blockquote>
                            </div>
                            <?php endif; ?>
                        </div><!-- .portfolio-entry-content -->


                    </div><!-- .entry-content -->

                    <footer class="entry-footer">
                        <div id="portfolio-related">
                            <h3 class="section-title"><?php _e('Related Work','birds-portfolio'); ?></h3>
                            <div>

                                <?php
                                    $terms = get_the_terms( $post->ID , 'portfolio_categories', 'string');
                                    $term_ids = wp_list_pluck($terms,'term_id');

                                    //Query posts with tax_query. Choose in 'IN' if want to query posts with any of the terms
                                    //Chose 'AND' if you want to query for posts with all terms
                                    $portfolio_related_query = new WP_Query( array(
                                        'post_type' => 'birds_portfolio',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'portfolio_categories',
                                                'field' => 'id',
                                                'terms' => $term_ids,
                                                'operator'=> 'IN' //Or 'AND' or 'NOT IN'
                                            )),
                                        'posts_per_page' => 3,
                                        'orderby' => 'rand',
                                        'post__not_in'=>array($post->ID)
                                    ) );

                                    //Loop through posts and display...
                                    if($portfolio_related_query->have_posts()) {
                                        echo '<div class="single_related">';
                                        while ($portfolio_related_query->have_posts() ) : $portfolio_related_query->the_post(); ?>
                                                <?php if (has_post_thumbnail()) { ?>
                                                <div class="portfolio_related_thumb">
                                                    <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"> <?php the_post_thumbnail( 'medium', array('alt' => get_the_title()) ); ?> </a>
                                                </div>
                                                <?php } else { ?>
                                                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                                <?php } ?>
                                        <?php endwhile; wp_reset_query();
                                        echo '<div>';
                                    }
                                ?>
                            </div>
                        </div><!-- #portfolio-related -->
                        <div class="clear"></div>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-## -->

                <?php endwhile; else: ?>
                <?php endif; ?>

            </main><!-- #main -->

        </div><!-- .large-12 -->

    </div><!-- #primary -->

</div><!-- .row -->

<?php get_footer(); ?>
