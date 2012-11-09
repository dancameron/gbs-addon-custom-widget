<?php
class Group_Buying_Widget_Addon extends Group_Buying_Controller {

	public static function init() {
		// Hook this plugin into the GBS add-ons controller
		add_filter( 'gb_addons', array( get_class(), 'gb_load_addon' ), 10, 1 );
	}

	public static function gb_load_addon( $addons ) {
		$addons['rewards'] = array(
			'label' => self::__( 'Custom Widget' ),
			'description' => self::__( 'Merchant widget to help voucher claiming.' ),
			'files' => array(
				__FILE__,
			),
			'callbacks' => array(
				array( 'Group_Buying_Widget', 'init' ),
				array( 'Group_Buying_Voucher_Claim_Modifications', 'init' ),
			),
		);
		return $addons;
	}

}

class Group_Buying_Voucher_Claim_Modifications extends Group_Buying_Controller {

	public static function init() {
		add_action( 'init', array( get_class(), 'attempt_process_claim' ) );
	}

	public function attempt_process_claim() {
		if ( !empty( $_POST ) && wp_verify_nonce($_POST['merchant_voucher_claim_nonce'], 'merchant_voucher_claim') ) {
			
			extract( $_POST['gb_voucher_redemption_data'] );

			if ( isset( $gb_voucher_claim_security_code ) ) {
				$voucher_ids = Group_Buying_Voucher::get_voucher_by_security_code( $gb_voucher_claim_security_code );
				$voucher_id = array_shift( $voucher_ids );
			}
			elseif ( isset( $gb_voucher_claim_voucher_id ) ) {
				$voucher_ids = Group_Buying_Voucher::get_voucher_by_serial( $gb_voucher_claim_voucher_id );
				$voucher_id = array_shift( $voucher_ids );
			}
			elseif ( isset( $gb_voucher_claim_name ) ) {
				// nothing yet
			}

			$voucher = Group_Buying_Voucher::get_instance( $voucher_id );
			$claimed = FALSE;
			if ( is_a( $voucher, 'Group_Buying_Voucher' ) ) {
				if ( FALSE != $voucher->set_claimed_date() ) {
					do_action( 'gb_voucher_merchant_redeemed', $voucher );
					$voucher->set_redemption_data( $_POST['gb_voucher_redemption_data'] );
					self::set_message( __( 'Voucher Claim Status Updated.' ), self::MESSAGE_STATUS_INFO );
					return;
				}
				else {
					self::set_message( __( 'Error: Voucher already claimed.' ), self::MESSAGE_STATUS_ERROR );
					return;
				}
			}
			if ( !$claimed ) {
				self::set_message( __( 'Error: Security code or Voucher ID is not valid.' ), self::MESSAGE_STATUS_ERROR );
				return;
			}

		}
	}

}

class Group_Buying_Widget extends WP_Widget {

	public static function init() {
		add_action( 'widgets_init', array( get_class(), 'register_widgets' ) );
	}

	public function register_widgets() {
		return register_widget( 'Group_Buying_Widget' );
	}

	function Group_Buying_Widget() {
		$widget_ops = array( 'description' => gb__( 'Merchant widget to help voucher claiming.' ) );
		parent::WP_Widget( false, $name = gb__( 'Group Buying :: Merchant Widget' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$description = ( $instance['description'] != '' ) ? $instance['description'] : gb__('Enter a Voucher ID or Security Code and your claim notes to mark a voucher as claimed.') ;

		do_action( 'gb_pre_merchant_claim_widget', $args, $instance );
		echo $before_widget;
		echo $before_title . $title . $after_title;

		include 'views/widget.php';

		echo $after_widget;

		do_action( 'gb_merchant_claim_widget', $args, $instance );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['description'] = strip_tags( $new_instance['description'] );
		return $instance;
	}

	function form( $instance ) {
		$title = esc_attr( $instance['title'] );
		$description = esc_attr( $instance['description'] );
		?>
            <p>
            	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Note (above fields):' ); ?></label>
            	<textarea class="widefat" type="textarea" name="<?php echo $this->get_field_name( 'description' ); ?>" id="<?php echo $this->get_field_id( 'description' ); ?>"><?php echo $description; ?></textarea>
            </p>

        <?php
	}
}
