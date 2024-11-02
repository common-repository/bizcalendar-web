<v-dialog
  v-model="show_dialog_submit_errors"
  max-width="340"
  scrollable
>
  <v-card v-bind="props('card')">
	<v-card-title v-bind="props('card-title')" class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgErrFormTitle')); ?></v-card-title>

	<v-card-text v-bind="props('card-text')" style="max-height: 300px;">
	   <div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgErrFormBody')); ?></div>
	   <template v-if="$refs.form">
	   <template v-for="input,input_index in $refs.form.inputs">
		<template v-if="input.errorCount" v-for="error_message,error_message_index in input.errorBucket">
			<div v-if="error_message.length" class="mb-3">{{ error_message }}</div>
		</template>
	   </template>
	   </template>
	</v-card-text>

	<v-card-actions v-bind="props('card-actions')">
		<v-spacer></v-spacer>
	  <v-btn
		 v-bind="props('button-secondary')"
		color="secondary"
		v-on:click="show_dialog_submit_errors = false"
	  ><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnGotIt'));  ?></v-btn>
	</v-card-actions>
  </v-card>
</v-dialog>