<template v-if="loading_ajax || submitting">
<v-spacer></v-spacer>
<v-progress-linear class="mt-2" style="width: calc(100% - 240px);"
indeterminate
v-bind="props('progress-linear')"
></v-progress-linear>
</template>
<template v-else-if="step==1">
	<v-spacer></v-spacer>
	<div @click="($refs.availability_alert_found_intervals || $refs.availability_alert_found_recommended_intervals || $refs.availability_alert_no_intervals || $refs.date_picker).$el.scrollIntoView()">
	<v-icon v-if="!loading_ajax && availability_list && availability_list.length && availability_list[0].RequestedDateAvailabilities" aria-hidden="false" color="success" class="mt-2">mdi-check</v-icon>
	<v-icon v-else-if="!loading_ajax && !availability_lock && !availability_list.length && can_get_availability && !(show_dialog_submit_fail_button && show_dialog_submit_fail.error.code != 10)" aria-hidden="false" color="error" class="mt-2">mdi-alert</v-icon>
	<v-icon v-else-if="!loading_ajax && availability_list && availability_list.length && !availability_list[0].RequestedDateAvailabilities" aria-hidden="false" color="warning" class="mt-2">mdi-exclamation</v-icon>
	</div>
</template>