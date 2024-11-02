<v-expansion-panels v-bind="props('expansion-panels')" v-if="!availability_lock && availability_list && availability_list.length" v-model="expanded_panel">
	<v-expansion-panel
	  v-for="(availability_list_item, availability_list_item_index) in availability_list"
	  :key="availability_list_item_index"
	  v-bind="props('expansion-panel')"
	>
	  <v-expansion-panel-header :hide-actions="expanded_panel == availability_list_item_index" v-bind="props('expansion-panel-header')">
		<v-row
		  align="center"
		  justify="start"
		>
		  <v-col
			cols="12"
			sm="9"
			class="d-flex align-center justify-start"
		  >
			<?php if (!empty($args['rargs']['show_physician_details'])){ ?>
			<v-avatar size="80px">
				<v-img v-if="availability_list_item.PictureURL" cover :lazy-src="'<?php BizCalendar\wp_kses_post(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'" :src="availability_list_item.PictureURL"></v-img>
				<v-img v-else cover :src="'<?php BizCalendar\wp_kses_post(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'"></v-img>
			</v-avatar>
			<span class="pl-5">{{ availability_list_item.label }}</span>
			<?php } else { ?>
			<span>{{ availability_list_item.label }}</span>
			<?php }?>
		  </v-col>

		  <v-col
			cols="3"
			v-if="availability_list_item.Price"
			class="hidden-xs-only justify-self-end text-right"
			class="text-no-wrap"
		  >{{ availability_list_item.Price }}</v-col>
		</v-row>
	  </v-expansion-panel-header>
	  <v-expansion-panel-content v-bind="props('expansion-panel-content')">
		<v-card-text v-bind="props('card-text')">
		<v-row
		  align="center"
		>
			<v-col
				cols="12"
				class="text-wrap"
			>
				<v-alert
					type="warning"
					v-if="date_value != availability_list_item.availabilities[0].start_date"
					v-bind="props('alert-warning')"
				>
					<div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('txtWarnNoAvailableAppointments')); ?></div>
					<div class="mb-3"><?php BizCalendar\wp_kses_post(setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('txtWarnShowingAvailableAppointments'), array('data' => '{{ localeDateTime(availability_list_item.availabilities[0].start_date) }}'))); ?></div>
				</v-alert>
				<template v-if="$data.enable_multiple_locations && !location_value && availability_list_item.day_locations">
					<v-card v-bind="props('card')" v-for="(loc_uid, loc_index) in availability_list_item.day_locations" :class="{'mt-3': !!loc_index}":key="[availability_list_item_index,loc_uid].join(',')">
						<v-card-subtitle v-bind="props('card-title')">{{ location_list.find(x=>x.UID == loc_uid).label }}</v-card-subtitle>
						<v-card-text>
							<v-chip-group
								v-bind="props('chip-group')"
								v-model="availability_value"
							  >
								<div class="d-flex pa-0 ma-0 justify-start flex-wrap">
									<v-chip
									  v-bind="props('chip')"
									  v-for="(date, date_index) in availability_list_item.day_availabilities.filter(function(x){ return x.LocationUID == loc_uid }) || []"
									  :key="date_index"
									  :value="date.value"
									  v-if="!$data['max_availabilities'] || date_index < $data['max_availabilities']"
									>{{ date.start_time }} - {{ date.end_time }}</v-chip>
								</div>
							</v-chip-group>
						</v-card-text>
					</v-card>
				</template>
				<template v-else>
					<v-chip-group
						v-bind="props('chip-group')"
						v-model="availability_value"
					  >
						<div class="d-flex pa-0 ma-0 justify-start flex-wrap">
							<v-chip
							  v-bind="props('chip')"
							  v-for="(date, date_index) in availability_list_item.day_availabilities || []"
							  :key="date_index"
							  :value="date.value"
							  v-if="!$data['max_availabilities'] || date_index < $data['max_availabilities']"
							>{{ date.start_time }} - {{ date.end_time }}</v-chip>
						</div>
					</v-chip-group>
				</template>
			</v-col>
			<?php if (!empty($args['rargs']['show_physician_details'])){ ?>
			<v-col
				cols="12"
				class="text-wrap subtitle-2"
				v-if="availability_list_item.Description"
			>{{ availability_list_item.Description }}</v-col>
			<?php } ?>
		</v-row>
	  </v-card-text>
	  </v-expansion-panel-content>
	</v-expansion-panel>
</v-expansion-panels>