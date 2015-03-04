<?php
/*
 * Template Name: Portfolio.
 */

get_header(); ?>
<?php
/**
* Check if isotope is enqueued in theme.
*/
global $wp_scripts;
$scriptlist = $wp_scripts->queue;
$string = 'isotope';
$scriptlistencoded = json_encode($scriptlist);

if ( strpos($scriptlistencoded, $string) !== false ) {
    // Script is queued. Do nothing
} else {
    wp_register_script( 'imagesloaded-js', plugins_url( '/birds-portfolio/assets/isotope/imagesloaded.min.js' ), array('jquery'), null, true );
    wp_enqueue_script( 'imagesloaded-js' );
    wp_register_script( 'isotope-js', plugins_url( '/birds-portfolio/assets/isotope/jquery.isotope.min.js' ), array('jquery'), null, true );
    wp_enqueue_script( 'isotope-js' );
}
?>
<script>
    var $jq = jQuery.noConflict();
    jQuery(document).ready(function () {
        "use strict";
        /**
         * Isotope
         */
        var $jqcontainer = $jq('.portfolio_wrapper');
        $jqcontainer.isotope({
            filter: '*',
            itemSelector: '.portfolio_item',
            layoutMode: 'fitRows',
            transitionDuration: '1.5s',
            hiddenStyle: {
                opacity: 0
            },
            visibleStyle: {
                opacity: 1
            }
        });

        $jq('.portfolio_filter a').click(function(){
            $jq('.portfolio_filter .current').removeClass('current');
            $jq(this).addClass('current');

            var selector = $jq(this).attr('data-filter');
            $jqcontainer.isotope({
                filter: selector,
            });
            return false;
        });
    });
</script>


<div class="row">

    <div id="primary" class="content-area">

        <div class="large-12 columns">

            <main id="main" class="site-main" role="main">

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">

                        <ul id="portfolio" class="portfolio_filter">
                            <?php
                                $terms = get_terms('portfolio_categories');

                                $count = count($terms); $i=0;
                                if ($count > 0) {
                                    $term_list = '<li><a href="#all" data-filter="*" title="All" class="current">'. __('All', 'birds') . '</a></li>';
                                    foreach ($terms as $term) {
                                        $i++;
                                        $term_list .= '<li><a href="#" data-filter=.' . strtolower(preg_replace('/\s+/', '-', $term->slug)) . ' title="' . strtolower(preg_replace('/\s+/', '-', $term->slug)) . '">' . $term->name . '</a>';
                                        if ($count != $i) $term_list .= ' '; else $term_list .= '</li>';
                                    }
                                    echo $term_list;
                                }
                            ?>

                        </ul>
                        <div class="portfolio_wrapper">

                            <?php
                                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                                $wpbp = new WP_Query(array( 'post_type' => 'birds_portfolio', 'posts_per_page' =>'-1', 'paged' => $paged, 'orderby'=> 'date', 'order' => 'DESC' ) );
                            ?>

                            <?php
                                if ($wpbp->have_posts()) : while ($wpbp->have_posts()) : $wpbp->the_post();
                                $terms = get_the_terms( get_the_ID(), 'portfolio_categories' );
                            ?>

                            <div class="portfolio_item <?php foreach ($terms as $term) { echo strtolower(preg_replace('/\s+/', '-', $term->slug)). ' '; } ?>">

                                <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
                                <?php $short_desc = get_post_meta(get_the_ID(), 'birds_portfolio_short_desc', TRUE); ?>
                                   <figure class="effect"> <!-- Let's do the magic! -->
                                       <?php the_post_thumbnail("full"); ?>
                                        <figcaption>
                                            <h2><?php the_title(); ?></h2>
                                            <p class="icon-links">
                                                <a href="<?php the_permalink(); ?>"><span class="icon-link"> </span></a>
                                            </p>
                                            <p class="description"><?php echo $short_desc; ?></p>
                                        </figcaption>
                                    </figure> <!-- Let's end the magic! -->
                            </div>
                            <div style="clear:both;"></div>
                                <?php } else { ?>

                                <?php echo '</div>' ; ?>
                                <?php } ?>

                            <?php
                                $count++;
                                endwhile; endif;
                                wp_reset_query();
                            ?>

                        </div><!-- .portfolio_wrapper -->
                    </div><!-- .entry-content -->

                <footer class="entry-footer">
                </footer><!-- .entry-footer -->
                </article><!-- #post-## -->



            </main><!-- #main -->

    </div><!-- .large-12 -->

</div><!-- #primary -->

</div><!-- .row -->

<?php get_footer(); ?>
