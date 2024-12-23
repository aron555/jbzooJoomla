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

$this->app->document->addScript('assets:js/alias.js');
$this->app->document->addScript('assets:js/type.js');

// filter output
OutputFilter::objectHTMLSafe($this->type, ENT_QUOTES);
?>

<form class="menu-has-level3" action="index.php" method="post" name="adminForm" id="adminForm" accept-charset="utf-8">

<?php echo $this->partial('menu'); ?>

<div class="box-bottom">

	<fieldset class="creation-form">
		<legend><?php echo Text::_('Details'); ?></legend>
		<div class="element element-name">
			<strong><?php echo Text::_('Name'); ?></strong>
			<div id="name-edit">
				<div class="row">
					<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo htmlspecialchars(isset($this->type->name) ? $this->type->name : '' , ENT_QUOTES, 'UTF-8'); ?>" />
					<span class="message-name"><?php echo Text::_('Please enter valid name.'); ?></span>
				</div>
				<div class="slug">
					<span><?php echo Text::_('Slug'); ?>:</span>
					<a class="trigger" href="#" title="<?php echo Text::_('Edit Type Slug');?>"><?php echo $this->type->id; ?></a>
					<div class="panel">
						<input type="text" name="identifier" value="<?php echo $this->type->id; ?>" />
						<input type="button" class="btn btn-small accept" value="<?php echo Text::_('Accept'); ?>"/>
						<a href="#" class="cancel"><?php echo Text::_('Cancel'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="group" value="<?php echo $this->group; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->type->id; ?>" />
<?php echo $this->app->html->_('form.token'); ?>

</form>

<script type="text/javascript">
	jQuery(function($) {
		$('#adminForm').EditType();
		$('#name-edit').AliasEdit({ edit: <?php echo (int) $this->edit; ?>, edit_field_name: 'identifier', force_safe: 1 });
		$('#name-edit').find('input[name="name"]').focus();
	});
</script>

<?php echo ZOO_COPYRIGHT;
