<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// init vars
$class = (string) $node->attributes()->class ? (string) $node->attributes()->class : 'inputbox';

?>

<div class="zoo-calendar">
	<?php echo $this->app->html->_('zoo.calendar', $value, $control_name.'['.$name.']', uniqid('calendar-'), compact('class'), true); ?>
</div>