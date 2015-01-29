<?php
/**
* Custom Post Type & Custom Categories
*
* @package    birds-portfolio
* @subpackage birds-portfolio/includes
* @since      1.0.0
*/

/**
 * Portfolio Custom Post Type
 */
add_action( 'init', 'birds_portfolio_register_post_type' );
function birds_portfolio_register_post_type() {
    $labels = array(
        'name'                => _x( 'Projects', 'Post Type General Name', 'birds-portfolio' ),
        'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'birds-portfolio' ),
        'menu_name'           => __( 'Portfolio', 'birds-portfolio' ),
        'parent_item_colon'   => __( 'Parent Project:', 'birds-portfolio' ),
        'all_items'           => __( 'All Projects', 'birds-portfolio' ),
        'view_item'           => __( 'View Project', 'birds-portfolio' ),
        'add_new_item'        => __( 'Add New Project', 'birds-portfolio' ),
        'add_new'             => __( 'Add New', 'birds-portfolio' ),
        'edit_item'           => __( 'Edit Project', 'birds-portfolio' ),
        'update_item'         => __( 'Update Project', 'birds-portfolio' ),
        'search_items'        => __( 'Search Project', 'birds-portfolio' ),
        'not_found'           => __( 'No Project found', 'birds-portfolio' ),
        'not_found_in_trash'  => __( 'No Project found in Trash', 'birds-portfolio' ),
    );
    $rewrite = array(
        'slug'                       => 'project',
        'with_front'                 => true,
    );
    $args = array(
        'label'               => __( 'projects', 'birds-portfolio' ),
        'description'         => __( 'Portfolio projects', 'birds-portfolio' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', ),
        'taxonomies'          => array( 'portfolio_categories' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 60,
        'menu_icon'           => 'dashicons-images-alt2',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'birds_portfolio', $args );
}

/**
 * Portfolio Taxonomies
 */
add_action( 'init', 'register_taxonomy_portfolio_categories' );
function register_taxonomy_portfolio_categories() {
    $labels = array(
        'name'                       => _x( 'Projects Categories', 'Taxonomy General Name', 'birds-portfolio' ),
        'singular_name'              => _x( 'Project Category', 'Taxonomy Singular Name', 'birds-portfolio' ),
        'menu_name'                  => __( 'Projects Categories', 'birds-portfolio' ),
        'all_items'                  => __( 'All Categories', 'birds-portfolio' ),
        'parent_item'                => __( 'Parent Category', 'birds-portfolio' ),
        'parent_item_colon'          => __( 'Parent Category:', 'birds-portfolio' ),
        'new_item_name'              => __( 'New Category', 'birds-portfolio' ),
        'add_new_item'               => __( 'Add New Category', 'birds-portfolio' ),
        'edit_item'                  => __( 'Edit Category', 'birds-portfolio' ),
        'update_item'                => __( 'Update Category', 'birds-portfolio' ),
        'separate_items_with_commas' => __( 'Separate Categories with commas', 'birds-portfolio' ),
        'search_items'               => __( 'Search Categories', 'birds-portfolio' ),
        'add_or_remove_items'        => __( 'Add or remove Categories', 'birds-portfolio' ),
        'choose_from_most_used'      => __( 'Choose from the most used Categories', 'birds-portfolio' ),
        'not_found'                  => __( 'Not Found', 'birds-portfolio' ),
    );
    $rewrite = array(
        'slug'                       => 'work',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'rewrite'                    => $rewrite,
    );
    register_taxonomy( 'portfolio_categories', array('birds_portfolio'), $args );
}
