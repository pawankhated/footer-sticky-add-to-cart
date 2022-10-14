<?php
// add to cart quantity plus minus
add_action( 'woocommerce_after_quantity_input_field', 'show_quantity_plus' ); 
function show_quantity_plus() {
    if ( is_product()){
   echo '<button type="button" class="plus">+</button>';
   }
} 
add_action( 'woocommerce_before_quantity_input_field', 'show_quantity_minus' );
  
function show_quantity_minus() {
    if ( is_product()){
        echo '<button type="button" class="minus">-</button>';
    }
}
// 2. Trigger update quantity script
  
add_action( 'wp_footer', 'add_cart_quantity_plus_minus' );
  
function bbloomer_add_cart_quantity_plus_minus() {
 
   if ( !is_product()) return;
    
   wc_enqueue_js( "   
           
      $(document).on( 'click', 'button.plus, button.minus', function() {
  
         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));
 
         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
                $('.qty').val(max).change();
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
               $('.qty').val(val + step).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
               $('.qty').val(min).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
               $('.qty').val(val - step).change();
            }
         }
 
      });
	  
	  $(document).ready(function(){
            $('.wpcbn-btn-single').click(function(){
                let quantity=$('input[name=quantity]').val();
		       let checkouturl=$('#direct_checkout').attr('href');
               if(typeof $('input[name=variation_id]').val() != 'undefined'){
                    let variant_id=$('input[name=variation_id]').val();
                     // alert('/checkout/?add-to-cart='+variant_id+'&quantity='+quantity);return false;
                    window.location.href='/checkout/?add-to-cart='+variant_id+'&quantity='+quantity;
               }

               //alert(checkouturl+'&quantity='+quantity);return false;
             
               window.location.href=checkouturl+'&quantity='+quantity;
               //$('#direct_checkout').click();
              })

});
        
   " ); 
}