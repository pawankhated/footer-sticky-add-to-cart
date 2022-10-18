<?php

add_filter('add_to_cart_redirect', 'lw_add_to_cart_redirect');
function lw_add_to_cart_redirect() {

    if(isset($_POST['add_to_cart_redirect']) && $_POST['submit']=="direct"){
        global $woocommerce;
        $lw_redirect_checkout = $woocommerce->cart->get_checkout_url();
        return $lw_redirect_checkout;   
    }
 
}

function wc_qty_add_product_field() {

    echo '<div class="options_group">';
    woocommerce_wp_text_input( 
        array( 
            'id'          => '_wc_min_qty_product', 
            'label'       => __( 'Minimum Quantity', 'woocommerce-max-quantity' ), 
            'placeholder' => '',
            'desc_tip'    => 'true',
            'description' => __( 'Optional. Set a minimum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ) 
        )
    );
    echo '</div>';
}
add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field' );
function wc_qty_save_product_field( $post_id ) {
    $val_min = trim( get_post_meta( $post_id, '_wc_min_qty_product', true ) );
    $new_min = sanitize_text_field( $_POST['_wc_min_qty_product'] );


    
    if ( $val_min != $new_min ) {
        update_post_meta( $post_id, '_wc_min_qty_product', $new_min );
    }
}
add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field' );

function wc_qty_input_args( $args, $product ) {
    
    $product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
    
    $product_min = wc_get_product_min_limit( $product_id );

    if ( ! empty( $product_min ) ) {
        // min is empty
        if ( false !== $product_min ) {
            //$args['min_value'] = $product_min;
            $args['min_value'] = 1; 
        }
    }

    if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
        $stock = $product->get_stock_quantity();

        // $args['min_value'] = min( $stock, $args['min_value'] ); 
        $args['min_value'] = 1; 
    }

    return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'wc_qty_input_args', 10, 2 );


function wc_get_product_min_limit( $product_id ) {
    $qty = get_post_meta( $product_id, '_wc_min_qty_product', true );
    if ( empty( $qty ) ) {
        $limit = false;
    } else {
        $limit = (int) $qty;
    }
    return $limit;
}
/*
* Validating the quantity on add to cart action with the quantity of the same product available in the cart. 
*/
function wc_qty_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {

    $product_min = wc_get_product_min_limit( $product_id );

    if ( ! empty( $product_min ) ) {
        // min is empty
        if ( false !== $product_min ) {
            $new_min = $product_min;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }



    $already_in_cart    = wc_qty_get_cart_qty( $product_id );
    $product            = wc_get_product( $product_id );
    $product_title      = $product->get_title();

    if ($new_min > ( $quantity )) {

        $passed = false;            

            wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a minimum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-max-quantity' ), 
                        $new_min,
                        $product_title,
                        '<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>',
                        $quantity ),
                    $new_max,
                    $quantity ),
            'error' );

        return $passed;


    }

    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'wc_qty_add_to_cart_validation', 10, 6 );

/*
* Get the total quantity of the product available in the cart.
*/ 
function wc_qty_get_cart_qty( $product_id ) {
    global $woocommerce;
    $running_qty = 0; // iniializing quantity to 0

    // search the cart for the product in and calculate quantity.
    foreach($woocommerce->cart->get_cart() as $other_cart_item_keys => $values ) {
        if ( $product_id == $values['product_id'] ) {               
            $running_qty += (int) $values['quantity'];
        }
    }

    return $running_qty;
}
/*
* Changing the minimum quantity to 2 for all the WooCommerce products
*/

function woocommerce_quantity_input_min_callback( $min, $product ) {
    $min = 1;  
    return $min;
}
add_filter( 'woocommerce_quantity_input_min', 'woocommerce_quantity_input_min_callback', 10, 2 );

/*
* Validate product quantity when cart is UPDATED
*/

function wc_qty_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
    $product_min = wc_get_product_min_limit( $values['product_id'] );

    if ( ! empty( $product_min ) ) {
        // min is empty
        if ( false !== $product_min ) {
            $new_min = $product_min;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }


    $product = wc_get_product( $values['product_id'] );
    $already_in_cart = wc_qty_get_cart_qty( $values['product_id'], $cart_item_key );


    if ( isset( $new_min) && ( $quantity )  < $new_min ) {
        wc_add_notice( apply_filters( 'wc_qty_error_message', sprintf( __( 'You should have minimum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity' ),
                    $new_min,
                    $product->get_name(),
                    '<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'),
                $new_min ),
        'error' );
        $passed = false;
    }

    return $passed;
}
add_filter( 'woocommerce_update_cart_validation', 'wc_qty_update_cart_validation', 1, 4 );  