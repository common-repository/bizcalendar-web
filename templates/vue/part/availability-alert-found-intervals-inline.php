<v-alert ref="availability_alert_found_intervals" v-if="!loading_ajax && availability_list && availability_list.length && availability_list[0].RequestedDateAvailabilities"
	type="success"
	v-bind="props('alert-success')"
>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtFoundAvailableAppointments')); ?></div>
</v-alert>