<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

// include assets css
$this->app->document->addStylesheet('elements:download/assets/css/download.css');

switch ($display) {
	case 'download_limit':
		$download_limit = ($download_limit) ? $download_limit : '-';
		echo $download_limit;
		break;

	case 'filesize':
		echo '<span>'.$size.'</span>';
		break;

	case 'filehits':
		echo '<span>'.$hits.'</span>';
		break;

	case 'buttonlink':
		if ($limit_reached) {
			echo '<a class="uk-button" href="javascript:alert(\''.Text::_('Download limit reached').'\');" title="'.Text::_('Download limit reached').'">'.Text::_('Download').'</a>';
		} else {
			echo '<a class="uk-button uk-button-primary" href="'.Route::_($download_link).'" title="'.$download_name.'">'.Text::_('Download').'</a>';
		}
		break;

	case 'imagelink':
		if ($limit_reached) {
			echo '<div class="zo-element-download-type-'.$filetype.'" title="'.Text::_('Download limit reached').'"></div>';
		} else {
			echo '<a class="zo-element-download-type-'.$filetype.'" href="'.Route::_($download_link).'" title="'.$download_name.'"></a>';
		}
		break;

	default:
		if ($limit_reached) {
			echo Text::_('Download limit reached');
		} else {
			echo '<a href="'.Route::_($download_link).'" title="'.$download_name.'">'.$download_name.'</a>';
		}
}
