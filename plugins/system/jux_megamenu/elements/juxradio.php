<?php
/**
 * ------------------------------------------------------------------------
 * JUX Options: enhance function for module configuration
 * ------------------------------------------------------------------------
 * Copyright (C) 2008-2013 Joomseller Solutions. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Joomseller
 * Websites: http://www.joomseller.com
 * ------------------------------------------------------------------------
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
if(!class_exists('JFormFieldRadio')) {
	require_once JPATH_ROOT.'/libraries/joomla/form/fields/radio.php';
}

/**
 * Create Radio List Button. With the ability to show/hide sub-options.
 * Example xml:
 * <field
 * 	name="mod_js_show_hide"
 * 	type="JSRadio"
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
	protected $type = 'JSRadio';
	
	/**
	 * Active sub-fields.
	 * 
	 * @var		string
	 */
	protected $active_sub_fields = '';
	
	/**
	 * List of all sub-fields
	 * 
	 * @var		string
	 */
	protected $sub_fields_list = array();

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		if (!defined ('JS_MODULE_OPTIONS_ASSETS')) {
			define ('JS_MODULE_OPTIONS_ASSETS', 1);
			$uri = str_replace("\\","/", str_replace(JPATH_SITE, JURI::root(), dirname(__FILE__) ));
			JHTML::script($uri.'/assets/js/juxoptions.js');
			JHTML::stylesheet($uri.'/assets/css/juxoptions.css');
		}

		$html = parent::getInput();
		$this->onload_script();
		
		return $html;
	}

	/**
	 * Method to get the script onload
	 * 
	 * @return blank
	 */
	private function onload_script() {
		?>
		<script type="text/javascript">
			var js_subfield_<?php echo $this->element['name']; ?> = "<?php echo implode(',', $this->sub_fields_list); ?>";
			window.addEvent('load', function() {
				js_HideOptions(js_subfield_<?php echo $this->element['name']; ?>);
				js_ShowOptions('<?php echo $this->active_sub_fields; ?>');
			});
		</script>
		<?php

		return;
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
				$sub_fields_list = explode(',',$sub_fields);
				$sub_fields_id_list	= array();
				foreach($sub_fields_list as $sub_field) {
					$sub_fields_id_list[] = $this->getId(null, $sub_field);
				}
				$sub_fields_id = implode(',', $sub_fields_id_list);
				$this->sub_fields_list = array_merge($this->sub_fields_list, array((string)$option['value'] => $sub_fields_id));
			}
			
			// Check if it's selected
			if($option["value"] == $this->value) {
				$this->active_sub_fields = $sub_fields_id;
			}

			// Set some JavaScript option attributes.
			$onclick = !empty($option['onclick']) ? (string) $option['onclick'] : '';

			// Add default onclick
			$onclick .= ' js_HideOptions(js_subfield_'.$this->element['name'].');';
			$onclick .= "js_ShowOptions('$sub_fields_id');";

			$tmp->onclick = $onclick;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}

}