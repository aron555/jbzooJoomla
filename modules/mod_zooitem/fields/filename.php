<?php
/**
 * @package   ZOO Item
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Form\FormField;

defined('JPATH_BASE') or die;

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JFormFieldFilename extends FormField {

	protected $type = 'Filename';

	public function getInput() {

		// get app
		$app = App::getInstance('zoo');

		// create select
		$path    = dirname(dirname(__FILE__)).$this->element->attributes()->path;
		$options = array();

		if (is_dir($path)) {
			foreach (Folder::files($path, '^([-_A-Za-z0-9]+)\.php$') as $tmpl) {
				$tmpl = basename($tmpl, '.php');
				$options[] = $app->html->_('select.option', $tmpl, ucwords($tmpl));
			}
		}

		return $app->html->_('select.genericlist', $options, "{$this->formControl}[{$this->group}][{$this->fieldname}]", 'class=" form-select"', 'value', 'text', $this->value);
	}

}
