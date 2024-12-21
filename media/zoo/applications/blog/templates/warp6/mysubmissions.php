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
$this->app->document->addStylesheet($this->template->resource.'assets/css/style.css');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div id="system" class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->submission->alias; ?>">

	<div class="mysubmissions">

		<h1 class="headline"><?php echo Text::_('My Submissions'); ?></h1>

		<p><?php echo sprintf(Text::_('Hi %s, here you can edit your submissions and add new submission.'), $this->user->name); ?></p>

		<?php

			echo $this->partial('mysubmissions');

		?>

	</div>

</div>
