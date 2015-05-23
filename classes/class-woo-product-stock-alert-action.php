<?php
/**
 * Start Checking subscribed customer and alert about stock 
 *
 */
class WOO_Product_Stock_Alert_Action {
	
	function stock_alert_action() {
		global $WC;
	
		$all_products = array();
		$all_products = get_posts(
				array(
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => -1
				)
		);
		
		$all_product_ids = array();
		if( !empty($all_products) && is_array($all_products) ) {
			foreach( $all_products as $products_each ) {
				$all_product_ids[] = $products_each->ID;
			}
		}
		
		$get_subscribed_user = array();
		if( !empty($all_product_ids) && is_array($all_product_ids) ) {
			foreach( $all_product_ids as $all_product_id ) {
				$_product_subscriber = get_post_meta($all_product_id, '_product_subscriber', true);
				if ( $_product_subscriber && !empty($_product_subscriber) ) {
					$get_subscribed_user[$all_product_id] = $_product_subscriber;
				}
			}
		}
		
		$admin_email = '';
		$admin_email = get_option('admin_email');
		
		if( !empty($get_subscribed_user) && is_array($get_subscribed_user) ) {
			foreach( $get_subscribed_user as $id => $subscriber ) {
				
				$product_availability_status = get_post_meta( $id, '_stock_status', true );
				if( $product_availability_status == 'instock' ) {
				
					$email = WC()->mailer()->emails['WC_Email_Stock_Alert'];
					foreach( $subscriber as $to ) {
						$email->trigger( $to, $id );
					}
					
					delete_post_meta( $id, '_product_subscriber' );
				}
			}
		}
		
	}
	
}
?>