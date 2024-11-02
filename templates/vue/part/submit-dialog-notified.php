<v-dialog
  v-model="show_dialog_submit_notified.open"
  max-width="800"
  scrollable
>
  <v-card v-bind="props('card')">
	<v-card-title v-bind="props('card-title')" class="text-h5"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('msgNotifiedSubmitTitle')); ?></v-card-title>
	<v-card-text v-bind="props('card-text')" v-html="show_dialog_submit_notified.data.message"></v-card-text>
	<v-card-actions v-bind="props('card-actions')">
		<v-spacer></v-spacer>
	  <v-btn
		v-bind="props('button-secondary')"
		color="secondary"
		v-on:click="show_dialog_submit_notified = false"
	  ><?php BizCalendar\wp_kses_post(setrio_bizcal_message('btnGotIt'));  ?></v-btn>
	</v-card-actions>
  </v-card>
</v-dialog>