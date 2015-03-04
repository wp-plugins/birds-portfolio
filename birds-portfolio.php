<?php

/**
 * The plugin bootstrap file
 *
 * @link:               http://www.tenbirdsflying.com/theme/birds-portfolio/
 * @since               1.0.2
 * @package             Birds_Portfolio
 *
 * @wordpress-plugin
 * Plugin Name:         Birds Portfolio
 * Plugin URI:          http://www.tenbirdsflying.com/theme/birds-portfolio/
 * Description:         This plugin adds portfolio projects (with categories) to your WordPress site. Developed to work with themes based on Zurb Foundation 5 framework.
 * Version:             1.0.2
 * Author:              Frédéric Serva
 * Author URI:          http://www.tenbirdsflying.com/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         birds-portfolio
 * Domain Path:         /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Birds_Portfolio_Main {
    private static $instance = null;
    private $plugin_path;
    private $plugin_url;
    private $text_domain = 'birds-portfolio';

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        // If an instance hasn't been created and set to $instance create an instance and set it to $instance.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
     */
    private function __construct() {
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url  = plugin_dir_url( __FILE__ );

        load_plugin_textdomain( $this->text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

        register_activation_hook( __FILE__, array( $this, 'activation' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

        $this->run_plugin();
    }

    public function get_plugin_url() {
        return $this->plugin_url;
    }

    public function get_plugin_path() {
        return $this->plugin_path;
    }

    /**
     * Place code that runs at plugin activation here.
     */
    public function activation() {

    }

    /**
     * Place code that runs at plugin deactivation here.
     */
    public function deactivation() {

    }

    /**
     * Enqueue and register Admin JavaScript files here.
     */
    public function register_admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_script( 'birds-media', plugins_url( '/birds-portfolio/admin/js/media.js' ), array( 'jquery', 'jquery-ui-sortable' ), null, true );

        wp_localize_script( 'birds-media', 'birds_media',
                           array(
                               'title'  => __( 'Upload or Choose the Image Files to include to the Project', 'birds-portfolio' ),
                               'button' => __( 'Add to Gallery', 'birds-portfolio' ),
                               'attr'   => __( 'Remove image', 'birds-portfolio' )
                           )
                          );

    }

    /**
     * Enqueue and register Admin CSS files here.
     */
    public function register_admin_styles() {
        wp_enqueue_style( 'birds-metaboxes-style', plugins_url( '/birds-portfolio/admin/css/portfolio_admin.css' ), array( 'dashicons' ), null );

    }

    /**
     * Enqueue and register Frontend JavaScript files here.
     */
    public function register_scripts() {
    }

    /**
     * Enqueue and register Frontend CSS files here.
     */
    public function register_styles() {
        wp_register_style( 'front-portfolio-css', plugins_url( '/birds-portfolio/public/css/portfolio_front.css' ), null, null, 'all' );
        wp_enqueue_style( 'front-portfolio-css' );
    }

    /**
     * Place code for your plugin's functionality here.
     */
    private function run_plugin() {

        // Page Templater
        require_once $this->plugin_path . 'includes/class-page-templater.php';
        // Custom Post Type
        require_once $this->plugin_path . 'includes/cpt.php';
        // Messages
        require_once $this->plugin_path . 'admin/messages.php';
        // Metabox
        require_once $this->plugin_path . 'admin/metabox.php';
        // Admin functions
        require_once $this->plugin_path . 'admin/admin.php';

        /**
        * Single page for projects
        */
        add_filter( 'template_include', function( $template ) {

            $birds_types = array( 'birds_portfolio' );
            $post_type = get_post_type();
            if ( ! in_array( $post_type, $birds_types ) ) {
                return $template;
            } else {
                $themetemplate = TEMPLATEPATH . '/templates/single-portfolio.php';
                if(!is_file( $themetemplate ) ){
                    return $this->plugin_path . 'assets/templates/single-portfolio.php';
                } else {
                    return $themetemplate;
                }
            }

        });

        /**
        * Portfolio Archives
        */
        add_filter( 'template_include', function( $template ) {
            if ( is_post_type_archive( 'birds_portfolio' ) || is_tax( 'portfolio_categories' ) ) {
                $themetemplate = TEMPLATEPATH . '/templates/archive-portfolio.php';
                if(!is_file( $themetemplate ) ){
                    return $this->plugin_path . 'assets/templates/archive-portfolio.php';
                } else {
                    return $themetemplate ;
                }
            } else {
                return $template;
            }
        });



    }
}

Birds_Portfolio_Main::get_instance();
