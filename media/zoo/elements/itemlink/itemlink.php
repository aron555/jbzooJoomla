<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: ElementItemLink
		The item link element class
*/

use Joomla\CMS\Language\Text;

class ElementItemLink extends Element {

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/
	public function hasValue($params = array()) {
		return true;
	}

    /*
       Function: getValue
            Returns the element's value.

       Parameters:
            $params - render parameter

        Returns:
            Value
    */
	public function getValue($params = array())
    {
        if ($this->_item->getState()) {
            return $this->app->route->item($this->_item);
        }
    }

    /*
       Function: edit
           Renders the edit form field.

       Returns:
           String - html
    */
	public function edit() {
		return null;
	}

	/*
		Function: render
			Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {
		$params = $this->app->data->create($params);
		$text = Text::_($params->get('link_text') ? $params->get('link_text') : 'READ_MORE');
		return $this->_item->getState() ? '<a href="' . $this->getValue() . '">' . $text . '</a>' : $text;
	}

}
