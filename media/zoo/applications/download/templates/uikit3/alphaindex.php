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

// add additional stylesheet
$this->app->document->addStylesheet('assets:css/uikit3-zoo.css');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-alphaindex'; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php if (!empty($this->selected_categories)) : ?>
		<div class="uk-margin">
			<h1 class="uk-h1"><?php echo Text::_('Categories starting with').' '.strtoupper($this->alpha_char); ?></h1>
			<?php echo $this->partial('categories'); ?>
		</div>
	<?php endif; ?>

	<?php

		// render items
		if (count($this->items)) {
			$title = Text::_('Items starting with').' '.strtoupper($this->alpha_char);
			$subtitle = '';
			echo $this->partial('items', compact('title', 'subtitle'));
		}

	?>

</div>