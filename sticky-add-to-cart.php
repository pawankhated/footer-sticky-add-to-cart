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


         
         //variation_id
            $html.='<div class="price-box">'.$product->get_price_html().'</div>';
            $html.= '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
            $html.= woocommerce_quantity_input( array(), $product, false );
            

           if( $product->is_type( 'simple' ) ){
              $html.= '<div class="btn-box"><button type="submit" class=" btn_add_cart button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
               $html.='<a href="javascript:void(0);" class="btn_direct_checkout wpcbn-btn-single buy-now button">Jetzt Kaufen</a></div>';
            }else{

              global $woocommerce;
              $lw_redirect_checkout = $woocommerce->cart->get_checkout_url();

                $html.= '<div class="btn-box"><input type="hidden" name="add_to_cart_redirect" value="'.$lw_redirect_checkout.'"><input type="hidden" name="add-to-cart" value="'.$current_product_id.'"><input type="hidden" name="product_id" value="'.$current_product_id.'"><input type="hidden" name="variation_id" id="btn_custom_variation_id" class="variation_id" value=""><button disabled="disabled" type="submit" name="submit" value="Add to cart" class=" btn_add_cart button alt">in den Warenkorb</button>';
               $html.='<button disabled="disabled" value="direct" name="submit" value="Direct Checkout" type="submit" class=" btn_add_cart button alt">Jetzt Kaufen</button></div>';
               


            }
          $html .= '</form>';
          $html.='</div>';
          $html;
  
  }