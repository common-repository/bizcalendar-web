<v-dialog
  v-model="show_dialog_submit_fail.open"
  max-width="500"
  scrollable
>
  <v-card v-bind="props('card')">
	<v-card-title class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgErrSubmitTitle')); ?></v-card-title>

	<v-card-text v-bind="props('card-text')" style="max-height: 300px;"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgErrSubmitBody')); ?></v-card-text>
	<v-card-text v-bind="props('card-text')" style="max-height: 300px;" v-html="show_dialog_submit_fail.error.message"></v-card-text>

	<v-card-actions v-bind="props('card-actions')">
		<v-spacer></v-spacer>
	  <v-btn
		 v-bind="props('button-secondary')"
		color="secondary"
		v-on:click="show_dialog_submit_fail = false"
	  ><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnGotIt'));  ?></v-btn>
	</v-card-actions>
  </v-card>
</v-dialog>