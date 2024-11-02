<v-container class="grey lighten-5">
	<v-row justify="space-between" >
		<v-col cols="12" class="order-2">
			<?php setrio_bizcal_get_template_part('vue/part/date',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/speciality',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/location',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/payment_type',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/' . ($args['appointment_param_order'] ? 'physician' : 'service'),array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/' . (!$args['appointment_param_order'] ? 'physician' : 'service'),array('rargs' => $args)); ?>
		<?php setrio_bizcal_get_template_part('vue/part/notify-alert', $args) ?>
		<?php setrio_bizcal_get_template_part('vue/part/availability',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
	<v-row>
		<v-col cols="12" md="6" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/lastname',array('rargs' => $args)); ?>
		</v-col>
		<v-col cols="12" md="6" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/firstname',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
	<v-row>
		<v-col cols="12" md="6" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/phone',array('rargs' => $args)); ?>
		</v-col>
		<v-col cols="12" md="6" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/email',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
	<v-row>
		<v-col cols="12" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/observations',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
	
	<v-row>
		<v-col cols="12" class="pb-0 pt-5 d-flex justify-space-between flex-wrap flex-grow-1">
			<div class="bizcal-vue-grecaptcha-wrapper d-flex flex-column justify-center flex-grow-1" >
				<?php setrio_bizcal_get_template_part('vue/part/recaptcha',array('rargs' => $args)); ?>
			</div>
			<div class="d-flex justify-center justify-sm-end flex-grow-1">
				<?php setrio_bizcal_get_template_part('vue/part/logo',array('rargs' => $args)); ?>
			</div>
		</v-col>
	</v-row>
	<v-row>
		<v-col cols="12" class="pb-0">
			<?php setrio_bizcal_get_template_part('vue/part/terms',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/data_policy',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/newsletter',array('rargs' => $args)); ?>
			<?php setrio_bizcal_get_template_part('vue/part/payment',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
	<v-row>
		<v-col cols="12">
			<?php setrio_bizcal_get_template_part('vue/part/submit',array('rargs' => $args)); ?>
		</v-col>
	</v-row>
</v-container>