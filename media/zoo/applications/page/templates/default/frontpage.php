<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

$this->app->error->raiseWarning(0, Text::_('Error Displaying Layout').' (The Pages App does not support a "'.$this->getLayout().'" view. It should display static content only. Please use another app.)');
