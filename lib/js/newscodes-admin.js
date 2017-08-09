(function($){
	"use strict";

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

	$.fn.selectWithText = function selectWithText(targetText) {
		return this.each(function () {
			var $selectElement, $options, $targetOption;

			$selectElement = $(this);
			$options = $selectElement.find('option');
			$targetOption = $options.filter(
				function () {return $(this).text() == targetText}
			);

			if ($targetOption) {
				return $targetOption;
			}
		});
	}


	var nc_loading = 'notactive';

	function nc_ajax( settings ) {

		var data = {
			action: 'nc_admin_ajax_factory',
			nc_settings: settings
		}

		return $.post(nc.ajax, data, function(response) {
			if (response) {
				nc_loading = 'notactive';
			}
			else {
				alert('Error!');
				nc_loading = 'notactive';
			}

		});

	}

	function nc_get_control( control, type ) {

		if ( type == 'typography' ) {

			return {
				'font-color' : $('[name="'+control+'[font-color]"]').val(),
				'font-family' : $('[name="'+control+'[font-family]"]').val(),
				'font-size' : $('[name="'+control+'[font-size]"]').val(),
				'font-style' : $('[name="'+control+'[font-style]"]').val(),
				'font-variant' : $('[name="'+control+'[font-variant]"]').val(),
				'font-weight' : $('[name="'+control+'[font-weight]"]').val(),
				'letter-spacing' : $('[name="'+control+'[letter-spacing]"]').val(),
				'line-height' : $('[name="'+control+'[line-height]"]').val(),
				'text-decoration' : $('[name="'+control+'[text-decoration]"]').val(),
				'text-transform' : $('[name="'+control+'[text-transform]"]').val(),
				'text-align' : $('[name="'+control+'[text-align]"]').val()
			};

		}

	}

	function nc_get_style( styleName ) {
		return {
			'name' : styleName,
			'nc_heading': nc_get_control( 'nc_heading', 'typography'),
			'nc_heading_hover': $('#nc_heading_hover').val(),
			'nc_meta': nc_get_control( 'nc_meta', 'typography'),
			'nc_meta_background': $('#nc_meta_background').val(),
			'nc_excerpt': nc_get_control( 'nc_excerpt', 'typography'),
			'nc_taxonomy_color': $('#nc_taxonomy_color').val(),
			'nc_taxonomy_background': $('#nc_taxonomy_background').val(),
			'nc_navigation': nc_get_control( 'nc_navigation', 'typography'),
			'nc_navigation_hover': $('#nc_navigation_hover').val(),
			'nc_navigation_style': $('#nc_navigation_style').val(),
			'nc_tabs': nc_get_control( 'nc_tabs', 'typography'),
			'nc_tabs_hover': $('#nc_tabs_hover').val(),
			'nc_tabs_style': $('#nc_tabs_style').val(),
			'nc_format_standard': $('#nc_format_standard').val(),
			'nc_format_aside': $('#nc_format_aside').val(),
			'nc_format_chat': $('#nc_format_chat').val(),
			'nc_format_gallery': $('#nc_format_gallery').val(),
			'nc_format_link': $('#nc_format_link').val(),
			'nc_format_image': $('#nc_format_image').val(),
			'nc_format_quote': $('#nc_format_quote').val(),
			'nc_format_status': $('#nc_format_status').val(),
			'nc_format_video': $('#nc_format_video').val(),
			'nc_format_audio': $('#nc_format_audio').val(),
			'nc_tabs_padding': $('#nc_tabs_padding').val(),
			'nc_image_padding': $('#nc_image_padding').val(),
			'nc_meta_padding': $('#nc_meta_padding').val(),
			'nc_heading_padding': $('#nc_heading_padding').val(),
			'nc_excerpt_padding': $('#nc_excerpt_padding').val(),
			'nc_pagination_padding': $('#nc_pagination_padding').val()
		}
	}

	$(document).on('click', '#nc-create-style', function() {

		var groupName = $('#newscodes-groups').val();

		if ($('#newscodes-styles').find('option[data-group="'+groupName+'"]').length>9) {
			alert( 'Group can have up to 10 styles! Please create a new group!' );
			return false;
		}

		if ( nc_loading == 'active' ) {
			return false;
		}
		nc_loading = 'active';

		var settings = {
			'type' : 'new'
		};

		$.when( nc_ajax( settings ) ).done( function(response) {

			$('body').append($(response));

			$('#nc-preview-style').trigger('click');

			$('.hide-color-picker').each(function(){
				$(this).wpColorPicker({
					defaultColor: true,
					hide: true
				});
			});

		});

		return false;

	});

	$(document).on('click', '#nc-edit-style', function() {

		var styleName = $('#newscodes-styles').val();
		var groupName = $('#newscodes-groups').val();
		var groupDefault = $('#newscodes-groups').find('option[value="'+groupName+'"]').attr('data-type');

		if ( groupName == '' ) {
			alert( 'Group not selected!' );
			return false;
		}

		if ( styleName == '' ) {
			alert( 'Style not selected!' );
			return false;
		}

		if ( nc_loading == 'active' ) {
			return false;
		}
		nc_loading = 'active';

		var settings = {
			'type' : 'edit',
			'style' : styleName,
			'group' : groupName
		};

		$.when( nc_ajax( settings ) ).done( function(response) {

			$('body').append($(response));

			$('#nc_name').prop('disabled',true);
			$('#nc_name').attr('disabled','disabled');

			$('.hide-color-picker').each(function(){
				$(this).wpColorPicker({
					defaultColor: true,
					hide: true
				});
			});

			if ( groupDefault == 'default' ) {
				$('#nc-save-style').hide();
				$('#nc-save-style-side').hide();
			}

			$('#nc-preview-style').trigger('click');

		});

		return false;

	});

	$(document).on('click', '#nc-discard-style', function() {

		$('#newscodes-edit').remove();

		return false;

	});

	$(document).on('click', '#nc-generator-discard', function() {
		
		$('#newscodes-shortcode-generator').remove();
		
		return false;
		
	});

	$(document).on('click', '#nc-save-style', function() {

		alert('Styles editing is not supported in the FREE version!');

	});

	$(document).on('click', '#nc-save-as-style', function() {

		alert('Styles editing is not supported in the free version!');

	});

	$(document).on('click', '#nc-delete-style', function() {

		alert('Styles editing is not supported in the free version!');

	});

	$(document).on('click', '#nc-preview-style', function() {

		if ( nc_loading == 'active' ) {
			return false;
		}
		nc_loading = 'active';

		var wrap = $('#newscodes-style-editor');

		wrap.addClass('nc-loading');

		var settings = {
			'type' : 'preview',
			'name' : 'preview',
			'preview' : $('#nc-preview-type').val(),
			'style' : nc_get_style('preview')
		};

		$.when( nc_ajax( settings ) ).done( function(response) {

			wrap.removeClass('nc-loading');

			$('#newscodes-preview').html($(response));

			if ( $('#nc-preview-background:checked').length>0 ) {
				$('.newscodes-preview-inner').css('background-color', '#040404');
				$('#newscodes-preview').css('background-color', '#111');
			}
			else {
				$('#newscodes-preview,.newscodes-preview-inner').removeAttr('style');
			}

		});

		return false;

	});

	$(document).on('click', '#nc-create-group', function() {

		alert('Groups are not supported in the free version!');

	});

	$(document).on('click', '#nc-delete-group', function() {

		alert('Groups are not supported in the free version!');

	});


	$(document).on('click', '#newscodes-preview, #newscodes-generator-preview, #newscodes-generator-preview *, #newscodes-preview *', function() {
		return false;
	});

	$(document).on('click', '#nc-save-style-side', function() {
		$('#nc-save-style').trigger('click');
	});

	$(document).on('click', '#nc-save-as-style-side', function() {
		$('#nc-save-as-style').trigger('click');
	});

	$(document).on('click', '#nc-discard-style-side', function() {
		$('#nc-discard-style').trigger('click');
	});

	$(document).on('click', '#nc-preview-style-side', function() {
		$('#nc-preview-style').trigger('click');
	});

	$(document).on( 'click', '#nc-update-optimizations', function() {

		alert('Optimization is not supported in the free version!');

	});

	var sc_parameters = {};
	$(document).on('click', '#nc-generator-preview', function() {

		if ( nc_loading == 'active' ) {
			return false;
		}
		nc_loading = 'active';

		var wrap = $('#nc-generator-preview');

		wrap.addClass('nc-loading');

		var settings = {
			'type' : 'generator_preview',
			'name' : 'preview',
			'atts' : sc_parameters,
			'style' : $('#nc_style option:selected').val(),
			'group' : $('#nc_style option:selected').attr('data-group')
		};

		$.when( nc_ajax( settings ) ).done( function(response) {

			wrap.removeClass('nc-loading');

			$('#newscodes-generator-preview').html($(response));

			if ( $('#nc-generator-preview').hasClass('nc-not-updated') ) {
				$('#nc-generator-preview').removeClass('nc-not-updated');
			}
			if ( $('#nc-generator-background:checked').length>0 ) {
				$('#newscodes-generator-preview').css('background-color', '#111');
			}
			else {
				$('#newscodes-generator-preview').removeAttr('style');
			}

		});

		return false;

	});

	$(document).on('click', '#nc-generator', function() {

		sc_parameters = {};

		if ( nc_loading == 'active' ) {
			return false;
		}
		nc_loading = 'active';

		var settings = {
			'type' : 'generator'
		};

		$.when( nc_ajax( settings ) ).done( function(response) {

			$('body').append($(response));

			$('#nc-generator-preview').trigger('click');

		});

		return false;

	});

	nc.sc_defaults;

	function sc_generate() {

		var obj = $('.nc-generator-editor').find('.newscodes-ui-checkbox, .newscodes-ui-input, .newscodes-ui-textarea, .newscodes-ui-select');
		var objLength = obj.length;

		obj.each( function() {
			var key = $(this).attr('name');
			var value = ( $(this).hasClass('newscodes-ui-checkbox' ) ? ( $(this).is(':checked') ? 'true' : 'false' ) : $(this).val() );

			if ( typeof nc.sc_defaults[key] !== 'undefined' ) {
				if ( nc.sc_defaults[key] != value ) {
					sc_parameters[key] = value;
				}
				else {
					if ( typeof sc_parameters[key] !== 'undefined' ) {
						delete sc_parameters[key];
					}
				}
			}

			if ( !--objLength ) {
				var filters = $('#nc-filter-manager-json').val();
				if ( filters !== '' ) {
					try {
						filters = $.parseJSON(filters);
					} catch (e) {
						filters = {};
					}
					if ( !$.isEmptyObject(filters) ) {

						var sc_filters = { 'filters': '', 'filter_terms': '', 'count': 0 };
						var sc_metas = { 'meta_keys': '', 'meta_values': '', 'meta_compares':'', 'meta_types':'', 'count': 0 };
						var filtersLength = filters.length;

						$.each( filters, function(n,v) {
							if ( v['type'] == 'taxonomy' ) {
								if ( sc_filters['count'] !== 0 ) {
									sc_filters['filters'] += '|';
									sc_filters['filter_terms'] += '|';
								}
								sc_filters['filters'] += v['taxonomy'];
								sc_filters['filter_terms'] += v['term'];
								sc_filters['count']++;
							}
							if ( v['type'] == 'meta' ) {
								if ( sc_metas['count'] !== 0 ) {
									sc_metas['meta_keys'] += '|';
									sc_metas['meta_values'] += '|';
									sc_metas['meta_compares'] += '|';
									sc_metas['meta_types'] += '|';
								}
								sc_metas['meta_keys'] += v['meta_key'];
								sc_metas['meta_values'] += v['meta_value'];
								sc_metas['meta_compares'] += v['meta_compare'];
								sc_metas['meta_types'] += v['meta_type'];
								sc_metas['count']++;
							}

							if ( !--filtersLength ) {
								if ( sc_filters['filters'] !== '' ) {
									sc_parameters['filters'] = sc_filters['filters'];
									sc_parameters['filter_terms'] = sc_filters['filter_terms'];
								}
								else {
									var check = [ 'filters', 'filter_terms' ];
									$.each( check, function(i,b) {
										if ( typeof sc_parameters[b] !== 'undefined' ) {
											delete sc_parameters[b];
										}
									});
								}
								if ( sc_metas['meta_keys'] !== '' ) {
									sc_parameters['meta_keys'] = sc_metas['meta_keys'];
									sc_parameters['meta_values'] = sc_metas['meta_values'];
									sc_parameters['meta_compares'] = sc_metas['meta_compares'];
									sc_parameters['meta_types'] = sc_metas['meta_types'];
								}
								else {
									var check = [ 'meta_keys', 'meta_values', 'meta_compares', 'meta_types' ];
									$.each( check, function(i,b) {
										if ( typeof sc_parameters[b] !== 'undefined' ) {
											delete sc_parameters[b];
										}
									});
								}
							}

						});
					}
				}
				else {
					var check = [ 'filters', 'filter_terms', 'meta_keys', 'meta_values', 'meta_compares', 'meta_types' ];
					$.each( check, function(i,b) {
						if ( typeof sc_parameters[b] !== 'undefined' ) {
							delete sc_parameters[b];
						}
					});
				}

				if ( !$.isEmptyObject(sc_parameters) ) {
					var generatedParameters = '[nc_factory';
					$.each( sc_parameters, function(k,v) {
						generatedParameters += ' '+k+'="'+v+'"';
					});
					generatedParameters += ']';
					$('#nc-generated-shortcode').html(generatedParameters);
				}
				else {
					$('#nc-generated-shortcode').html('[nc_factory]');
				}
				if ( !$('#nc-generator-preview').hasClass('nc-not-updated') ) {
					$('#nc-generator-preview').addClass('nc-not-updated');
				}
			}

		});
	}
	
	$(document).on( 'change', '.nc-filter-settings-collect', function() {
		if ( !$('#nc-update-filters').hasClass('nc-not-updated') ) {
			$('#nc-update-filters').addClass('nc-not-updated');
		}
	});

	$(document).on( 'change', '.newscodes-ui-checkbox, .newscodes-ui-input, .newscodes-ui-textarea, .newscodes-ui-select', function() {
		sc_generate();
	});

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
				var postType = $('#nc_post_type').val();

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

					alert('Filters updated!');
					if ( $('#nc-update-filters').hasClass('nc-not-updated') ) {
						$('#nc-update-filters').removeClass('nc-not-updated');
					}
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

		var value = $('#nc_post_type').val();

		filter.find('.nc-taxonomy[data-param="post_type_'+value+'"]').show();

		if ( !$('#nc-update-filters').hasClass('nc-not-updated') ) {
			$('#nc-update-filters').addClass('nc-not-updated');
		}

	});

	$(document).on('click', '#nc-remove-filter', function() {
		$(this).closest('.nc-composer-filter').remove();
		$('#nc-update-filters').trigger('click');
	});

	$(document).on('click', '#nc-update-filters', function() {
		var activeFilters = $('#nc-composer-filters-wrap .nc-composer-filter').length;

		if ( activeFilters == 0 ) {
			$('#nc-filter-manager-json').val('').attr('value','');
			alert('Filters updated!');
		}

		nc_do_filters_update();
		sc_generate();

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
			var value = $('#nc_post_type').val();
			filter.find('.nc-type').show();
			filter.find('.nc-taxonomy[data-param="post_type_'+value+'"]').show();
		}

	});


	$(document).on('change', '.nc-taxonomy', function() {

		var filter = $(this).closest('.nc-composer-filter');

		var value = $(this).val();

		filter.find('.nc-taxonomy-terms').hide();

		if (value!=''){
			filter.find('.nc-taxonomy-terms[data-param="taxonomy_'+value+'"]').show();
		}

	});

	$(document).on('click', '#nc-generator-save, #nc-generator-save-as', function() {

		alert('Shortcode saving/editing is not supported in the free version!');

	});

	$(document).on('click', '#nc-generator-delete', function() {

		alert('Shortcode saving/editing is not supported in the free version!');

	});

	$(document).on('click', '#nc-generator-edit', function() {

		alert('Shortcode saving/editing is not supported in the free version!');

	});

})(jQuery);