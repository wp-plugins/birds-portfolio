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
