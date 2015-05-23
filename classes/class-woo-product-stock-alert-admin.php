<?php
class WOO_Product_Stock_Alert_Admin {
  
  public $settings;

	public function __construct() {
		//admin script and style
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
		
		add_action('woo_product_stock_alert_dualcube_admin_footer', array(&$this, 'dualcube_admin_footer_for_woo_product_stock_alert'));
		
		add_action('woocommerce_product_options_stock_fields', array(&$this, 'product_subscriber_details'));
		
		add_action('manage_edit-product_columns', array(&$this, 'custom_column'));
		add_action('manage_product_posts_custom_column', array(&$this, 'manage_custom_column'), 10, 2);
	}
	
	function dualcube_admin_footer_for_woo_product_stock_alert() {
    global $WOO_Product_Stock_Alert;
    ?>
    <div style="clear: both"></div>
    <div id="dc_admin_footer">
      <?php _e('Powered by', $WOO_Product_Stock_Alert->text_domain); ?> <a href="http://dualcube.com" target="_blank"><img src="<?php echo $WOO_Product_Stock_Alert->plugin_url.'/assets/images/dualcube.png'; ?>"></a><?php _e('Dualcube', $WOO_Product_Stock_Alert->text_domain); ?> &copy; <?php echo date('Y');?>
    </div>
    <?php
	}

	/**
	 * Admin Scripts
	 */

	public function enqueue_admin_script() {
		global $WOO_Product_Stock_Alert;
		
		wp_enqueue_script('admin_js', $WOO_Product_Stock_Alert->plugin_url.'assets/admin/js/admin.js', array('jquery'), $WOO_Product_Stock_Alert->version, true);
		wp_enqueue_style('admin_css',  $WOO_Product_Stock_Alert->plugin_url.'assets/admin/css/admin.css', array(), $WOO_Product_Stock_Alert->version);
	}
	
	/**
	 * Stock Alert news
	 */
	function product_subscriber_details() {
		global $post;
		
		$product_availability_status = get_post_meta( $post->ID, '_stock_status', true );
		
		if( $product_availability_status == 'outofstock' ) {
			
			$product_subscriber = get_post_meta( $post->ID, '_product_subscriber', true );
			
			if( !empty( $product_subscriber ) ) {
				
				$no_of_subscriber = count($product_subscriber);
				
				?>
					<p class="form-field _stock_field">
						<label class="">Number of Interested Person(s)</label>
						<span class="no_subscriber"><?php echo $no_of_subscriber; ?></span>
					</p>
				<?php
			} else {
				?>
					<p class="form-field _stock_field">
						<label class="">Number of Interested Person</label>
						<span class="no_subscriber">0</span>
					</p>
				<?php
			}
		}
		
	}
	
	/**
	 * Custom column addition
	 */
	function custom_column($columns) {
		
		return array_merge($columns, array( 'product_subscriber' =>__( 'Interested Person(s)')) );
	}
	
	/**
	 * Manage custom column
	 */
	function manage_custom_column( $column_name, $post_id ) {
		$no_of_subscriber = '';
		$product_subscriber = array();
		switch( $column_name ) {
			case 'product_subscriber' :
				$product_availability_status = get_post_meta( $post_id, '_stock_status', true );
				if( $product_availability_status == 'outofstock' ) {
					$product_subscriber = get_post_meta( $post_id, '_product_subscriber', true );
					if( !empty($product_subscriber) ) {
						$no_of_subscriber = count($product_subscriber);
						echo $no_of_subscriber;
					} else {
						echo '0';
					}
				}
		}
	}
	 
}