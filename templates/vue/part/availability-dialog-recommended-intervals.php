<v-dialog
  v-model="show_dialog_recommended_intervals"
  max-width="340"
>
  <v-card v-bind="props('card')">
	<v-card-title v-bind="props('card-title')" class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtLaterAvailableAppointments')); ?></v-card-title>

	<v-card-text v-bind="props('card-text')">
	   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnNoAvailableAppointments')); ?></div>
	   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnShowingClosestAvailableAppointments')); ?></div>
	   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnNoAvailableAppointmentsOnce')); ?></div>
	</v-card-text>

	<v-card-actions v-bind="props('card-actions')">
	  <v-btn
		 v-bind="props('button-secondary')"
		color="primary"
		v-on:click="show_dialog_recommended_intervals = false"
	  ><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnGotIt'));  ?></v-btn>
	</v-card-actions>
  </v-card>
</v-dialog>