<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// create label
$label = '';
if (isset($params['showlabel']) && $params['showlabel']) {
	$label .= '<h2 class="uk-panel-title uk-margin-remove-top">';
	$label .= ($params['altlabel']) ? $params['altlabel'] : $element->config->get('name');
	$label .= ': </h2>';
}

?>

<?php echo $label; ?>

<?php echo $element->render($params); ?>