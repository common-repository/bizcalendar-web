<v-dialog
  v-model="show_dialog_submit_success.open"
  v-if="show_dialog_submit_success.open"
  max-width="800"
  scrollable
>
  <v-card v-bind="props('card')">
	<v-card-title v-if="show_dialog_submit_success.data.form" v-bind="props('card-title')" class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgWarning')); ?></v-card-title>
	<v-card-title v-else v-bind="props('card-title')" class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgSuccessSubmitTitle')); ?></v-card-title>
	<v-card-text v-bind="props('card-text')" v-html="show_dialog_submit_success.data.message"></v-card-text>
	<v-card-actions v-bind="props('card-actions')">
		<v-spacer></v-spacer>
	  <v-btn
		color="secondary"
		v-bind="props('button-secondary')"
		v-on:click="show_dialog_submit_success = false"
	  ><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnGotIt'));  ?></v-btn>
	</v-card-actions>
  </v-card>
</v-dialog>