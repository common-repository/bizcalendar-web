jQuery(function($) {
	$('.setrio-bizcal-post-selector').select2( {
									placeholder: 'Select a post',
									multiple: true,
									minimumInputLength: 3,
									ajax: {
										url: ajaxurl,
										dataType: 'json',
										data: function (term, page) {
											return {
												q: term,
												action: 'ssetrio_bizcal_post_select_lookup',
												post_type: $(this).attr('data-post-type'),
												s2ps_post_select_field_id: $(this).attr('data-setrio-bizcal-post-select-field-id')
											};
										},
										results: setrioBizcalProcessPostSelectDataForSelect2
									},
									initSelection: function(element, callback) {
										// the input tag has a value attribute preloaded that points to a preselected movie's id
										// this function resolves that id attribute to an object that select2 can render
										// using its formatResult renderer - that way the movie name is shown preselected
										var ids=$(element).val();
										if (ids!=="") {
											$.ajax(ajaxurl, {
												data: {
													action: 'setrio_bizcal_get_post_titles',
													post_ids: ids
												},
												dataType: "json"
											}).done(function(data) {
												var processedData = setrioBizcalProcessPostSelectDataForSelect2(data);
												callback(processedData.results); });
										}
									},
								});
});

function setrioBizcalProcessPostSelectDataForSelect2( ajaxData, page, query ) {

	var items=[];
	var newItem=null;

	for (var thisId in ajaxData) {
		newItem = {
			'id': ajaxData[thisId]['id'],
			'text': ajaxData[thisId]['title']
		};
		items.push(newItem);
	}
	return { results: items };
}