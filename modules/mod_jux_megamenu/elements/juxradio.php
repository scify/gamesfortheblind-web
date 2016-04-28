<?php
/**
 * ------------------------------------------------------------------------
 * JSE Options: enhance function for module configuration
 * ------------------------------------------------------------------------
 * Version 2.0.0
 * Copyright (C) 2008-2013 Joomseller Solutions. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Joomseller
 * Websites: http://www.joomseller.com
 * ------------------------------------------------------------------------
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
if(!class_exists('JFormFieldRadio')) {
	JFormHelper::loadFieldClass('radio');
}

/**
 * Create Radio List Button. With the ability to show/hide sub-options.
 * Example xml:
 * <field
 * 	name="mod_js_show_hide"
 * 	type="JSERadio"
 * 	default="1"
 * 	label="MOD_JS_LABEL"
 * 	description="MOD_JS_DESC">
 * 	<option value="1" sub_fields="mod_yes_field_1,mod_yes_field_2">JYES</option>
 * 	<option value="0" sub_fields="mod_no_field_1,mod_no_field_2">JNO</option>
 * </field>
 */
class JFormFieldJUXRadio extends JFormFieldRadio {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'JUXRadio';
	
	/**
	 * List of all sub-fields
	 * 
	 * @var		string
	 */
	protected $all_sub_fields = array();

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		if (!defined ('JUX_OPTION_FIELDS_ASSETS')) {
			define ('JUX_OPTION_FIELDS_ASSETS', 1);
			$uri = str_replace("\\","/", str_replace(JPATH_SITE, JURI::root(), dirname(__FILE__) ));
			
			JHTML::script($uri.'/assets/js/juxoptions.js');
		}

		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="radio ' . (string) $this->element['class'] . '"' : ' class="radio"';

		// Get the field options.
		$options = $this->getOptions();

		// Initialize sub fields data.
		$all_sub_fields		= !empty($this->all_sub_fields) ? ' data-all_sub_fields="' . implode(',', $this->all_sub_fields) . '"' : '';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . $all_sub_fields . '>';

		// Build the radio field output.
		foreach ($options as $i => $option)
		{
			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
			$required = !empty($option->required) ? ' required="required" aria-required="true"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			// Initialize data sub-fields
			$subfields = !empty($option->data_sub_fields) ? ' data-sub_fields="' . $option->data_sub_fields . '"' : '';

			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '" value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . $required . $subfields . '/>';

			$html[] = '<label for="' . $this->id . $i . '"' . $class . '>'
				. JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';
		}

		// End the radio field output.
		$html[] = '</fieldset>';

		// Onload Script
		$html[] = '<script type="text/javascript">';
		$html[] = '	jQuery(document).ready(function() {';
		$html[] = '		jux_ToggleOption("'.$this->id.'");';
		$html[] = '	});';
		$html[] = '</script>';
		
		return implode("\n", $html);
	}

	/**
	 * Override getOptions Method to get sub fields list.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions() {
		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
							'select.option', (string) $option['value'], trim((string) $option), 'value', 'text', ((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Get sub_fields.
			$sub_fields = str_replace("\n", '', trim($option['sub_fields']));
			$sub_fields_id = '';
			if(!empty($sub_fields)) {
				$all_sub_fields = explode(',',$sub_fields);
				$sub_fields_id_list	= array();
				foreach($all_sub_fields as $sub_field) {
					if(strpos($sub_field, '/') != false) {
						$slash_pos	= strpos($sub_field, '/');
						$tmp_group = $this->group;
						$this->group = substr($sub_field, 0, $slash_pos);
						$sub_field = substr($sub_field, $slash_pos + 1);
						$sub_fields_id_list[] = $this->getId(null, $sub_field);
						$this->group = $tmp_group;
						
						continue;
					}
					$sub_fields_id_list[] = $this->getId(null, $sub_field);
				}
				$sub_fields_id = implode(',', $sub_fields_id_list);
				$this->all_sub_fields = array_merge($this->all_sub_fields, array((string)$option['value'] => $sub_fields_id));
			}

			// Set some JavaScript option attributes.
			$onclick = !empty($option['onclick']) ? (string) $option['onclick'] : '';

			// Add default onclick
			$onclick .= ' jux_ToggleOption(\''.$this->id.'\');';

			$tmp->onclick = $onclick;

			// Set sub fields data
			$tmp->data_sub_fields = $sub_fields_id;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}

}