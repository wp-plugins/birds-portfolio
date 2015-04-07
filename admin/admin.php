<?php
/**
* Admin Functions
*
* @package    birds-portfolio
* @subpackage birds-portfolio/admin
* @since      1.0.0
*/

add_filter( 'enter_title_here', 'birds_title_placeholder', 10 );
add_filter( 'manage_edit-birds_portfolio_columns', 'birds_portfolio_columns' );
add_action( 'manage_birds_portfolio_posts_custom_column', 'birds_manage_portfolio_columns', 10, 2 );
add_filter( 'manage_edit-birds_portfolio_sortable_columns', 'birds_column_sortable' );

/**
 * Filter the 'enter title here' placeholder.
 *
 */
function birds_title_placeholder( $title ) {

    if ( 'birds_portfolio' == get_current_screen()->post_type )
        $title = esc_attr__( 'Enter project title here', 'birds-portfolio' );

    return $title;
}

/**
 * Sets up custom columns on the portfolio edit screen.
 */
function birds_portfolio_columns( $columns ) {
    unset( $columns['title'] );
    unset( $columns['author'] );
    unset( $columns['comments'] ) ;
    unset( $columns['taxonomy-portfolio_categories'] );

    $new_columns = array(
        'cb'    => '<input type="checkbox" />'
    );

    if ( current_theme_supports( 'post-thumbnails' ) )
        $new_columns['thumbnail'] = __( 'Featured Image', 'birds-portfolio' );

    $new_columns['title'] = __( 'Project Title', 'birds-portfolio' );
    $new_columns['taxonomy-portfolio_categories'] = __( 'Categories', 'birds-portfolio' );

    return array_merge( $new_columns, $columns );
}

/**
 * Displays the content of custom portfolio columns on the edit screen.
*/
function birds_manage_portfolio_columns( $column, $post_id ) {
    global $post;

    switch( $column ) {

        case 'thumbnail' :

            if ( has_post_thumbnail() )
                the_post_thumbnail(array(50,50));
            elseif ( function_exists( 'get_the_image' ) )
                get_the_image( array( 'image_scan' => true, 'width' => 50, 'height' => 50 ) );

            break;

        /* Just break out of the switch statement for everything else. */
        default :
            break;
    }
}

/**
 * Make Categories column sortable.
 */
function birds_column_sortable( $columns ) {
    $columns['taxonomy-portfolio_categories'] = 'taxonomy-portfolio_categories';
    return $columns;
}

/**
 * One Category Shortcode
 */
function birds_portfolio_func($atts, $content = null)
{

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
        // Enqueue scripts
        wp_register_script( 'imagesloaded-js', plugins_url( '/birds-portfolio/assets/isotope/imagesloaded.min.js' ), array('jquery'), null, true );
        wp_enqueue_script( 'imagesloaded-js' );
        wp_register_script( 'isotope-js', plugins_url( '/birds-portfolio/assets/isotope/jquery.isotope.min.js' ), array('jquery'), null, true );
        wp_enqueue_script( 'isotope-js' );


        // Attributes
        extract(shortcode_atts(array(
            'cat' => ''
        ), $atts));

        ob_start();

        $output = '
            <script>
                var $jq = jQuery.noConflict();
                jQuery(document).ready(function () {
                    "use strict";
                    /**
                     * Isotope
                     */
                    var $jqcontainer = $jq(".portfolio_sc_wrapper").imagesLoaded( function() {;
                        $jqcontainer.isotope({
                            filter: ".'.esc_attr($cat).'",
                            itemSelector: ".portfolio_sc_item",
                            layoutMode: "fitRows",
                            transitionDuration: "1.5s",
                            hiddenStyle: {
                                opacity: 0
                            },
                            visibleStyle: {
                                opacity: 1
                            }
                        });
                    });
                });
            </script>
        ';
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'birds_portfolio',
            'tax_query' => array(
                array(
                    'taxonomy' => 'portfolio_categories',
                    'field'    => 'slug',
                    'terms' => esc_attr($cat),
                ),
            ),
            'posts_per_page' =>'-1',
            'paged' => $paged,
            'orderby'=> 'date',
            'order' => 'DESC',
        );
        $wpbp = query_posts( $args );
        if (have_posts()) :
        $output .= '<div class="portfolio_sc_wrapper">';
        while (have_posts()) : the_post();
        $output .= '<div class="portfolio_sc_item '.esc_attr($cat).'">';
        if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) {
            $short_desc = get_post_meta(get_the_ID(), 'birds_portfolio_short_desc', TRUE);
            $output .= '<figure class="sc_effect">';
            $output .= get_the_post_thumbnail(get_the_ID(),'full');
            $output .= '<figcaption>';
            $output .= '<h2>'.get_the_title(get_the_ID()).'<h2>';
            $output .= '<p class="icon-links">';
            $output .= '<a href="'.get_permalink(get_the_ID()).'"><span class="icon-link"> </span></a>';
            $output .= '</p>';
            $output .= '<p class="description">';
            $output .= $short_desc;
            $output .= '</p>';
            $output .= '</figcaption>';
            $output .= '</figure>';
            $output .= '</div><div style="clear:both;"></div>';
        } else {
            $output .= '</div>';
        }
        endwhile; endif;
        wp_reset_query();
        return $output;

        return ob_get_clean();

    }
}
add_shortcode('birdsfolio', 'birds_portfolio_func');
// Usage: [birdsfolio cat="the_portfolio_category_you_want_to_display"]
