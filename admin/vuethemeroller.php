<?php
$config_file = isset($_GET['config_file']) && filter_var($_GET['config_file'], FILTER_VALIDATE_URL) ? $_GET['config_file'] : null;
$script_file_uri = $_SERVER['SCRIPT_URI'];
$script_dir_uri = dirname($script_file_uri) . '/';
?><!doctype html>
<!--[if IE 7 ]>		 <html class="no-js ie ie7 lte7 lte8 lte9"> lang="en-US"> <![endif]-->
<!--[if IE 8 ]>		 <html class="no-js ie ie8 lte8 lte9"> lang="en-US"> <![endif]-->
<!--[if IE 9 ]>		 <html class="no-js ie ie9 lte9"> lang="en-US"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="en-US">
<!--<![endif]-->

<head data-live-domain="jqueryui.com">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
	<title>ThemeRoller | VUE</title>
	<link rel="stylesheet" href="../vendor/materialdesign/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="../vendor/vuetify/vuetify.css?v=1.0.0">
	<link rel="stylesheet" href="../css/bizcalendar-vue.css?v=1.0.0">
	<script src="../vendor/vue/vue-2.6.12.js"></script>
	<script src="../vendor/vue/vuex-3.6.0.js"></script>
	<script src="../vendor/vuetify/vuetify.js?v=1.0.0"></script>
	<script src="//code.jquery.com/jquery-1.11.3.js"></script>
	<script>
	var config_file = <?php echo json_encode($config_file); ?>;
	</script>
	<script src="./vuethemeroller.js?v=1.0.0"></script>

	<style>
		.bizcal-vue-v-app {
			width: 100%;
		}
		
		.v-color-picker__canvas>canvas {
			width: 100%;
		}
		
		.setrio.setrio-bizcal.setrio-bizcal-related.bizcal-vue .bizcal-vue-app .v-color-picker__color {
			width: 25px;
		}
	</style>
</head>

<body class="setrio-bizcal-related">

	<div id="container" class="">
		<div id="content-wrapper" class="clearfix row">
			<div class="content-full full-width twelve columns">
				<div id="content">
					<div class="setrio setrio-bizcal setrio-bizcal-related bizcal-vue">
						<div class="bizcal-vue-app">
							<v-app id="demo-app" class="bizcal-vue-v-app bizcal-force" v-bind="props('app')">
								<template>
							<v-container fluid class="pa-0" v-bind="props('container')">
								<v-row>
									<v-col cols="12">
									<v-toolbar dense>
									  <v-toolbar-title>Personalizare tema vue</v-toolbar-title>
									  <v-spacer></v-spacer>
									  <v-btn @click.stop="save" color="warning">
										Salveaza tema
										<v-icon>mdi-content-save</v-icon>
									  </v-btn>
									</v-toolbar>
									</v-col>
									<v-col cols="12" md="2" lg="1" class="d-md-flex flex-column" style="min-width:200px;">
										<v-subheader>Culori principale</v-subheader>
										<template v-for="(color_value, color_name) in theme.themes[theme.isDark ? 'dark' : 'light']">
											<v-btn
												class="mb-3"
												@click.stop="color_dialog = {
													open: true,
													tab: 0,
													type: 'main',
													value: color_value,
													key: color_name,
												}"
												:color="color_name"
												v-bind="props('button')"
											>
												<span v-html="color_name"></span>
												<v-icon right>mdi-pencil</v-icon>
											</v-btn>
										</template>
										<v-subheader>Culori custom</v-subheader>
										<template v-for="(color_value, color_name) in theme.themes.custom">
											<v-btn
												class="mb-3"
												@click.stop="color_dialog = {
													open: true,
													tab: 0,
													type: 'custom',
													value: color_value,
													key: color_name,
												}"
												:color="color_name"
												v-bind="props('button')"
											>
												<span v-html="color_name"></span>
												<v-icon right>mdi-pencil</v-icon>
											</v-btn>
										</template>
										<v-btn class="mb-3" @click.stop="addMainColor">
											<span>Adaugare</span>
											<v-icon right>mdi-plus</v-icon>
										</v-btn>
										<v-subheader>Culori implicite</v-subheader>
										<template v-for="(color_value, color_name) in theme.themes.default">
											<v-btn
												class="mb-3"
												@click.stop="color_dialog = {
													open: true,
													tab: 0,
													type: 'default',
													value: color_value,
													key: color_name,
												}"
												:color="color_name"
												v-bind="props('button')"
											>
												<span v-html="color_name"></span>
												<v-icon right>mdi-pencil</v-icon>
											</v-btn>
										</template>
									</v-col>
									<v-col>
										<v-tabs>
											<v-tab>
												Stil campuri
											</v-tab>
											<v-tab-item>
												<v-tabs vertical>
													<template v-for="(setting, setting_key) in theme.themes.style">
														<template v-if="undefined !== setting.props">
															<v-tab :value="setting_key" v-html="setting_key"></v-tab>
															<v-tab-item :key="setting_key">
																<v-card v-bind="props('card')">
																	<v-card-text v-bind="props('card-text')">
																		<v-subheader>{{ setting_key }}</v-subheader>
																		<template v-for="(prop_value, prop_key) in theme.themes.style['default'].props">
																			<template v-if="-1 !== ['color','header-color','background-color','border-color','item-color'].indexOf(prop_key)">
																				<template v-if="('default' == setting_key) || setting.props.hasOwnProperty(prop_key)">
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="classes"
																						chips
																						small-chips
																						multiple
																						deletable-chips
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																					>
																					<template v-slot:item="{ item }">
														<v-list-item-content>
														  <v-list-item-title v-text="item"></v-list-item-title>
														</v-list-item-content>
														<v-list-item-action>
														  <v-icon :class="item">mdi-check</v-icon>
														</v-list-item-action>
													</template>
																					</v-autocomplete>
																				</template>
																			</template>
																			<template v-else-if="(-1 !== ['clear-icon','append-icon','icon','expand-icon'].indexOf(prop_key))">
																				<template v-if="(-1 === ['default','button'].indexOf(setting_key)) && setting.props.hasOwnProperty(prop_key)">
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="icons"
																						chips
																						small-chips
																						multiple
																						deletable-chips
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																					>
																					<template v-slot:item="{ item }">
														<v-list-item-content>
														  <v-list-item-title v-text="item"></v-list-item-title>
														</v-list-item-content>
														<v-list-item-action>
														  <v-icon v-html="item"></v-icon>
														</v-list-item-action>
													</template>
																					</v-autocomplete>
																				</template>
																			</template>
																			<template v-else-if="(-1 !== ['border'].indexOf(prop_key))">
																				<template v-if="(-1 === ['default','button'].indexOf(setting_key)) && setting.props.hasOwnProperty(prop_key)">
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="['top','right','bottom','left']"
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																					></v-autocomplete>
																				</template>
																			</template>
																			<template v-else-if="(-1 !== ['transition'].indexOf(prop_key))">
																				<template v-if="(-1 === ['default','button'].indexOf(setting_key)) && setting.props.hasOwnProperty(prop_key)">
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="['v-fab-transition', 'v-fade-transition', 'v-expand-transition', 'v-scale-transition', 'v-scroll-x-transition', 'v-scroll-x-reverse-transition', 'v-scroll-y-transition', 'v-scroll-y-reverse-transition', 'v-slide-x-transition', 'v-slide-x-reverse-transition', 'v-slide-y-transition', 'v-slide-y-reverse-transition']"
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																					></v-autocomplete>
																				</template>
																			</template>
																			<template v-else-if="-1 !== ['active-class'].indexOf(prop_key)">
																				<template>
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="classes"
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																						multiple
																						chips
																						small-chips
																						deletable-chips
																					>
																					</v-autocomplete>
																				</template>
																			</template>
																			<template v-else-if="prop_key == 'class'">
																				<template>
																					<v-autocomplete
																						filled
																						clearable
																						v-model="setting.props[prop_key]"
																						:hide-details="'auto'"
																						:items="classes"
																						:label="prop_key"
																						:placeholder="prop_key"
																						class="mb-3"
																						multiple
																						chips
																						small-chips
																						deletable-chips
																					>
																					</v-autocomplete>
																				</template>
																			</template>
																			<template v-else>
																				<template v-if="('default' == setting_key) || setting.props.hasOwnProperty(prop_key)">
																					<span class="d-inline-block mb-3">
																						<strong class="px-5 d-inline-block text-right" style="width:200px" v-html="prop_key"></strong>
																					
																					<v-btn-toggle mandatory v-model="setting.props[prop_key]">
																						<v-btn :value="0">
																						  Default
																						</v-btn>
																						<v-btn :value="2" class="success">
																						  <v-icon>mdi-check</v-icon>
																						</v-btn>
																						<v-btn :value="1" class="error">
																						  <v-icon>mdi-close</v-icon>
																						</v-btn>
																					</v-btn-toggle>
																					</span>
																				</template>
																			</template>
																		</template>
																		
																		<v-text-field v-if="setting.props.hasOwnProperty('font-family')" mandatory v-model="setting.props['font-family']" :hide-details="'auto'" label="Font Family"></v-text-field>
																		
																		<v-text-field v-if="setting.props.hasOwnProperty('height')" mandatory v-model="setting.props['height']" :hide-details="'auto'" label="Inaltime"></v-text-field>
																		
																		<v-text-field mandatory v-model="setting['outline']['size']" :hide-details="'auto'" label="Marime bordura"></v-text-field>
																		<v-text-field mandatory v-model="setting['outline']['style']" :hide-details="'auto'" label="Stil bordura"></v-text-field>
																		<v-slider label="Rotunjire colturi" v-model="setting.outline.radius" :max="demo.rounded.length - 1" :tick-labels="demo.rounded"></v-slider>
																		<v-slider label="Umbra" ticks thumb-label v-model="setting.outline.elevation" :max="25"></v-slider>
																		<div class="text-center">
																			<v-btn v-bind="props('button')" class="mx-auto transition-swing secondary">Buton exemplu</v-btn>
																		</div>
																	</template>
																	</v-card-text>
																</v-card>
															</v-tab-item>
														</template>

												</v-tabs>
											</v-tab-item>
										</v-tabs>
										
										<v-subheader>Vizualizare</v-subheader>
										<v-card v-bind="props('card')">
											<v-card-title v-bind="props('card-title')">
												Excepteur sint occaecat cupidatat
											</v-card-title>
											<v-card-text v-bind="props('card-text')">
												<v-row>
													<v-col>
														<v-date-picker class="mb-5" first-day-of-week="1" locale="ro" v-model="demo.calendar_date" v-bind="props('date-picker')">
														</v-date-picker>
														
<v-expansion-panels v-bind="props('expansion-panels')">
	<v-expansion-panel v-bind="props('expansion-panel')">
	  <v-expansion-panel-header v-bind="props('expansion-panel-header')">
			Lorem ipsum
	  </v-expansion-panel-header>
	  <v-expansion-panel-content v-bind="props('expansion-panel-content')">
		<v-card-text v-bind="props('card-text')">
			Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
		</v-card-text>
	  </v-expansion-panel-content>
	</v-expansion-panel>
	<v-expansion-panel v-bind="props('expansion-panel')">
	  <v-expansion-panel-header v-bind="props('expansion-panel-header')">
			Ut enim ad minim
	  </v-expansion-panel-header>
	  <v-expansion-panel-content v-bind="props('expansion-panel-content')">
		<v-card-text v-bind="props('card-text')">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
		</v-card-text>
	  </v-expansion-panel-content>
	</v-expansion-panel>
</v-expansion-panels>
													</v-col>
													<v-col>
														<v-btn v-bind="props('button')">Excepteur sint</v-btn>
														
														<v-text-field ref="test-text-field" class="mb-5" v-bind="props('text-field')" :hide-details="'auto'" label="Lorem ipsum dolor sit amet"></v-text-field>
														
														<v-text-field class="mb-5" v-bind="props('text-field')" :hide-details="'auto'" label="Lorem ipsum dolor sit amet" :rules="[v => (!!v && !!v.trim().length) || 'Ut enim ad minim veniam' || '']" validate-on-blur></v-text-field>

														<v-textarea class="mb-5" v-bind="props('textarea')" :hide-details="'auto'" label="Duis aute irure dolor"></v-textarea>
														
														<v-textarea class="mb-5" v-bind="props('textarea')" :hide-details="'auto'" label="Duis aute irure dolor" :rules="[v => (!!v && !!v.trim().length) || 'Ut enim ad minim veniam' || '']" validate-on-blur></v-textarea>

														<v-autocomplete class="mb-5" v-bind="props('autocomplete')" :hide-details="'auto'" :items="demo.autocomplete_items" :item-text="'label'" :item-value="'value'" clearable label="Lorem ipsum dolor sit amet" placeholder="Lorem ipsum dolor sit amet"></v-autocomplete>
														
														<v-autocomplete class="mb-5" v-bind="props('autocomplete')" :hide-details="'auto'" :items="demo.autocomplete_items" :item-text="'label'" :item-value="'value'" clearable label="Lorem ipsum dolor sit amet" placeholder="Lorem ipsum dolor sit amet" :rules="[v => (!!v && !!v.trim().length) || 'Ut enim ad minim veniam' || '']" validate-on-blur></v-autocomplete>

														<v-checkbox class="mb-5" v-bind="props('checkbox')" v-model="demo.checkbox">
															<template v-slot:label>
																<span class="subtitle-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
															</template>
														</v-checkbox>
													</v-col>
												</v-row>
											</v-card-text>
											<v-card-actions v-bind="props('card-actions')">
												<v-btn ref="test-btn" color="disabled">
													Disabled
												</v-btn>
												<v-spacer></v-spacer>
												<v-btn color="secondary">
													Secondary
												</v-btn>
												<v-spacer></v-spacer>
												<v-btn color="primary">
													Primary
												</v-btn>
												<v-spacer></v-spacer>
												<v-btn color="error">
													Error
												</v-btn>
											</v-card-actions>
										</v-card>
										
										<v-chip-group
											v-bind="props('chip-group')"
											v-model="demo.chip"
										  >
											<v-chip v-bind="props('chip')"
											  v-for="tag in [1,2,3,4,5,6,7]"
											  :key="tag"
											>
											  0{{ tag }}:00
											</v-chip>
									  </v-chip-group>
										
										<v-container style="min-height: 150px;" >
										  <v-row
											class="fill-height"
											align-content="center"
											justify="center"
										  >
											<v-col
											  class="text-subtitle-1 text-center"
											  cols="12"
											>Loading</v-col>
											<v-col cols="6">
											  <v-progress-linear
												indeterminate
												v-bind="props('progress-linear')"
											  ></v-progress-linear>
										  </v-row>
										</v-container>
										
										<v-container style="min-height: 150px;" >
										  <v-row
											class="fill-height"
											align-content="center"
											justify="center"
										  >
											<v-col cols="12">
											 <v-alert type="warning" v-bind="props('alert-warning')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</v-alert>
											 <v-alert type="success" v-bind="props('alert-success')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</v-alert>
											 <v-alert type="error" v-bind="props('alert-error')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</v-alert>
											 <v-alert type="info" v-bind="props('alert-info')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</v-alert>
											</v-col>
										  </v-row>
										</v-container>
									</v-col>
								</v-row>
								</v-container>
								</template>
								<template>
								<v-dialog
									attach="#demo-app"
									v-model="color_dialog.open"
									ref="color_dialog"
									scrollable
									width="900"
									>
									<v-card v-bind="props('card')">
									<v-card-title v-bind="props('card-title')" v-html="color_dialog.key"></v-card-title>
									<v-card-text v-bind="props('card-text')">
									<v-btn v-if="color_dialog.type == 'custom'" class="mb-3" @click.stop="addVariantColor(color_dialog.key)">
										<span>Adaugare</span>
										<v-icon right>mdi-plus</v-icon>
									</v-btn>
									<v-tabs vertical>
										<template v-for="(variation_value, variation_key) in $vuetify.theme.parsedTheme[color_dialog.key]">
											<v-tab :value="variation_key" :class="color_dialog.key + ' ' + variationClass(variation_key)" v-html="variation_key"></v-tab>
											<v-tab-item :key="variation_key">
												<v-subheader v-html="variation_key"></v-subheader>
												<v-card-text v-bind="props('card-text')">
												<template v-if="color_dialog.type == 'main'">
													<v-autocomplete
														v-bind="props('autocomplete')"
														clearable
														v-model="theme.themes.background[((color_dialog.type == 'custom' || color_dialog.type == 'default') ? color_dialog.type : (theme.isDark ? 'dark' : 'light'))][color_dialog.key][variation_key]"
														:hide-details="'auto'"
														:items="colors"
														label="Culoare background"
														placeholder="Culoare background"
													>
														<template v-slot:item="{ item }">
															<v-list-item-content>
															  <v-list-item-title v-text="item"></v-list-item-title>
															</v-list-item-content>
															<v-list-item-action>
															  <v-icon :class="item">mdi-check</v-icon>
															</v-list-item-action>
														</template>
													</v-autocomplete>
												</template>
												<template v-if="color_dialog.type == 'main'">
													<v-autocomplete
														v-bind="props('autocomplete')"
														clearable
														v-model="theme.themes['text-background'][((color_dialog.type == 'custom' || color_dialog.type == 'default') ? color_dialog.type : (theme.isDark ? 'dark' : 'light'))][color_dialog.key][variation_key]"
														:hide-details="'auto'"
														:items="colors"
														label="Culoare background text"
														placeholder="Culoare background text"
													>
														<template v-slot:item="{ item }">
															<v-list-item-content>
															  <v-list-item-title v-text="item"></v-list-item-title>
															</v-list-item-content>
															<v-list-item-action>
															  <v-icon :class="item">mdi-check</v-icon>
															</v-list-item-action>
														</template>
													</v-autocomplete>
												</template>
												<v-autocomplete
													v-bind="props('autocomplete')"
													clearable
													v-model="theme.themes.text[((color_dialog.type == 'custom' || color_dialog.type == 'default') ? color_dialog.type : (theme.isDark ? 'dark' : 'light'))][color_dialog.key][variation_key]"
													:hide-details="'auto'"
													:items="colors"
													label="Culoare text"
													placeholder="Culoare text"
												>
													<template v-slot:item="{ item }">
														<v-list-item-content>
														  <v-list-item-title v-text="item"></v-list-item-title>
														</v-list-item-content>
														<v-list-item-action>
														  <v-icon :class="item">mdi-check</v-icon>
														</v-list-item-action>
													</template>
												</v-autocomplete>
												<v-autocomplete
													v-bind="props('autocomplete')"
													clearable
													v-model="theme.themes.border[((color_dialog.type == 'custom' || color_dialog.type == 'default') ? color_dialog.type : (theme.isDark ? 'dark' : 'light'))][color_dialog.key][variation_key]"
													:hide-details="'auto'"
													:items="colors"
													label="Culoare border"
													placeholder="Culoare border"
												>
													<template v-slot:item="{ item }">
														<v-list-item-content>
														  <v-list-item-title v-text="item"></v-list-item-title>
														</v-list-item-content>
														<v-list-item-action>
														  <v-icon :class="item">mdi-check</v-icon>
														</v-list-item-action>
													</template>
												</v-autocomplete>
												
												
												<template v-if="color_dialog.type !== 'main'">
													<v-color-picker
														width="300"
														canvas-height="100"
														v-model="theme.themes[((color_dialog.type == 'custom' || color_dialog.type == 'default') ? color_dialog.type : (theme.isDark ? 'dark' : 'light'))][color_dialog.key][variation_key]"
														mode="rgba"
														class="ma-2 mx-auto"
														show-swatches
														swatches-max-height="200px"
													></v-color-picker>
												</template>
												
												</v-card-text>
											</v-tab-item>
										</template>
									</v-tabs>
									</template>
								</v-card-text>
								</v-card>
								</v-dialog>
								</template>
							</v-app>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</body>
<script>
</script>

</html>