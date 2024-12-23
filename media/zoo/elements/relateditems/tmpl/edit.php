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

$this->app->html->_('behavior.modal', 'a.modal-button');

?>

<div id="<?php echo $this->identifier; ?>" class="select-relateditems">
	<ul>

	<?php foreach ($data as $item) : ?>

		<li>
			<div>
				<div class="item-name"><?php echo $item->name; ?></div>
				<div class="item-sort" title="<?php echo Text::_('Sort Item'); ?>"></div>
				<div class="item-delete" title="<?php echo Text::_('Delete Item'); ?>"></div>
				<input type="hidden" name="<?php echo $this->getControlName('item', true); ?>" value="<?php echo $item->id; ?>"/>
			</div>
		</li>

	<?php endforeach; ?>
	</ul>
	<a class="item-add modal-button" rel="{handler: 'iframe', size: {x: 850, y: 500}}" title="<?php echo Text::_('Add Item'); ?>" href="<?php echo $link; ?>" ><?php echo Text::_('Add Item'); ?></a>
</div>

<script type="text/javascript">
	jQuery(function($) {
		$('#<?php echo $this->identifier; ?>').ElementRelatedItems({ variable: '<?php echo $this->getControlName('item', true); ?>', msgDeleteItem: '<?php echo Text::_('Delete Item'); ?>', msgSortItem: '<?php echo Text::_('Sort Item'); ?>' });
	});
</script>
