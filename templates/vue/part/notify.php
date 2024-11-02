<template v-if="show_dialog_submit_fail_button">
	<v-btn
		:disabled="loading_ajax || submitting"
		v-on:click="notifyAdmin"
		color="error"
		v-bind="props('button-error')"
		class="mt-3"
	>
		<template v-if="loading_ajax || submitting"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtLoading')); ?></template>
		<template v-else><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnCannotRequestAppointment')); ?></template>
	</v-btn>
</template>