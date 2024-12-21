<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->item->alias; ?>">
<?php if ($this->item->canEdit()) : ?>
    <?php $edit_link = $this->app->route->submission($this->item->getApplication()->getItemEditSubmission(), $this->item->type, null, $this->item->id, 'itemedit'); ?>
    <div class="uk-align-right">
        <a href="<?php echo Route::_($edit_link); ?>" title="<?php echo Text::_('Edit Item'); ?>" class="item-icon edit-item"><?php echo Text::_('Edit Item'); ?></a>
    </div>
<?php endif; ?>

	<?php echo $this->renderer->render('item.full', array('view' => $this, 'item' => $this->item)); ?>

	<?php if ($this->application->isCommentsEnabled() && ($this->item->isCommentsEnabled() || $this->item->getCommentsCount(1))) : ?>
		<?php echo $this->app->comment->renderComments($this, $this->item); ?>
	<?php endif; ?>

</div>
