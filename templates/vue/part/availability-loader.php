<v-container v-if="availability_lock && can_get_availability" style="min-height: 150px;" >
  <v-row
	class="fill-height"
	align-content="center"
	justify="center"
  >
	<v-col
	  class="text-subtitle-1 text-center"
	  cols="12"
	><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblCheckingAvailability')); ?></v-col>
	<v-col cols="6">
	  <v-progress-linear
		indeterminate
		v-bind="props('progress-linear')"
	  ></v-progress-linear>
	</v-col>
  </v-row>
</v-container>