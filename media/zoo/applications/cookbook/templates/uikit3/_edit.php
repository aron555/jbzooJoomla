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

?>

<div id="edit" class="uk-margin" style="display:none">
	<h3><?php echo Text::_('Edit comment'); ?></h3>

	<form class="uk-form" method="post" action="<?php echo $this->app->link(array('controller' => 'comment', 'task' => 'edit')); ?>">

			<div class="uk-form-row">
				<textarea name="content" rows="5" cols="80" ></textarea>
			</div>

			<div class="uk-form-row actions">
				<input class="uk-button uk-button-primary" name="submit" type="submit" value="<?php echo Text::_('Save comment'); ?>" accesskey="s"/>
				<a class="comment-cancelEdit" href="#edit"><?php echo Text::_('Cancel'); ?></a>
			</div>

			<input type="hidden" name="comment_id" value="0"/>
			<input type="hidden" name="redirect" value="<?php echo $this->redirect; ?>"/>
			<?php echo $this->app->html->_('form.token'); ?>
	</form>
</div>
