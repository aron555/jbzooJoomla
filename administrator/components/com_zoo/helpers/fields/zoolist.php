<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Joomla\CMS\Language\Text;

$name  = $control_name.'['.$name.']';
$class = ($node->attributes('class') ? $node->attributes('class') : 'inputbox');

printf('<select %s>', $this->app->field->attributes(compact('name', 'class')));

foreach ($node->children() as $option) {

	// set attributes
	$attributes = array('value' => (string) $option);

	// is checked ?
	if ((string) $option == $value) {
		$attributes['selected'] = 'selected';
	}

	printf('<option %s>%s</option>', $this->app->field->attributes($attributes), Text::_((string) $option->attributes()->name));
}

printf('</select>');
