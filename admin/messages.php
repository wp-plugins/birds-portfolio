<?php
/**
* Portfolio Custom Messages
*
* @package    birds-portfolio
* @subpackage birds-portfolio/admin
* @since      1.0.0
*/

add_filter( 'post_updated_messages', 'birds_updated_messages' );

function birds_updated_messages( $messages ) {
    global $post, $post_ID;

    $messages['birds_portfolio'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __( 'Project updated. <a href="%s">View Project</a>', 'birds-portfolio' ), esc_url( get_permalink( $post_ID ) ) ),
        2 => __( 'Custom field updated.', 'birds-portfolio' ),
        3 => __( 'Custom field deleted.', 'birds-portfolio' ),
        4 => __( 'Project updated.', 'birds-portfolio' ),
        5 => isset( $_GET['revision'] ) ? sprintf( __( 'Project restored to revision from %s', 'birds-portfolio' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __( 'Project published. <a href="%s">View It</a>', 'birds-portfolio' ), esc_url( get_permalink( $post_ID ) ) ),
        7 => __( 'Project saved.', 'birds-portfolio' ),
        8 => sprintf( __( 'Project submitted. <a target="_blank" href="%s">Preview Project</a>', 'birds-portfolio' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __( 'Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Project</a>', 'birds-portfolio' ),
          // translators: Publish box date format, see http://php.net/date
          date_i18n( __( 'M j, Y @ G:i', 'birds-portfolio' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
        10 => sprintf( __( 'Project draft updated. <a target="_blank" href="%s">Preview Project</a>', 'birds-portfolio' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
    );

    return $messages;
}

