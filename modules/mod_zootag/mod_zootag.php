<?php
/**
 * @package   ZOO Tag
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Helper\ModuleHelper;

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

// get app
$zoo = App::getInstance('zoo');

// load zoo frontend language file
$zoo->system->language->load('com_zoo');

// init vars
$path = dirname(__FILE__);

//register base path
$zoo->path->register($path, 'mod_zootag');

// register helpers
$zoo->path->register($path, 'helpers');
$zoo->loader->register('TagModuleHelper', 'helpers:helper.php');

// init vars
$application = $zoo->table->application->get($params->get('application', 0));

// is application ?
if (empty($application)) {
	return null;
}

// get tags
$tags = $zoo->tagmodule->buildTagCloud($application, $params);

// filter output
foreach ($tags as $tag) {
    OutputFilter::objectHTMLSafe($tag, ENT_QUOTES);
}

// load template
include(ModuleHelper::getLayoutPath('mod_zootag', $params->get('theme', 'list')));
