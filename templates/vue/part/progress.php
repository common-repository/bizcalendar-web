<v-container fluid>
<v-row>
	<v-col>
		<v-subheader :class="{'primary--text' : step==1}">{{ texts.txt_step_1 }}</v-subheader>
		<v-progress-linear
		  :buffer-value="step==1?100:0"
		  :value="step>1?100:0"
		  stream
		  color="primary"
		  v-bind="props('progress-linear')"
		></v-progress-linear>
	</v-col>
	<v-col>
		<v-subheader :class="{'primary--text' : step==2}">{{ texts.txt_step_2 }}</v-subheader>
		<v-progress-linear
		  :buffer-value="step==2?100:0"
		  :value="step>2?100:0"
		  stream
		  :color="show_dialog_submit_fail_button ? 'warning' : (step > 2 && !availability_value ? 'error' : 'primary')"
		  v-bind="props('progress-linear')"
		></v-progress-linear>
	</v-col>
	<v-col>
		<v-subheader :class="{'primary--text' : step==3}">{{ texts.txt_step_3 }}</v-subheader>
		<v-progress-linear
		  :buffer-value="step==3?100:0"
		  :value="step>3?100:0"
		  stream
		  color="primary"
		  v-bind="props('progress-linear')"
		></v-progress-linear>
	</v-col>
	<v-col>
		<v-subheader :class="{'primary--text' : step==4}">{{ texts.txt_step_4 }}</v-subheader>
		<v-progress-linear
		  :buffer-value="step==4?100:0"
		  :value="step>4?100:0"
		  stream
		  color="primary"
		  v-bind="props('progress-linear')"
		></v-progress-linear>
	</v-col>
</v-row>
</v-container>