Object.nc_object_size = function(obj) {
	var size = 0, key;
	for (key in obj) {
		if (obj.hasOwnProperty(key)) size++;
	}
	return size;
};

!function($) {

	var settings = $('#nc-filter-manager-json').val();

	if (typeof settings==='undefined'||settings!='') {

		var filterSettings = $.parseJSON(settings);
		var wrap = $('#nc-composer-filters-wrap');

		var counter = Object.nc_object_size(filterSettings);

		for (var key in filterSettings) {

			if (!filterSettings.hasOwnProperty(key)) continue;

			var defaults = $('#nc-composer-filters-default').html();
			var html = $('<div class="nc-composer-filter">'+defaults+'</div>');

			wrap.append(html);

			if (!--counter) {

				for (var setKey in filterSettings) {

					if (!filterSettings.hasOwnProperty(setKey)) continue;

					wrap.append(html);
					var filter = wrap.find('.nc-composer-filter:eq('+setKey+')');

					filter.find('.nc-filter-settings-collect').hide();

					var obj = filterSettings[setKey];

					if (obj['type']=='meta') {

						filter.find('.nc-filter-settings-collect.type_meta').show();
						filter.find('.nc-type').show().find('option[value="'+obj['type']+'"]').prop('selected',true).attr('selected',true);
						filter.find('.nc-filter-settings-collect[data-param="meta_key"]').val(obj['meta_key']).attr('value',obj['meta_key']);
						filter.find('.nc-filter-settings-collect[data-param="meta_value"]').val(obj['meta_value']).attr('value',obj['meta_value']);
						filter.find('.nc-filter-settings-collect[data-param="meta_compare"]').show().find('option[value="'+obj['meta_compare']+'"]').prop('selected',true).attr('selected',true);
						filter.find('.nc-filter-settings-collect[data-param="meta_type"]').show().find('option[value="'+obj['meta_type']+'"]').prop('selected',true).attr('selected',true);

					}
					else if (obj['type']=='taxonomy') {

						filter.find('.nc-type').show().find('option[value="'+obj['type']+'"]').prop('selected',true).attr('selected',true);

						var postType = $('select.post_type.dropdown').val();

						filter.find('.nc-taxonomy[data-param="post_type_'+postType+'"]').show().find('option[value="'+obj['taxonomy']+'"]').prop('selected',true).attr('selected',true);
						filter.find('.nc-taxonomy-terms[data-param="taxonomy_'+obj['taxonomy']+'"]').show().find('option[value="'+obj['term']+'"]').prop('selected',true).attr('selected',true);

					}

				}

			}

		}

	}

}(window.jQuery);