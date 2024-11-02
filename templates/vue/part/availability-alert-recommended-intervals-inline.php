<v-alert ref="availability_alert_found_recommended_intervals" v-if="!loading_ajax && availability_list && availability_list.length && !availability_list[0].RequestedDateAvailabilities"
	type="warning"
	v-bind="props('alert')"
>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnNoAvailableAppointments')); ?></div>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnShowingClosestAvailableAppointments')); ?></div>
</v-alert>