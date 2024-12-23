<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;

// no direct access
defined('_JEXEC') or die('Restricted access');

// add js
$this->app->document->addScript('assets:js/tag.js');

// filter output
foreach ($this->tags as $tag) {
    OutputFilter::objectHTMLSafe($tag, ENT_QUOTES);
}

?>

<form class="tags-default" action="<?php echo $this->app->link(array('controller' => $this->controller)); ?>" method="post" name="adminForm" id="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<?php if ($this->is_filtered || count($this->tags) > 0) :?>

	<ul class="filter">
		<li class="filter-left">
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="rounded" />
			<button class="btn btn-small" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button>
			<button class="btn btn-small" onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo Text::_('Reset'); ?></button>
		</li>
		<li class="filter-right">
            <?php echo str_replace(array('input-mini', 'size="1"', 'form-select'), array('', '', 'inputbox'), $this->pagination->getLimitBox()); ?>
		</li>
	</ul>

	<?php endif;

	if(count($this->tags) > 0) : ?>

	<table class="list stripe">
		<thead>
			<tr>
				<th class="checkbox">
					<input type="checkbox" class="check-all" />
				</th>
				<th class="name" colspan="2">
					<?php echo $this->app->html->_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th class="items" colspan="1">
					<?php echo $this->app->html->_('grid.sort', 'Items', 'items', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->tags as $tag) : ?>
			<?php $link_items = $this->app->link(array('controller' => 'item','filter_category_id' => '-1', 'filter_type' => '', 'filter_author_id' => '', 'search' => $tag->name)); ?>
			<tr>
				<td class="checkbox">
					<input type="checkbox" name="cid[]" value="<?php echo $tag->name; ?>" />
				</td>
				<td class="icon"></td>
				<td class="name">
					<span class="edit-tag">
						<a href="#" title="<?php echo Text::_('Edit Tag');?>"><?php echo $tag->name; ?></a>
					</span>
				</td>
				<td class="items">
					<a href="<?php echo $link_items; ?>"><?php echo $tag->items; ?></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php elseif($this->is_filtered) :

			$title   = Text::_('SEARCH_NO_TAG').'!';
			$message = null;
			echo $this->partial('message', compact('title', 'message'));

		else :

			$title   = Text::_('NO_TAGS_YET').'!';
			$message = Text::_('TAG_MANAGER_DESCRIPTION');
			echo $this->partial('message', compact('title', 'message'));

		endif;
	?>

</div>

<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="changeapp" value="<?php echo $this->application->id; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

<script type="text/javascript">
	jQuery(function($) {
		$('#adminForm').BrowseTags({ msgSave: '<?php echo Text::_('Save'); ?>', msgCancel: '<?php echo Text::_('Cancel'); ?>' });
	});
</script>

</form>

<?php echo ZOO_COPYRIGHT;
