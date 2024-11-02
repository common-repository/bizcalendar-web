<v-dialog v-model="dialog" max-width="800" style='z-index:20001;' scrollable attach="#setrio-bizcal-vue-popup">
	<?php if($args['button_style'] == 'vue'){ ?>
	<template v-slot:activator="{ on, attrs }">
		<v-btn
		class="setrio-bizcal-appointment-button <?php echo esc_attr($args['class']); ?>"
		color="primary"
		v-bind="props('button-primary')"
		v-on="on"
		>
		{{ $data.title }}
		</v-btn>
	</template>
	<?php } ?>
	<v-card v-bind="props('card')">
		<v-card-title v-bind="props('card-title')">
			<?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblRequestAppointmentTitle')); ?>
			<v-spacer></v-spacer>
				<v-btn icon @click="dialog = false" >
				<v-icon>mdi-close</v-icon>
			</v-btn>
		</v-card-title>
		<?php setrio_bizcal_get_template_part('vue/part/progress',array('rargs' => $args)); ?>
		<v-card-text class="pa-0">
			<v-window v-model="step" scrollable>
				<v-window-item :value="1">
					<v-card-text v-bind="props('card-text')">
					<v-row>
						<v-col cols="12" md="6" class="pb-0">
							<?php setrio_bizcal_get_template_part('vue/part/speciality',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/location',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/payment_type',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/' . ($args['appointment_param_order'] ? 'physician' : 'service'),array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/' . (!$args['appointment_param_order'] ? 'physician' : 'service'),array('rargs' => $args)); ?>
						</v-col>
						<v-col cols="12" md="6" class="pb-0">
							<?php setrio_bizcal_get_template_part('vue/part/date',array('rargs' => $args)); ?>
						</v-col>
						<v-col cols="12">
							<?php setrio_bizcal_get_template_part('vue/part/notify-alert', $args) ?>
							<?php setrio_bizcal_get_template_part('vue/part/availability-loader',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/availability-alert-found-intervals-inline',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/availability-alert-no-intervals-inline',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/availability-alert-recommended-intervals-inline',array('rargs' => $args)); ?>
						</v-col>
					</v-row>
					<?php // setrio_bizcal_get_template_part('vue/part/availability-dialog-no-intervals',array('rargs' => $args)); ?>
					<?php // setrio_bizcal_get_template_part('vue/part/availability-dialog-recommended-intervals',array('rargs' => $args)); ?>
					</v-card-text>
					<?php /*<v-tour name="step_1_tour" ref="step_1_tour" :steps="step_1_tour_steps" @hook:mounted="dialog ? startTour() : false">
					<template slot-scope="tour">
						<transition name="fade">
						  <v-step
							v-if="tour.steps[tour.currentStep]"
							:key="tour.currentStep"
							:step="tour.steps[tour.currentStep]"
							:previous-step="tour.previousStep"
							:next-step="tour.nextStep"
							:stop="tour.stop"
							:skip="tour.skip"
							:is-first="tour.isFirst"
							:is-last="tour.isLast"
							:labels="tour.labels"
							class="white--background warning--text error--border"
						  >
							<template>
							  <div v-if="!tour.isLast" slot="actions">
								<v-btn v-if="tour.currentStep === 0 && speciality_value" @click="tour.nextStep" color="primary" v-bind="props('button-primary')">Am inteles</v-btn>
							  </div>
							  <div v-if="!tour.isLast" slot="actions">
								<v-btn v-if="tour.currentStep === 1 && location_value" @click="tour.nextStep" color="primary" v-bind="props('button-primary')">Am inteles</v-btn>
							  </div>
							  <div v-if="tour.isLast" slot="actions">
								<v-btn @click="tour.stop" color="primary" v-bind="props('button-primary')">Inchide</v-btn>
							  </div>
							</template>
						  </v-step>
						</transition>
					  </template>
					
					</v-tour>
					*/ ?>
				</v-window-item>

				<v-window-item :value="2">
				
					<v-card-text v-bind="props('card-text')">
					<div class="text-h5 text-center mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblAppointmentTime')) ?></div>
					<v-row>
						<v-col cols="12" class="pb-0">
							<?php setrio_bizcal_get_template_part('vue/part/availability',array('rargs' => $args)); ?>
						</v-col>
					</v-row>
					</v-card-text>
				</v-window-item>

				<v-window-item :value="3">
					<v-card-text v-bind="props('card-text')">
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
						<v-col cols="12" class="pb-3">
							<?php setrio_bizcal_get_template_part('vue/part/observations',array('rargs' => $args)); ?>
						</v-col>
					</v-row>
					</v-card-text>
				</v-window-item>
				<v-window-item :value="4">
					<v-card-text v-bind="props('card-text')">
					<v-row>
						<v-col cols="12" class="pb-0">
							<?php setrio_bizcal_get_template_part('vue/part/summary',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/terms',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/data_policy',array('rargs' => $args)); ?>
							<?php setrio_bizcal_get_template_part('vue/part/newsletter',array('rargs' => $args)); ?>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12" class="pb-0 d-flex justify-space-between flex-wrap flex-grow-1">
							<div class="bizcal-vue-grecaptcha-wrapper d-flex flex-column justify-center flex-grow-1 mb-3">
								<?php setrio_bizcal_get_template_part('vue/part/recaptcha',array('rargs' => $args)); ?>
							</div>
							<div class="d-flex justify-center justify-sm-end flex-grow-1 mb-3">
								<?php setrio_bizcal_get_template_part('vue/part/logo',array('rargs' => $args)); ?>
							</div>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12" class="pb-0">
							<?php setrio_bizcal_get_template_part('vue/part/payment',array('rargs' => $args)); ?>
						</v-col>
					</v-row>
					</v-card-text>
				</v-window-item>
			</v-window>
		</v-card-text>
		<v-card-actions v-bind="props('card-actions')" class="flex-wrap mt-n3">
			<v-btn v-bind="props('button')" :disabled="!canStep(-1)" v-on:click="step--" color="secondary" class="mt-3" :loading="loading_ajax || submitting">
				<?php BizCalendar\wp_kses_post(setrio_bizcal_message('textPrev')) ?>
			</v-btn>
			<?php setrio_bizcal_get_template_part('vue/part/loader-modal',array('rargs' => $args)); ?>
			<v-spacer></v-spacer>
			<template v-if="step === 4">
			<?php setrio_bizcal_get_template_part('vue/part/submit',array('rargs' => $args)); ?>
			</template>
			<template v-else>
			<v-btn v-bind="props('button')" :disabled="!canStep(1)" color="primary" depressed v-on:click="step++" class="mt-3":loading="loading_ajax || submitting">
				<?php BizCalendar\wp_kses_post(setrio_bizcal_message('textNext')) ?>
			</v-btn>
			</template>
		</v-card-actions>
	</v-card>
</v-dialog>