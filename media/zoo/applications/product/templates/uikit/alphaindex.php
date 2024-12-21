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

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-alphaindex'; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php

		// render categories
		if (!empty($this->selected_categories)) {
			$categoriestitle = Text::_('Categories starting with').' '.strtoupper($this->alpha_char);
			echo $this->partial('categories', compact('categoriestitle'));
		}

	?>

	<?php

		// render items
		if (count($this->items)) {
			$itemstitle = Text::_('Items starting with').' '.strtoupper($this->alpha_char);
			echo $this->partial('items', compact('itemstitle'));
		}

	?>

</div>
