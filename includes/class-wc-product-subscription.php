<?php

/**
 *
 * @class 		WC_Product_Subscription
 * @version		2.0.0
 * @category	Class
 */
class WC_Product_Subscription extends WC_Product_Simple {

    public $product_custom_fields;

    /**
     * Initialize product.
     *
     * @param mixed $product
     */
    public function __construct( $product ) {
        parent::__construct( $product );
        $this->product_type = 'subscription';
        $this->virtual = 'yes';

        // Load all meta fields
        $this->product_custom_fields = get_post_meta( $this->id, '', true );
    }

    public function get_end_date() {
        return current( $this->product_custom_fields['_end_date'] );
    }
}