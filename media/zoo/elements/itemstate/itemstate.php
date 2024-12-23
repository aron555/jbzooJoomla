<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: ElementItemState
		The item state element class
*/

use Joomla\CMS\Language\Text;

class ElementItemState extends Element implements iSubmittable {

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/
	public function hasValue($params = array()) {
		return $this->_item && $this->_item->canEditState();
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
		$now		  = $this->app->date->create()->toUnix();
		$publish_up   = $this->app->date->create($this->_item->publish_up)->toUnix();
		$publish_down = $this->app->date->create($this->_item->publish_down)->toUnix();

		if ($now <= $publish_up && $this->_item->state == 1) {
			return Text::_('Published');
		} else if (($now <= $publish_down || $this->_item->publish_down == $this->app->database->getNullDate()) && $this->_item->state == 1) {
			return Text::_('Published');
		} else if ($now > $publish_down && $this->_item->state == 1) {
			return Text::_('Expired');
		} else if ($this->_item->state == 0) {
			return Text::_('Unpublished');
		} else if ($this->_item->state == -1) {
			return Text::_('Archived');
		}
	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - AppData submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array()) {
		$value = $this->_item->id == null ? $params->get('default', 0) : $this->_item->state;
		return $this->app->html->_('select.booleanlist', $this->getControlName('value'), null, $value);
	}

	/*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function validateSubmission($value, $params) {
        return array('value' => (int) (bool) $value->get('value'));
	}

	/*
		Function: bindData
			Set data through data array.

		Parameters:
			$data - array

		Returns:
			Void
	*/
	public function bindData($data = array()) {
		$this->_item->state = (int) @$data['value'];
	}

}
