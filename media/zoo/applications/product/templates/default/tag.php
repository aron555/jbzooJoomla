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

// include assets css/js
if (strtolower(substr($GLOBALS['app']->getTemplate(), 0, 3)) != 'yoo') {
	$this->app->document->addStylesheet('assets:css/reset.css');
}
$this->app->document->addStylesheet($this->template->resource.'assets/css/zoo.css');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div id="yoo-zoo" class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-tag'; ?>">

	<?php if ($this->params->get('template.show_alpha_index')) : ?>
		<?php echo $this->partial('alphaindex'); ?>
	<?php endif; ?>

	<?php

		// render items
		if (count($this->items)) {
			$itemstitle = Text::_('Items tagged with').' '.strtoupper($this->tag);
			echo $this->partial('items', compact('itemstitle'));
		}

	?>

</div>
