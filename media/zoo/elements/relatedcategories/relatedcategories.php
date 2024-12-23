<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
   Class: ElementRelatedCategories
       The category element class
*/

use Joomla\CMS\Language\Text;

class ElementRelatedCategories extends Element implements iSubmittable {

    /*
        Function: getValue
            Returns the element's value.

       Parameters:
            $params - render parameter

        Returns:
            Value
    */
	public function getValue($params = array()) {
        return $this->app->table->category->getById($this->get('category', array()), true);
    }

    /*
        Function: render
            Override. Renders the element.

       Parameters:
            $params - render parameter

        Returns:
            String - html
    */
	public function render($params = array()) {

		$params = $this->app->data->create($params);
		$category_links = array();
		$categories = $this->app->table->category->getById($this->get('category', array()), true);
		foreach ($categories as $category) {
			$category_links[] = '<a href="'.$this->app->route->category($category).'">'.$category->name.'</a>';
		}

		return $this->app->element->applySeparators($params->get('separated_by'), $category_links);

	}

	/*
	   Function: _edit
	       Renders the edit form field.
		   Must be overloaded by the child class.

	   Returns:
	       String - html
	*/
	public function edit(){
		//init vars
		$multiselect = $this->config->get('multiselect');

        $options = array();
        if (!$multiselect) {
            $options[] = $this->app->html->_('select.option', '', '-' . Text::_('Select Category') . '-');
        }

		$attribs = $multiselect ? 'size="5" multiple="multiple"' : '';

		return $this->app->html->_('zoo.categorylist', $this->app->zoo->getApplication(), $options, $this->getControlName('category', true), $attribs, 'value', 'text', $this->get('category', array()));
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
        //init vars
        $multiselect = $this->config->get('multiselect');

        $options = array();
        if (!$multiselect) {
            $options[] = $this->app->html->_('select.option', '', '-' . Text::_('Select Category') . '-');
        }

        $attribs = 'class="form-select"';
        $attribs .= $multiselect ? ' size="5" multiple="multiple"' : '';

        return $this->app->html->_('zoo.categorylist', $this->app->zoo->getApplication(), $options, $this->getControlName('category', true), $attribs, 'value', 'text', $this->get('category', array()));
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
        $options = array('required' => $params->get('required'));
		$messages = array('required' => 'Please choose a related category.');
        $clean = $this->app->validator
				->create('foreach', $this->app->validator->create('string', $options, $messages), $options, $messages)
				->clean($value->get('category'));

        $categories = array_keys($this->_item->getApplication()->getCategories());
        foreach ($clean as $category) {
            if (!empty($category) && !in_array($category, $categories)) {
                throw new AppValidatorException('Please choose a correct category.');
            }
        }

		return array('category' => $clean);
	}

}
