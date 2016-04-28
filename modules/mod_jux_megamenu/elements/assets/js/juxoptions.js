/**
 * ------------------------------------------------------------------------
 * JS Options: enhance function for module configuration
 * ------------------------------------------------------------------------
 * Copyright (C) 2008-2013 Joomseller Solutions. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Joomseller
 * Websites: http://www.joomseller.com
 * ------------------------------------------------------------------------
 */

/**
 * Toggle show/hide sub fields of a field list.
 * 
 */
function jux_ToggleOptions(field_list) {
	if(field_list == undefined || (/^\s*$/).test(field_list)) {
		return;
	}

	var fields = field_list.split(',');
	fields = jux_CleanArray(fields);

	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		jux_ToggleOption(fields[i]);
	}
}

/**
 *	Function of jse radio field for toggle show/hide sub fields.
 *
 *	@param	string	field_id	The id of the control field.
 */
function jux_ToggleOption(field_id) {
	if((/^\s*$/).test(field_id)) {
		return;
	}

	var control_field = jux_GetField(field_id);

	if(control_field == undefined) {
		return;
	}

	// Check is hiding
	if(jux_IsHidding(control_field)) {
		return;
	}

	// Hide all sub fields, sub-fields of sub-fields are included.
	var all_sub_fields = control_field.data('all_sub_fields');
	jux_HideOptions(all_sub_fields);

	// Get active sub fields
	var active_sub_fields	= jux_GetActiveSubfields(control_field);
	jux_ShowOptions(active_sub_fields);

	// repeate toggle option with sub-fields
	jux_ToggleOptions(active_sub_fields)
}

/**
 * Get the active option from a control field.
 */
function jux_GetActiveSubfields(control_field) {
	var active_option = undefined;

	// Check if it's radio list
	if(control_field.is("fieldset")) {
		active_option = control_field.find('input:radio:checked');
	}

	// Check if it's dropdown list
	if (control_field.is("select")) {
		active_option = control_field.find("option:selected");
	}

	if(active_option == undefined || active_option == null) {
		return '';
	}

	var active_sub_fields = [];
	active_option.each(function() {
		var $this = jQuery(this);
		var sub_field = $this.data('sub_fields');
		if(sub_field != undefined && sub_field != null && sub_field != '') {
			active_sub_fields.push(sub_field);
		}
	});

	return active_sub_fields.join(",");
}

/**
 * Function for hide options.
 * 
 * @param   array  sub_fields  The list of fields to Hide.
 */
function jux_HideOptions(sub_fields) {
	if(sub_fields == undefined || (/^\s*$/).test(sub_fields)) {
		return;
	}

	var fields = sub_fields.split(',');
	fields = jux_CleanArray(fields);

	for(var i = 0; i < fields.length; i ++){
		jux_HideOption(fields[i]);
	}
}

/**
 * Function for show options.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function jux_ShowOptions(sub_fields) {
	if(sub_fields == undefined || (/^\s*$/).test(sub_fields)) {
		return;
	}

	var fields = sub_fields.split(',');
	fields = jux_CleanArray(fields);
	
	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		jux_ShowOption(fields[i]);
	}
}

/**
 * Function for Show one options
 * 
 * @param   string  field_name  Name of Field to show.
 */
function jux_ShowOption(field_id) {
	var field	= jux_GetField(field_id);

	if(field == undefined || field == null) {
		return;
	}

	// Check if it is already shown
	if(!jux_IsHidding(field)) {
		return;
	}

	// Get wrapper control
	var control	= jux_GetWrapperControl(field);
	
	// Show
	if(control !== undefined && control != null) {
		control.removeClass('hide');
	}
}

/**
 * Function for Hide one options
 * 
 * @param   field_id  Name of Field to hide.
 */
function jux_HideOption(field_id) {
	var field	= jux_GetField(field_id);
	
	if(field == undefined || field == null) {
		return;
	}

	// Check if it is already hidden
	if(jux_IsHidding(field)) {
		return;
	}

	// Get wrapper control
	var control	= jux_GetWrapperControl(field);
	
	// Hide
	if(control !== undefined && control != null) {
		control.addClass('hide');

		// Also hide all sub-fields
		jux_HideOptions(field.data('all_sub_fields'));
	}
}

/**
 * Get wrapper control
 *
 * @param	field	Current field.
 */
function jux_GetWrapperControl (field) {
	// Joomla 3.0
	var control	= field.closest('div.control-group');

	// Joomla 2.5 field
	if(control == undefined || control == null || control.length == 0) {
		control = field.closest('li');
	}

	return control;
}

/**
 * Check if current field is hidding or not.
 *
 * @param	field	Current field
 */
function jux_IsHidding(field) {
	var wrapper	= jux_GetWrapperControl(field);
	if(wrapper == undefined || wrapper == null || wrapper.hasClass("hide")) {
		return true;
	}

	return false;
}

function jux_GetField(field_id) {
	var field	= jQuery('#' + field_id);
	if(field == undefined || field == null) {
		field	= jQuery('#' + field_id + '-lbl');
	}

	return field;
}

function jux_CleanArray(arrayObjects) {
	var uniqueObjects = [];
	jQuery.each(arrayObjects, function(i, el){
		if(jQuery.inArray(el, uniqueObjects) === -1 && !(/^\s*$/).test(el)) uniqueObjects.push(el);
	});

	return uniqueObjects;
}


function showDefaultTabIndex(option_name) {
	toggleDefaultTabIndex(option_name, true);
}

function hideDefaultTabIndex(option_name) {
	toggleDefaultTabIndex(option_name, false);
}

function toggleDefaultTabIndex(option_name, show) {
	// Get the default tab index
	var default_tab_index = jQuery('jform_params_' + 'mod_tabFirstIndex');

	// Loop the select options:
	for ( var i = 0; i < default_tab_index.length; i++) {
		if (default_tab_index[i].value != option_name) {
			continue;
		} else {
			// get the Bootstrap element:
			var bootstrap_element = jQuery('jform_params_' + 'mod_tabFirstIndex'
					+ '_chzn_o_' + i);

			if (show && default_tab_index[i].hasClass('hide')) {
				bootstrap_element.setStyle('display', null);
				default_tab_index[i].removeClass('hide');

				if (default_tab_index.selectedIndex == -1) {
					changeSelectedDefaultTabIndex(default_tab_index);
				}
			}

			if (!show && !default_tab_index[i].hasClass('hide')) {
				bootstrap_element.setStyle('display', 'none');
				default_tab_index[i].addClass('hide');
				if (default_tab_index[i].selected) {
					default_tab_index[i].removeAttribute('selected');
					changeSelectedDefaultTabIndex(default_tab_index);
				}
			}

			break;
		}
	}
}

function changeSelectedDefaultTabIndex(default_tab_index) {
	// Get the single bootstrap
	chzn_DIV = jQuery('jform_params_' + 'mod_tabFirstIndex' + '_chzn');
	chzn_single = chzn_DIV.firstChild;

	// Loop the select options:
	for ( var i = 0; i < default_tab_index.length; i++) {
		// get the Bootstrap element:
		var bootstrap_element = jQuery('jform_params_' + 'mod_tabFirstIndex'
				+ '_chzn_o_' + i);

		if (default_tab_index[i].hasClass('hide')) {
			continue;
		} else {
			default_tab_index[i].selected = true;
			chzn_single.firstChild.innerHTML = default_tab_index[i].innerHTML;
			break;
		}
	}

	if (i == default_tab_index.length) {
		default_tab_index.selectedIndex = -1;
		chzn_single.firstChild.innerHTML = '';
	}
}