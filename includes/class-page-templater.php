<?php
/**
 * Page Templater
 * Slightly modified from https://github.com/wpexplorer/page-templater/blob/master/pagetemplater.php
 * Credits to Harri Bell-Thomas: http://hbt.io/
 *
 * @package    birds-portfolio
 * @subpackage birds-portfolio/includes
 * @since      1.0.0
 */

class PageTemplater {
    protected $plugin_slug;
    private static $instance;
    protected $templates;

    public static function get_instance() {
        if( null == self::$instance ) {
            self::$instance = new PageTemplater();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->templates = array();
        add_filter(
            'page_attributes_dropdown_pages_args',
            array( $this, 'register_project_templates' )
        );
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );
        // Check first if the theme contains its own portfolio template
        $themetemplate = get_template_directory() . '/templates/portfolio.php';
        if ( file_exists($themetemplate) ) {
            // Do nothing. Theme's template prevail over the plugin's one
        } else {
            // Custom Templates are here!
            $this->templates = array(
                'portfolio.php'     => __( 'Portfolio', 'birds-portfolio' ),
            );
        }
    }

    public function register_project_templates( $atts ) {
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }
        wp_cache_delete( $cache_key , 'themes');
        $templates = array_merge( $templates, $this->templates );
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
    }

    public function view_project_template( $template ) {
        global $post;
        if (!isset($this->templates[get_post_meta(
            get_the_ID(), '_wp_page_template', true
        )] ) ) {
            return $template;
        }
        $file = plugin_dir_path( dirname( __FILE__ ) ) . 'assets/templates/' . get_post_meta(
            get_the_ID(), '_wp_page_template', true
        );
        if( file_exists( $file ) ) {
            return $file;
        }
        else { echo $file; }
        return $template;
    }
}

add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
