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

$params = $this->app->data->create($params);

// add tooltip
$tooltip = '';
if ($params->get('show_tooltip') && ($description = $element->config->get('description'))) {
	$tooltip = ' class="hasTip" title="'.Text::_($description).'"';
}

// create label
$label  = '<strong'.$tooltip.'>';
$label .= Text::_($params->get('altlabel') ? $params->get('altlabel') : $element->config->get('name'));
$label .= '</strong>';

// create error
$error = '';
if (@$element->error) {
    $error = '<p class="error-message">'.(string) $element->error.'</p>';
}

// create class attribute
$class = 'element element-'.$element->getElementType().($params->get('first') ? ' first' : '').($params->get('last') ? ' last' : '').($params->get('required') ? ' required' : '').(@$element->error ? ' error' : '');

$element->loadAssets();

?>
<div class="<?php echo $class; ?>">
	<?php echo $label.$element->renderSubmission($params).$error; ?>
</div>
