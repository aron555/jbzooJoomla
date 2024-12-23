<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: ElementItemAccess
		The item access element class
*/

use Joomla\CMS\Language\Text;

class ElementItemAccess extends Element implements iSubmittable {

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
		return $this->app->html->_('control.accesslevel', array(), $this->getControlName('value'), 'class="form-select"', 'value', 'text', $this->_item->access);
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
		$groups = $this->app->zoo->getGroups();
		if (!isset($groups[$value->get('value')])) {
			throw new AppValidatorException('Please choose a valid access level');
		}
        return (array) $value;
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
		$this->_item->access = (int) @$data['value'];
	}

}
