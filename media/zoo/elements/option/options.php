<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// get element from parent parameter form
use Joomla\CMS\Language\Text;

$element = $parent->element;
$config  = $element->config;

// init vars
$id = uniqid('option-');
$i  = 0;

?>

<div id="<?php echo $id; ?>" class="options">
	<ul>
		<?php
			foreach ($config->get('option', array()) as $opt) {
				echo '<li>'.$element->editOption($control_name, $i++, $opt['name'], $opt['value']).'</li>';
			}
		?>
		<li class="hidden" ><?php echo $element->editOption($control_name, '0', '', ''); ?></li>
	</ul>
	<div class="add"><?php echo Text::_('Add Option'); ?></div>
</div>

<script type="text/javascript">
	jQuery('#<?php echo $id; ?>').ElementSelect({variable: '<?php echo $control_name; ?>'});
</script>
