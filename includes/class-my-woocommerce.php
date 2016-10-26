<?php

/**
 * Hook into WooCommerce admin settings
 *
 * @class 		MY_WooCommerce
 * @version		2.0.0
 * @category	Class
 */
class MY_WooCommerce {

    public function __construct() {

        add_filter( 'product_type_selector', [&$this, 'add_subscription_product'] );
        add_action( 'woocommerce_product_options_general_product_data', [&$this, 'subscription_pricing_fields'] );

        add_action( 'admin_footer', [&$this, 'simple_subscription_custom_js'] );

        add_filter( 'woocommerce_product_data_tabs', [&$this, 'custom_product_tabs'] );
        add_action( 'woocommerce_product_data_panels', [&$this, 'subscription_options_product_tab_content'] );

        /**
         * @see WC_Meta_Box_Product_Data::save()
         * 'woocommerce_process_product_meta_' . $product_type
         */
        add_action( 'woocommerce_process_product_meta_subscription', [&$this, 'save_subscription_option_field'], 10, 1  );
    }

    /**
     * add product to selection of product types
     *
     * @param $types
     * @return mixed
     */
    public function add_subscription_product( $types ) {
        // Key should be exactly the same as in the class product_type parameter
        $types[ 'subscription' ] = __( 'Subscription' );

        return $types;
    }

    /**
     * If you are not having custom pices
     */
    public function simple_subscription_custom_js() {
        if ( 'product' != get_post_type() ) return;
        ?><script>
            jQuery( document ).ready( function() {
                jQuery('.options_group.pricing').addClass('show_if_subscription').show();
                var input_virtual = jQuery('input#_virtual');
                input_virtual.parent().addClass('show_if_subscription').show();
                input_virtual.prop('checked', true);
            });
        </script>
        <?php
    }

    /**
     * This is to show price if not have custom prices @see WP_Product_Simple
     */
    public function subscription_pricing_fields() {
        echo '<div class="options_group show_if_subscription"></div>';
    }

    /**
     * adding an admin tab for custom product type
     */
    public function custom_product_tabs( $tabs ) {
        $tabs['linked_product']['class'][] = 'hide_if_subscription';

        $tabs['subscription'] = array(
            'label'     => __( 'Subscription settings', 'woocommerce' ),
            'target'    => 'subscription_options',
            'class'     => ['show_if_subscription', 'show_if_variable_subscription'],
        );

        return $tabs;
    }

    /**
     * addming meta boxs for your custom tab
     */
    public function subscription_options_product_tab_content() {
        ?>
        <div id='subscription_options' class='panel woocommerce_options_panel'>
            <div class='options_group'>
                <?php
                woocommerce_wp_text_input([
                    'id' => '_end_date',
                    'label' => 'End date',
                    'desc_tip' => 'true',
                    'description'	=> 'Used to calculate the cost per class',
                    'type' => 'date'
                ]);
                ?>
            </div>
        </div><?php
    }

    /**
     * save admin meta for product subscription
     * @param $post_id
     */
    public function save_subscription_option_field( $post_id ) {
        $end_date = isset( $_POST['_end_date'] ) ? $_POST['_end_date'] : '';

        update_post_meta( $post_id, '_end_date', sanitize_text_field( $end_date ) );
    }

}

new MY_WooCommerce();