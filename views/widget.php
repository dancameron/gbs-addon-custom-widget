
<p><?php echo $description ?></p>

<form id="claim_voucher" action="" method="post">

	<!--<span class="gb_widget_input">
		<label for="gb_voucher_claim_name" class="cloak"><?php gb_e( 'Name' ) ?></label>
		<input type="text" name="gb_voucher_redemption_data[gb_voucher_claim_name]" id="gb_voucher_claim_name" placeholder="<?php gb_e( 'Name' ) ?>"/>
	</span>-->

	<span class="gb_widget_input">
		<label for="gb_voucher_claim_voucher_id" class="cloak"><?php gb_e( 'Voucher Serial' ) ?></label>
		<input type="text" name="gb_voucher_redemption_data[gb_voucher_claim_voucher_id]" id="gb_voucher_claim_voucher_serial" placeholder="<?php gb_e( 'voucher id or' ) ?>" />
	</span>

	<span class="gb_widget_input">
		<label for="gb_voucher_claim_security_code" class="cloak"><?php gb_e( 'Security Code' ) ?></label>
		<input type="text" name="gb_voucher_redemption_data[gb_voucher_claim_security_code]" id="gb_voucher_claim_security_code" placeholder="<?php gb_e( 'security code' ) ?>" />
	</span>

	<span class="gb_widget_input">
		<label for="gb_voucher_claim_security_code" class="cloak"><?php gb_e( 'Notes' ) ?></label>
		<textarea type="textarea" name="gb_voucher_redemption_data[gb_voucher_claim_notes]" id="gb_voucher_claim_notes" placeholder="<?php gb_e( 'notes to added to the voucher claim' ) ?>" ></textarea>
	</span>

	<input type="hidden" name="redirect_to" value="<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">

	<input type="submit" class="form-submit" value="<?php gb_e( 'Submit' ); ?>" />
	<?php wp_nonce_field( 'merchant_voucher_claim', 'merchant_voucher_claim_nonce' ) ?>
</form>