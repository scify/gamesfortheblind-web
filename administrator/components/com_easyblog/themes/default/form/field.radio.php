<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$radioKey = ($prefix) ? $prefix . $field->attributes->name : 'params[' . $field->attributes->name . ']';

?>
<?php foreach ($field->options as $option) {
    if ($skipEmpty && ($option->value < 0)) { continue; }
?>
    <input type="radio" name="<?php echo $radioKey;?>" value="<?php echo $option->value; ?>" <?php echo ($option->value == $params->get($prefix . $field->attributes->name, $default)) ? ' checked': '';?> />&nbsp; <?php echo JText::_($option->label);?>
<?php } ?>
