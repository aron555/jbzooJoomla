<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

$this->app->html->_('behavior.tooltip');

$this->app->html->_('formbehavior.chosen', '#categories');
$this->app->html->_('formbehavior.chosen', '#paramsprimary_category');

// add script
$this->app->document->addScript('assets:js/autosuggest.js');
$this->app->document->addScript('assets:js/item.js');
$this->app->document->addScript('assets:js/alias.js');
$this->app->document->addScript('assets:js/tag.js');

// filter output
OutputFilter::objectHTMLSafe($this->item, ENT_QUOTES, array('params', 'elements'));

// Keepalive behavior
$this->app->html->_('behavior.keepalive');

?>

<form class="item-edit" action="index.php" method="post" name="adminForm" id="adminForm" accept-charset="utf-8">

	<?php echo $this->partial('menu'); ?>

	<div class="box-bottom">
		<div class="col col-left width-60">

			<fieldset class="creation-form">
				<legend><?php echo Text::_('Details'); ?></legend>
				<div class="element element-name">
					<strong><?php echo Text::_('Name'); ?></strong>
					<div id="name-edit">
						<div class="row">
							<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->item->name; ?>" />
							<span class="message-name"><?php echo Text::_('Please enter valid name.'); ?></span>
						</div>
						<div class="slug">
							<span><?php echo Text::_('Slug'); ?>:</span>
							<a class="trigger" href="#" title="<?php echo Text::_('Edit Item Slug');?>"><?php echo $this->item->alias; ?></a>
							<div class="panel">
								<input type="text" name="alias" value="<?php echo $this->item->alias; ?>" />
								<input type="button" class="btn btn-small accept" value="<?php echo Text::_('Accept'); ?>"/>
								<a href="#" class="cancel"><?php echo Text::_('Cancel'); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php if ($this->item->canEditState()) : ?>
				<div class="element element-published">
					<strong><?php echo Text::_('Published'); ?></strong>
					<?php echo $this->lists['select_published']; ?>
				</div>
				<?php endif; ?>
				<div class="element element-searchable">
					<strong><?php echo Text::_('Searchable'); ?></strong>
					<?php echo $this->lists['select_searchable']; ?>
				</div>
				<div class="element element-comments">
					<strong><?php echo Text::_('Comments'); ?></strong>
					<?php echo $this->lists['select_enable_comments']; ?>
				</div>
				<?php if ($this->item->canManageFrontpage()) : ?>
				<div class="element element-frontpage">
					<strong><?php echo Text::_('Frontpage'); ?></strong>
					<?php echo $this->lists['select_frontpage']; ?>
				</div>
				<?php endif; ?>
				<div class="element element-categories">
					<strong><?php echo Text::_('Categories'); ?></strong>
					<?php echo $this->lists['select_categories']; ?>
				</div>
				<div class="element element-primary-category">
					<strong><?php echo Text::_('Primary Category'); ?></strong>
					<?php echo $this->lists['select_primary_category']; ?>
				</div>
				<?php
				foreach ($this->item->getElements() as $element) {

					if (!$element->canAccess() && !$this->user->authorise('core.admin')) {
						continue;
					}

					// trigger beforeEdit event
					$render = true;
					$this->app->event->dispatcher->notify($this->app->event->create($element, 'element:beforeedit', array('render' => &$render)));

					if ($render && $edit = $element->edit()) {
						$element->loadAssets();

						// set label
						$name = Text::_($element->config->get('name'));

						if ($description = $element->config->get('description')) {
							$description = ' class="editlinktip hasTip" title="'.Text::_($description).'"';
						}

						$html   = array();
						$html[] = '<div class="element element-'.$element->getElementType().'">';
						$html[] = '<strong'.$description.'>'.$name.'</strong>';
						$html[] = $edit;
						$html[] = '</div>';

						// trigger afterEdit event
						$this->app->event->dispatcher->notify($this->app->event->create($element, 'element:afteredit', array('html' => &$html, 'description' => $description, 'name' => $name)));

						echo implode("\n", $html);
					}
				}
				?>
			</fieldset>

		</div>

		<div class="col col-right width-40">

			<table width="100%" class="infobox">
				<?php if ($this->item->id) : ?>
				<tr>
					<td>
						<strong><?php echo Text::_('Item ID'); ?>:</strong>
					</td>
					<td>
						<?php echo $this->item->id; ?>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td>
						<strong><?php echo Text::_('Type'); ?></strong>
					</td>
					<td>
						<?php echo $this->item->getType()->name; ?>
						<input type="hidden" name="type" value="<?php echo $this->item->type; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo Text::_('State'); ?></strong>
					</td>
					<td>
						<?php echo $this->item->state > 0 ? Text::_('Published') : ($this->item->state < 0 ? Text::_('Archived') : Text::_('Draft Unpublished'));?>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo Text::_('Hits'); ?></strong>
					</td>
					<td>
						<?php echo $this->item->hits;?>
						<span <?php echo !$this->item->hits ? 'style="display: none; visibility: hidden;"' : null; ?>>
							<input name="reset_hits" type="button" class="btn btn-small" value="<?php echo Text::_('Reset'); ?>" onclick="Joomla.submitbutton('resethits');" />
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo Text::_('Created'); ?></strong>
					</td>
					<td>
						<?php echo $this->item->created == null ? Text::_('New item') : $this->app->html->_('date', $this->item->created, Text::_('DATE_FORMAT_LC2'), $this->app->date->getOffset()); ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo Text::_('Modified'); ?></strong>
					</td>
					<td>
						<?php echo $this->item->modified == null ? Text::_('Not modified') : $this->app->html->_('date', $this->item->modified, Text::_('DATE_FORMAT_LC2'), $this->app->date->getOffset()); ?>
					</td>
				</tr>
                <tr>
					<td>
						<strong><?php echo Text::_('Author'); ?></strong>
					</td>
					<td>
						<?php

							// author
							if ($author = $this->item->created_by_alias) {
								echo $author;
							} else if (($user = $this->app->user->get($this->item->created_by)) && $user->name) {
								echo $user->name;
							} else {
								echo Text::_('Guest');
							}

						?>
					</td>
				</tr>
			</table>

			<?php
			;

				// get item xml form
				$form = $this->app->parameterform->create(dirname(__FILE__).'/params.xml');

				// set details parameter
				$details = $this->app->parameter->create()
					->set('created_by', empty($this->item->created_by) ? $this->app->user->get()->id : 'NO_CHANGE')
					->set('access', $this->item->access)
					->set('created_by_alias', $this->item->created_by_alias)
					->set('created', $this->app->html->_('date', isset($this->item->created) ? $this->item->created : '', 'Y-m-d H:i:s', true))
					->set('publish_up', $this->app->html->_('date', $this->item->publish_up, 'Y-m-d H:i:s', true))
					->set('publish_down', $this->app->html->_('date', $this->item->publish_down, 'Y', true) <= 1969 || $this->item->publish_down == $this->app->database->getNullDate() ? Text::_('Never') : $this->app->html->_('date', $this->item->publish_down, 'Y-m-d H:i:s', true));

			?>

			<div id="parameter-accordion">
				<h3 class="toggler"><?php echo Text::_('Details'); ?></h3>
				<div class="content">
					<?php echo $form->setValues($details)->render('details'); ?>
				</div>
				<h3 class="toggler"><?php echo Text::_('Metadata'); ?></h3>
				<div class="content">
					<?php echo $form->setValues($this->params->get('metadata.'))->render('params[metadata]', 'metadata'); ?>
				</div>
				<?php $form = $this->application->getParamsForm()->setValues($this->params->get('content.')); ?>
				<?php if ($form->getParamsCount('item-content')) : ?>
					<h3 class="toggler"><?php echo Text::_('Content'); ?></h3>
					<div class="content">
						<?php echo $form->render('params[content]', 'item-content'); ?>
					</div>
				<?php endif; ?>
				<?php $form = $this->application->getParamsForm()->setValues($this->params->get('config.')); ?>
				<?php if ($form->getParamsCount('item-config')) : ?>
					<h3 class="toggler"><?php echo Text::_('Config'); ?></h3>
					<div class="content">
						<?php echo $form->render('params[config]', 'item-config'); ?>
					</div>
				<?php endif; ?>
				<?php $template = $this->application->getTemplate(); ?>
				<?php if ($template) : ?>
					<?php $form = $template->getParamsForm(true)->setValues($this->params->get('template.')); ?>
					<?php if ($form->getParamsCount('item')) : ?>
					<h3 class="toggler"><?php echo Text::_('Template'); ?></h3>
					<div class="content">
						<?php echo $form->render('params[template]', 'item'); ?>
					</div>
					<?php endif; ?>
				<?php else: ?>
					<h3 class="toggler"><?php echo Text::_('Template'); ?></h3>
					<div class="content">
						<em><?php echo Text::_('Please select a Template'); ?></em>
					</div>
				<?php endif; ?>
				<h3 class="toggler"><?php echo Text::_('Tags'); ?></h3>
				<div class="content">
					<div id="tag-area">
						<input type="text" value="<?php echo implode(', ', $this->item->getTags()); ?>" placeholder="<?php echo Text::_('Add new tag'); ?>" />
						<p><?php echo Text::_('Choose from the most used tags');?>:</p>
						<?php if (count($this->lists['most_used_tags'])) : ?>
						<div class="tag-cloud">
							<?php foreach ($this->lists['most_used_tags'] as $tag) :?>
								<a title="<?php echo $tag->items . ' ' . ($tag->items == 1 ? Text::_('item') : Text::_('items')); ?>"><?php echo $tag->name; ?></a>
							<?php endforeach;?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="hits" value="<?php echo $this->item->hits; ?>" />
<input type="hidden" name="changeapp" value="<?php echo $this->application->id; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#adminForm').EditItem();
		$('#name-edit').AliasEdit({ edit: <?php echo (int) $this->item->id; ?> });
		$('#name-edit').find('input[name="name"]').focus();
		$('#tag-area').Tag({ url: 'index.php?option=com_zoo&controller=item&format=raw&task=loadtags', addButtonText: '<?php echo Text::_('Add Tag'); ?>' });

		// Add here since on 3.0 the options are hardcoded in the constructor of the PHP method
		$('#categories, #paramsprimary_category').data('chosen').search_contains = true;
	});
</script>

<?php echo ZOO_COPYRIGHT;
