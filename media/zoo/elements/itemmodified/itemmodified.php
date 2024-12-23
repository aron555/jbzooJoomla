<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: ElementItemModified
		The item modified element class
*/
class ElementItemModified extends Element {

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
    public function getValue($params = array()) {
        if (!empty($this->_item)) {
            return $this->_item->modified;
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
		$format = $params->get('date_format');

		if ($format == 'custom') {
			$format = $params->get('custom_format');
		}

		if (!empty($this->_item)) {
			return $this->app->html->_('date', $this->_item->modified, $this->app->date->format($format), $this->app->date->getOffset());
		}
	}

}
