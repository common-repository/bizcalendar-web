<v-alert ref="availability_alert_no_intervals" v-if="!loading_ajax && !availability_lock && !availability_list.length && can_get_availability && !(show_dialog_submit_fail_button && show_dialog_submit_fail.error.code != 10)"
	type="warning"
	v-bind="props('alert-warning')"
>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnNoAvailableAppointments')); ?></div>
   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnHelpAppointments')); ?></div>
</v-alert>