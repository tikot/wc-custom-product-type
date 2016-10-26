<?php
/**
 * Plugin Name:  My Subscription Product
 * Description:  Custom Product Type demo
 * Version:      1.0.1
 * Author:       tikot
 * Author URI:  https://github.com/tikot
 * Text Domain: my-class
*/

/**
 * @since             1.0.0
 * @package           my-class
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'MyClass' ) ) :

final class MyClass {

    /**
     * Plugin version.
     *
     * @var string
     */
    public $version = '1.0.1';

    /**
     * The single instance of the class.
     *
     * @var MyClass
     * @since 2.1
     */
    protected static $_instance = null;

    /**
     * Main MyClass Instance.
     *
     * Ensures only one instance of WooCommerce is loaded or can be loaded.
     *
     * @since 2.0
     * @static
     * @return MyClass - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * MyClass Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     * @since  2.0
     */
    private function init_hooks() {

        add_action( 'init', [$this, 'init'], 0 );
    }

    public function init() {
        include_once 'includes/class-wc-product-subscription.php';
        include_once 'includes/class-my-woocommerce.php';

    }

    /**
     * Get the plugin path.
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
}
endif;

/**
 * Main instance of MyClass.
 *
 * @since  2.0
 * @return MyClass
 */
function MY() {
    return MyClass::instance();
}
MY();
