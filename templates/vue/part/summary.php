<div v-if="Physician" class="d-flex flex-no-wrap justify-space-around align-center">
	<v-avatar size="80px">
		<v-img v-if="Physician.PictureURL" cover :lazy-src="'<?php echo esc_attr(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'" :src="Physician.PictureURL"></v-img>
		<v-img v-else cover :src="'<?php echo esc_attr(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'"></v-img>
	</v-avatar>
	<div class="pl-3">
		<div>
			<span><?php echo esc_attr(setrio_bizcal_message('lblSelectedPhysician')) ?></span> <strong>{{  objVal(Physician,'label') }}</strong>
		</div>
		<div v-if="speciality_value && objVal($refs,'speciality.selectedItems.0.label')" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblMedicalSpeciality')) ?></span> <strong>{{  objVal($refs,'speciality.selectedItems.0.label') }}</strong>
		</div>
		<div v-if="Location" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblLocation')) ?></span> <strong>{{  Location.label }}</strong>
		</div>
		<div v-if="service_value && objVal($refs,'service.selectedItems.0.label')" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblSelectedService')) ?></span> <strong>{{  objVal($refs,'service.selectedItems.0.label') }}</strong>
		</div>
		<div v-if="payment_type_value && objVal($refs,'payment_type.selectedItems.0.label')" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblPaymentType')) ?></span> <strong>{{  objVal($refs,'payment_type.selectedItems.0.label') }}</strong>
		</div>
		<div v-if="Physician.Price" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblSelectedServicePrice')) ?></span> <strong>{{  Physician.Price }}</strong>
		</div>
		<div v-if="availability" >
			<span><?php echo esc_attr(setrio_bizcal_message('lblSelectedDate')) ?></span>
			<template v-if="availability.start_date === availability.end_date">
				<strong>{{ localeDateTime(availability.start_date) }}</strong> <strong>{{ availability.start_time }}</strong> - <strong>{{ availability.end_time }}</strong>
			</template>
			<template v-else>
				<strong>{{ localeDateTime(availability.start_datetime) }}</strong> - <strong>{{ localeDateTime(availability.end_datetime) }}</strong>
			</template>
		</div>
	</div>
</div>