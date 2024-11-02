<?php if (setrio_bizcal_online_enabled()) {?>
<div class="text-center netopia-payment-wrapper mt-5" v-if="((<?php echo (int)(get_option('setrio_bizcal_payment_mobilpay_general', 0)) ?> || online_pay_value) && Physician && ((!isNaN(parseFloat(Physician.Price)) && parseFloat(Physician.Price) > 0) || ((payment_type_value == 2) && <?php echo (int)(get_option('setrio_bizcal_payment_mobilpay_free', 0) && get_option('setrio_bizcal_payment_mobilpay_free_cnas', 0)) ?>) || ((payment_type_value != 2) && <?php echo (int)get_option('setrio_bizcal_payment_mobilpay_free', 0) ?>)))">
<v-alert
	type="warning"
	v-bind="props('alert')"
>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblRedirectPayment')) ?></div>
</v-alert>
<a href="https://netopia-payments.com/" target="_blank" class="netopia-payment-link">
	<v-avatar tile width="330px">
		<v-img contain :src="'<?php echo esc_attr(plugins_url( '/assets/netopia-330x58.jpg', BIZCALENDAR_PLUGIN_FILE )); ?>'"></v-img>
	</v-avatar>
</a>
</div>
<?php } ?>