<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// load js
use Joomla\CMS\Language\Text;

$this->app->document->addScript('fields:global.js');

// init vars
$id     = uniqid('itemorder-');
$global = $parent->getValue((string) $name) === null;

// create html
echo '<div class="global itemorder">';
echo '<input id="'.$id.'" type="checkbox" name="_global"'.($global ? ' checked="checked"' : '').' />';
echo '<label for="'.$id.'">'.Text::_('Global').'</label>';
echo '<div class="input">';
echo $this->app->field->render('zooitemorder', $name, $value, $node, compact('control_name', 'parent'));
echo '</div>';
echo '</div>';
