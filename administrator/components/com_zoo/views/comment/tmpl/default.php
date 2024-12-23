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

$this->app->html->_('behavior.tooltip');

// add js
$this->app->document->addScript('assets:js/comment.js');

?>

<form class="comments-default" action="<?php echo $this->app->link(array('controller' => $this->controller)); ?>" method="post" name="adminForm" id="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<ul class="filter">
		<li class="filter-left">
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="rounded" />
			<button class="btn btn-small" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button>
			<button class="btn btn-small" onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo Text::_('Reset'); ?></button>
		</li>
		<li class="filter-right">
			<?php echo str_replace(array('input-mini', 'size="1"', 'form-select'), array('', '', 'inputbox'), $this->pagination->getLimitBox()); ?>
		</li>
		<li class="filter-right">
			<?php echo $this->lists['select_author'];?>
		</li>
		<li class="filter-right">
			<?php echo $this->lists['select_item'];?>
		</li>
		<li class="filter-right">
			<?php echo $this->lists['select_state'];?>
		</li>
	</ul>

	<?php  if($this->pagination->total > 0) : ?>

	<table id="actionlist" class="list stripe">
	    <thead>
	        <tr>
	            <th class="checkbox">
					<input type="checkbox" class="check-all" />
	            </th>
	            <th class="author">
					<?php echo Text::_('Author'); ?>
	            </th>
	            <th class="comment">
					<?php echo Text::_('Comment'); ?>
	            </th>
	            <th class="comment-on">
	                <?php echo Text::_('Comment On'); ?>
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
	    <tbody id="table-body">
	    	<?php
				foreach ($this->comments as $comment) {
					$this->comment = $comment;
					echo $this->partial('row');
				}
			?>
	    </tbody>
	</table>

	<?php elseif($this->is_filtered) :

			$title   = Text::_('FILTER_NO_COMMENTS').'!';
			$message = null;
			echo $this->partial('message', compact('title', 'message'));

		else :

			$title   = Text::_('NO_COMMENTS_YET').'!';
			$message = Text::_('COMMENTS_MANAGER_DESCRIPTION');
			echo $this->partial('message', compact('title', 'message'));

		endif;
	?>

</div>

<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="changeapp" value="<?php echo $this->application->id; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#adminForm').BrowseComments();
	});
</script>

<?php echo ZOO_COPYRIGHT;
