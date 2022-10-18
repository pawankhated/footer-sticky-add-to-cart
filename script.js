$(document).ready(function(){
  $(window).scroll(function(){
    if ($(window).scrollTop() > 150){
        $('.stricky-footer').addClass( "show-footer",100);
    }
    else {
    $('.stricky-footer').removeClass("show-footer",100);
    }
 });
});

 let getVariantId=jQuery(".variation_id").val();
    if(getVariantId==0){
      jQuery(".btn_add_cart").attr("disabled","disabled");
      jQuery(".btn_direct_checkout").attr("disabled","disabled");
    }

    jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) { 

        let getVariantId=jQuery(".variation_id").val();
        console.log(variation);
        console.log(variation.price_html);

          if(getVariantId!=""){
            jQuery(".price-box").html(variation.price_html);
            jQuery("#btn_custom_variation_id").val(getVariantId);
            jQuery(".btn_add_cart").attr("disabled",false);
            jQuery(".btn_direct_checkout").attr("disabled",false);

          }else{

              jQuery(".btn_add_cart").attr("disabled",true);
              jQuery(".btn_direct_checkout").attr("disabled",true);

          }
    });