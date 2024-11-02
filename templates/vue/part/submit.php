<v-btn
	:disabled="loading_ajax || submitting"
	:loading="loading_ajax || submitting"
	v-if="!(show_dialog_submit_fail_button)"
	v-on:click="submit"
	color="primary"
	v-bind="props('button-primary')"
	class="mt-3"
>
	<template v-if="loading_ajax || submitting"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtLoading')); ?></template>
	<template v-else><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnRequestAppointment')); ?></template>
</v-btn>
<template>
<?php setrio_bizcal_get_template_part('vue/part/notify',array('rargs' => $args)); ?>
</template>
<?php setrio_bizcal_get_template_part('vue/part/submit-dialog-errors', $args) ?>
<?php setrio_bizcal_get_template_part('vue/part/submit-dialog-success', $args) ?>
<?php setrio_bizcal_get_template_part('vue/part/submit-dialog-fail', $args) ?>