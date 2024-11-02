<template v-if="show_dialog_submit_fail_button && show_dialog_submit_fail.error.code != 10">
	<v-alert
		type="error"
		v-bind="props('alert-error')"
	>
		<div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtCannotRequestAppointment')); ?></div>
		<div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnHelpAppointments')); ?></div>
		
		<template v-if="show_dialog_submit_fail.error.code">
			<div v-html="show_dialog_submit_fail.error.message"></div>
		</template>
	</v-alert>
</template>
<?php setrio_bizcal_get_template_part('vue/part/submit-dialog-notified', $args) ?>