<?php 
if(!empty($args['args']['type']) && $args['args']['type'] == 'input'){
	$args['args']['model_suffix'] = '_value';
	setrio_bizcal_get_template_part('vue/part/input', $args);
	return;
}
$attach = isset($args['rargs']) && isset($args['rargs']['attach']) ? $args['rargs']['attach'] : false;
$a = shortcode_atts(array_replace(array(
	'label' => setrio_bizcal_message('txtUnknown'),
	'text_no_items' => setrio_bizcal_message('txtNoItems'),
	'atts' => 'auto-select-first',
	'class' => 'mb-3',
	'locale' => 'ro',
	'hide-details' => "'auto'",
	'placeholder' => '',
	'type' => 'autocomplete',
	'texts' => array(
		'required' => setrio_bizcal_message('lblFieldMissing'),
	),
	'rules' => "",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
$key = $args['key'];

?>
<v-autocomplete 
	v-bind="props('autocomplete')"
	<?php if($attach){ ?>
	attach="<?php echo esc_attr($attach); ?>"
	<?php } ?>
	<?php if($key == 'physician' || $key == 'location'){ ?>
	:clearable="!!<?php echo esc_attr($key); ?>_value"
	<?php } ?>
	<?php echo esc_attr($a['atts']); ?>
	locale="<?php echo esc_attr($a['locale']); ?>" 
	label="<?php echo esc_attr($a['label']); ?>"
	class="<?php echo esc_attr($a['class']); ?>"
	:class="{'sbc-<?php echo esc_attr($key); ?>': true,'d-none': <?php 
	if($key == 'payment_type'){ ?>(!speciality_value || (payment_type_list.length == 1 && payment_type_value))<?php 
	} elseif($key == 'physician'){ ?>!$data.allow_search_physician || !speciality_value || !payment_type_value<?php 
	} elseif($key == 'service'){ ?>1 != payment_type_value<?php 
	} elseif($key == 'location'){ ?>!speciality_value <?php 
	} else { ?>false<?php } 
	?>}" 
	:hide-details="<?php echo esc_attr($a['hide-details']); ?>"
	:loading="<?php echo esc_attr($key); ?>_lock"
	:disabled="<?php echo esc_attr($key); ?>_lock"
	ref="<?php echo esc_attr($key); ?>" 
	v-model="<?php echo esc_attr($key); ?>_value" 
	:items="<?php echo esc_attr($key); ?>_list||[]" 
	:item-text="'label'" 
	:item-value="'value'" 
	<?php if ($a['placeholder']) { ?>placeholder="<?php echo esc_attr($a['placeholder']); ?>"<?php } ?>
	<?php if ($a['rules']) { ?>:rules="<?php echo esc_attr($a['rules']); ?>"<?php } ?>
>
	
	<template v-slot:append v-if="'error' === objVal($refs,'<?php echo esc_attr($key); ?>.validationState')">
		<v-icon
			color="error"
			v-text="'mdi-alert-circle-outline'"
		></v-icon>
	</template>
	<template v-slot:no-data>
		<v-list-item>
			<template v-if="0"></template>
			<?php if(in_array($key, array('location','payment_type','service', 'physician'))){ ?>
			<v-list-item-title v-else-if="!speciality_value"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblFieldMissing')); ?> {{ texts.txt_speciality }}</v-list-item-title>
			<?php } ?>
			<?php if($key == 'service'){ ?>
			<v-list-item-title v-else-if="!payment_type_value"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblFieldMissing')); ?> {{ texts.txt_payment_type }}</v-list-item-title>
			<?php } ?>
			<?php if($key == 'physician'){ ?>
			<v-list-item-title v-else-if="!payment_type_value"><?php BizCalendar\wp_kses_post(setrio_bizcal_message('lblFieldMissing')); ?> {{ texts.txt_payment_type }}</v-list-item-title>
			<?php } ?>
			<v-list-item-title v-else><?php BizCalendar\wp_kses_post(esc_attr($a['text_no_items'])); ?></v-list-item-title>
		</v-list-item>
	</template>
	<?php if($key == 'service'){ ?>
	<template v-slot:item="data">
	  <v-list-item-content v-if="data.item">
		<v-list-item-title class="text-wrap">{{ data.item.label }} <span class="float-right">{{ data.item.Price }}</span></v-list-item-title>
	  </v-list-item-content>
	</template>
	<?php } ?>
	<?php if($key == 'physician' && !empty($args['rargs']['show_physician_details'])){ ?>
	<template v-slot:item="data">
		<template v-if="data.item">
		  <v-list-item-avatar v-if="data.item.PictureURL">
			<v-img cover :lazy-src="'<?php echo esc_attr(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'" :src="data.item.PictureURL">
		  </v-list-item-avatar>
		  <v-list-item-avatar v-else>
			<v-img cover :src="'<?php echo esc_attr(plugins_url( '/css/images/physician-icon.png', BIZCALENDAR_PLUGIN_FILE )); ?>'">
		  </v-list-item-avatar>
		  <v-list-item-content>
			<v-list-item-title v-html="data.item.label"></v-list-item-title>
			<v-list-item-subtitle v-if="data.item.Description" v-html="data.item.Description" class="text-wrap"></v-list-item-subtitle>
		  </v-list-item-content>
		</template>
	</template>
	<?php } ?>
</v-autocomplete>
<?php if($key == 'service'){ ?>
	<div class="font-weight-bold text-h6 mb-3 text-center" v-if="$refs.<?php echo esc_attr($key); ?> && objVal($refs,'<?php echo esc_attr($key); ?>.selectedItems.0')">
        {{ $refs.<?php echo esc_attr($key); ?>.selectedItems[0].Price }}
	</div>
<?php } ?>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php echo esc_attr($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>