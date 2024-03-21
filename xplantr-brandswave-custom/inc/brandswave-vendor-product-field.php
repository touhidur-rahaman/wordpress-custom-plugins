<?php
/*
* Adding extra field on New product popup/without popup form
*/

add_action( 'dokan_new_product_after_product_tags','new_product_field',10 );

function new_product_field(){ ?>

     <div class="dokan-form-group">
              <input type="text" class="dokan-form-control" name="product_weight_field" placeholder="<?php esc_attr_e( 'Product Weight', 'dokan-lite' ); ?> required">
        </div>

   <?php
}

/*
* Saving product field data for edit and update
*/

 add_action( 'dokan_new_product_added','save_add_product_meta', 10, 2 );
 add_action( 'dokan_product_updated', 'save_add_product_meta', 10, 2 );

function save_add_product_meta($product_id, $postdata){

    $product = wc_get_product( $product_id );

    
    
    if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            return;
        }
        
        if ( ! empty( $postdata['product_weight_field'] ) ) {
            update_post_meta( $product_id, '_weight', $postdata['product_weight_field'] );
        }
}

/*
* Showing field data on product edit page
*/

add_action('dokan_product_edit_after_product_tags','show_on_edit_page',99,2);

function show_on_edit_page($post, $post_id){
$product_weight_field         = get_post_meta( $post_id, '_weight', true );
?>
   <div class="dokan-form-group">
        <input type="hidden" name="product_weight_field" id="dokan-edit-product-id" value="<?php echo esc_attr( $post_id ); ?>"/>
        <label for="product_weight_field" class="form-label"><?php esc_html_e( 'Product Weight', 'dokan-lite' ); ?></label>
        <?php dokan_post_input_box( $post_id, 'product_weight_field', array( 'placeholder' => __( 'product weight', 'dokan-lite' ), 'value' => $product_weight_field, 'required'=>'required' ) ); ?>
        <div class="dokan-product-title-alert dokan-hide">
         <?php esc_html_e( 'Please enter product weight!', 'dokan-lite' ); ?>
        </div>
     </div> <?php

}
