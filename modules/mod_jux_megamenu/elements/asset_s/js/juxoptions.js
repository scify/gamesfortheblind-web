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
 * Function for hide options.
 * 
 * @param   array  sub_fields  The list of fields to Hide.
 */
function js_HideOptions(sub_fields) {
	if((/^\s*$/).test(sub_fields)) {
		return;
	}

	fields = sub_fields.split(',');
	for(var i = 0; i < fields.length; i ++){
		js_HideOption(fields[i]);
	}
}

/**
 * Function for show options.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function js_ShowOptions(sub_fields) {
	if((/^\s*$/).test(sub_fields)) {
		return;
	}

	fields = sub_fields.split(',');
	
	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		js_ShowOption(fields[i]);
	}
}

/**
 * Function for show options.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function js_ShowOptionsByControl(control_field, sub_fields_array) {
	if((/^\s*$/).test(control_field)) {
		return;
	}
	
	if($(control_field) == null){
		return;
	}
	
	var key = $(control_field).get("value");
	var sub_fields = sub_fields_array[key];

	if(sub_fields === undefined) {
		return;
	}

	fields = sub_fields.split(',');
	
	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		js_ShowOption(fields[i]);
	}
}

/**
 * Function for Show one options
 * 
 * @param   string  field_name  Name of Field to show.
 */
function js_ShowOption(field_id) {
	var field	= $(field_id);
	if(field == null) {
		field	= $(field_id + '-lbl');
	}
	
	if(field == null) {
		return;
	}

	// Joomla 3.0
	var control	= field.getParent('div.control-group');
	
	// Joomla 2.5 field
	if(control == null) {
		control = field.getParent('li');
	}
	
	// Show
	if(control !== null && control.hasClass('hide')) {
		control.removeClass('hide');
	}
}

/**
 * Function for Hide one options
 * 
 * @param   string  field_name  Name of Field to hide.
 */
function js_HideOption(field_id) {
	var field	= $(field_id);
	if(field == null) {
		field	= $(field_id + '-lbl');
	}
	
	if(field == null) {
		return;
	}

	// Joomla 3.0
	var control	= field.getParent('div.control-group');
	
	// Joomla 2.5 field
	if(control == null) {
		control = field.getParent('li');
	}
	
	// Hide
	if(control !== null && !control.hasClass('hide')) {
		control.addClass('hide');
	}
}

