<?php
// Register the option
function brandwave_custom_option_init() {
register_setting( 'myoption-group', 'admin_mobile_for_sms', 'sanitize_text_field' ); // Sanitize text field (optional)
register_setting( 'myoption-group', 'brandwave_text_area', 'sanitize_textarea_field' );

}
add_action( 'admin_init', 'brandwave_custom_option_init' );
  
// Create the standalone options page
function brandwave_custom_options_page_create() {
add_menu_page( 
    'Brands Wave Custom Option Page', 
    'Brands Wave Custom Options', 
    'manage_options', 
    'brandwave-custom-options', 
    'brandwave_custom_options_page', 
    '', 
    20 
);
}
add_action( 'admin_menu', 'brandwave_custom_options_page_create' );
  
  // Create the options page
function brandwave_custom_options_page() {
    ?>
    <div class="wrap">
      <h1>Brands Wave Custom Option</h1>
      <form method="post" action="options.php">
        <?php settings_fields( 'myoption-group' ); ?>
        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row"><label for="brandwave_text_area">Checkout Tooltip Text:</label></th>
              <td>
                <!-- <input type="text" id="my_text_field" name="my_text_field" value="<?php //echo esc_attr( get_option( 'my_text_field' ) ); ?>"> -->
                <textarea id="brandwave_text_area" name="brandwave_text_area" rows="5" cols="50"><?php echo esc_textarea( get_option( 'brandwave_text_area' ) ); ?></textarea>
                <p class="description">Enter your text data here.</p>
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="admin_mobile_for_sms">Admin Phone Number:</label></th>
              <td>
                <input type="text" id="admin_mobile_for_sms" name="admin_mobile_for_sms" value="<?php echo esc_attr( get_option( 'admin_mobile_for_sms' ) ); ?>">
                <p class="description">Enter your mobile number here.</p>
              </td>
            </tr>
          </tbody>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
}