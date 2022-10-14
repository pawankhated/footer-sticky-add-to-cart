<?php     


	if ( is_product() ){
            
          $html='<div class="stricky-footer">';
        
      	  $product = get_product(get_the_ID());
      	  $current_product_id = get_the_ID(); 
      	  $product = wc_get_product( $current_product_id );         
      	  $checkout_url = wc_get_checkout_url();
	      $quantity_field = woocommerce_quantity_input( array(
	      'input_name'  => 'product_id',
	      'input_value' => ! empty( $product->cart_item['quantity'] ) ? $product->cart_item['quantity'] : 0,
	      'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
	      'min_value'   => 0,
	    ), $product, false );
         
            $html.=$product->get_price_html();
            $html.='<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
            $html.= woocommerce_quantity_input( array(), $product, false );
            $html.= '<div class="btn-box"><button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
            if( $product->is_type( 'simple' ) ){
              $html.='<a href="javascript:void(0);" class="wpcbn-btn-single buy-now button">Jetzt Kaufen</a></div>';
          }
          $html.= '</form>';
          
		$html.= '</div>';
		return $html;
  

  }