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

<?php if ($this->checkPosition('top')) : ?>
	<?php echo $this->renderPosition('top', array('style' => 'uikit_block')); ?>
<?php endif; ?>
<div class="uk-grid" uk-grid>

	<div class="uk-width-3-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-last@m'; ?>">
		<?php if ($this->checkPosition('title')) : ?>
		<h1 class="uk-h1"><?php echo $this->renderPosition('title'); ?></h1>
		<?php endif; ?>

		<?php if ($this->checkPosition('subtitle')) : ?>
		<p class="uk-text-large">
			<?php echo $this->renderPosition('subtitle', array('style' => 'comma')); ?>
		</p>
		<?php endif; ?>

		<?php if ($this->checkPosition('description')) : ?>
			<?php echo $this->renderPosition('description', array('style' => 'uikit_block')); ?>
		<?php endif; ?>

		<?php if ($this->checkPosition('address') || $this->checkPosition('contact')) : ?>
		<div class="uk-grid" uk-grid>

			<?php if ($this->checkPosition('address')) : ?>
			<div class="uk-width-1-2@m">
				<h3><?php echo Text::_('Address'); ?></h3>
				<ul class="uk-list">
					<?php echo $this->renderPosition('address', array('style' => 'uikit_list')); ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if ($this->checkPosition('contact')) : ?>
			<div class="uk-width-1-2@m">
				<h3><?php echo Text::_('Contact'); ?></h3>
				<ul class="uk-list">
					<?php echo $this->renderPosition('contact', array('style' => 'uikit_list')); ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
		<?php endif; ?>

		<?php if ($this->checkPosition('employee')) : ?>
			<?php echo $this->renderPosition('employee', array('style' => 'uikit_grid_multirow')); ?>
		<?php endif; ?>
	</div>

	<?php if ($this->checkPosition('sidebar')) : ?>
	<div class="uk-width-1-4@m <?php if($view->params->get('template.item_sidebar_alignment') == 'left') echo 'uk-flex-first@m'; ?>">
		<?php echo $this->renderPosition('sidebar', array('style' => 'uikit_panel')); ?>
	</div>
	<?php endif; ?>

</div>

<?php if ($this->checkPosition('bottom')) : ?>
	<?php echo $this->renderPosition('bottom', array('style' => 'uikit_block')); ?>
<?php endif;
