String.prototype.nc_escape_chars = function() {
	return this.replace(/\\n/g, "\\n")
		.replace(/\\'/g, "\\'")
		.replace(/\\"/g, '\\"')
		.replace(/\\&/g, "\\&")
		.replace(/\\r/g, "\\r")
		.replace(/\\t/g, "\\t")
		.replace(/\\b/g, "\\b")
		.replace(/\\f/g, "\\f");
};

!function($) {

	function nc_do_filters_update() {

		var filters = $('#nc-composer-filters-wrap');

		var filterSettings = {};

		var paramFilters = '';
		var paramFilterTerms = '';

		var paramMetaKeys = '';
		var paramMetaValues = '';
		var paramMetaCompares = '';
		var paramMetaTypes = '';

		var counter = filters.find('.nc-composer-filter').length;

		var im = 0;
		var it = 0;

		filters.find('.nc-composer-filter').each(function(i, element) {

			var el = $(this);
			var hasError = false;

			filterSettings[i] = {};

			filterSettings[i]['type'] = el.find('.nc-type').val();

			if (filterSettings[i]['type']=='meta') {
				filterSettings[i]['meta_key'] = el.find('.type_meta[data-param="meta_key"]').val();
				if (!filterSettings[i]['meta_key']){
					hasError = true;
				}
				else {
					paramMetaKeys += (im>0?'|'+filterSettings[i]['meta_key']:filterSettings[i]['meta_key']);
				}
				filterSettings[i]['meta_value'] = el.find('.type_meta[data-param="meta_value"]').val();
				if (!filterSettings[i]['meta_value']){
					hasError = true;
				}
				else {
					paramMetaValues += (im>0?'|'+filterSettings[i]['meta_value']:filterSettings[i]['meta_value']);
				}
				filterSettings[i]['meta_compare'] = el.find('.type_meta[data-param="meta_compare"]').val();
				if (!filterSettings[i]['meta_compare']){
					hasError = true;
				}
				else {
					paramMetaCompares += (im>0?'|'+filterSettings[i]['meta_compare']:filterSettings[i]['meta_compare']);
				}
				filterSettings[i]['meta_type'] = el.find('.type_meta[data-param="meta_type"]').val();
				if (!filterSettings[i]['meta_type']){
					hasError = true;
				}
				else {
					paramMetaTypes += (im>0?'|'+filterSettings[i]['meta_type']:filterSettings[i]['meta_type']);
				}
				im++;
			}
			else if (filterSettings[i]['type']=='taxonomy'){
				var postType = $('select.post_type.dropdown').val();

				filterSettings[i]['taxonomy'] = el.find('.nc-taxonomy[data-param="post_type_'+postType+'"]').val();
				if (!filterSettings[i]['taxonomy']){
					hasError = true;
				}
				else {
					paramFilters += (it>0?'|'+filterSettings[i]['taxonomy']:filterSettings[i]['taxonomy']);
				}
				filterSettings[i]['term'] = el.find('.nc-taxonomy-terms[data-param="taxonomy_'+filterSettings[i]['taxonomy']+'"]').val();
				if (!filterSettings[i]['term']){
					hasError = true;
				}
				else {
					paramFilterTerms += (it>0?'|'+filterSettings[i]['term']:filterSettings[i]['term']);
				}
				it++;
			}

			if (!--counter) {

				if ( hasError === true ) {
					alert('Empty values are not allowed!');
				}
				else {
					var jsonSettings = JSON.stringify(filterSettings).nc_escape_chars();
					$('#nc-filter-manager-json').val(jsonSettings).attr('value',jsonSettings);

					if ( paramMetaKeys ) {
						$('input.meta_keys.textfield').val(paramMetaKeys).attr('value',paramMetaKeys);
					}
					else {
						$('input.meta_keys.textfield').val('').attr('value','');
					}
					if ( paramMetaValues ) {
						$('input.meta_values.textfield').val(paramMetaValues).attr('value',paramMetaValues);
					}
					else {
						$('input.meta_values.textfield').val('').attr('value','');
					}
					if ( paramMetaCompares ) {
						$('input.meta_compares.textfield').val(paramMetaCompares).attr('value',paramMetaCompares);
					}
					else {
						$('input.meta_compares.textfield').val('').attr('value','');
					}
					if ( paramMetaTypes ) {
						$('input.meta_types.textfield').val(paramMetaTypes).attr('value',paramMetaTypes);
					}
					else {
						$('input.meta_types.textfield').val('').attr('value','');
					}

					if ( paramFilters ) {
						$('input.filters.textfield').val(paramFilters).attr('value',paramFilters);
					}
					else {
						$('input.filters.textfield').val('').attr('value','');
					}
					if ( paramFilterTerms ) {
						$('input.filter_terms.textfield').val(paramFilterTerms).attr('value',paramFilterTerms);
					}
					else {
						$('input.filter_terms.textfield').val('').attr('value','');
					}
					alert('Filters updated!');
				}

			}

		});

	}

	$(document).on('click', '#nc-add-filter', function() {

		var filterWrap = $('#nc-composer-filters-wrap');
		var defaults = $('#nc-composer-filters-default').html();

		var html = $('<div class="nc-composer-filter">'+defaults+'</div>');

		filterWrap.append(html);

		var filter = filterWrap.find('.nc-composer-filter:last');

		filter.find('.nc-filter-settings-collect').hide();

		filter.find('.nc-type').show().find('option:first').prop('selected',true).attr('selected',true);
		filter.find('[data-param="post_type"]').show();

		var value = $('select.post_type.dropdown').val();

		filter.find('.nc-taxonomy[data-param="post_type_'+value+'"]').show();

	});

	$(document).on('click', '#nc-remove-filter', function() {
		$(this).closest('.nc-composer-filter').remove();
	});

	$(document).on('click', '#nc-update-filters', function() {
		var activeFilters = $('#nc-composer-filters-wrap .nc-composer-filter').length;

		if ( activeFilters == 0 ) {
			$('#nc-filter-manager-json').val('').attr('value','');
			$('input.meta_keys.textfield').val('').attr('value','');
			$('input.meta_values.textfield').val('').attr('value','');
			$('input.meta_compares.textfield').val('').attr('value','');
			$('input.meta_types.textfield').val('').attr('value','');
			$('input.filters.textfield').val('').attr('value','');
			$('input.filter_terms.textfield').val('').attr('value','');
			alert('Filters updated!');
		}

		nc_do_filters_update();

	});

	$(document).on('change', '.nc-type', function() {

		var filter = $(this).closest('.nc-composer-filter');

		filter.find('.nc-filter-settings-collect:not(.nc-type)').hide();
		filter.find('select:not(.nc-type),input').val();
		filter.find('input').attr('value', '');
		filter.find('select:not(.nc-type) option').prop('selected', false);

		var type = filter.find('.nc-type').val();

		if (type=='meta') {
			filter.find('.type_meta').show();
		}
		else if (type='taxonomy') {
			var value = $('select.post_type.dropdown').val();
			filter.find('.nc-type').show();
			filter.find('.nc-taxonomy[data-param="post_type_'+value+'"]').show();
		}

	});

/*	$(document).on('change', '.nc-parent-post-type', function() {

		var filter = $(this).closest('.nc-composer-filter');

		var value = $(this).val();;

		filter.find('.nc-taxonomy').hide();

		if (value!=''){
			filter.find('.nc-taxonomy[data-param="post_type_'+value+'"]').show();
		}

		alert('changed1');

	});*/

	$(document).on('change', '.nc-taxonomy', function() {

		var filter = $(this).closest('.nc-composer-filter');

		var value = $(this).val();

		filter.find('.nc-taxonomy-terms').hide();

		if (value!=''){
			filter.find('.nc-taxonomy-terms[data-param="taxonomy_'+value+'"]').show();
		}

	});

}(window.jQuery);