<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Including fallback code for the placeholder attribute in the search field.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

if ($width)
{
	$moduleclass_sfx .= ' ' . 'mod_search' . $module->id;
	$css = 'div.mod_search' . $module->id . ' input[type="search"]{ width:auto; }';
	JFactory::getDocument()->addStyleDeclaration($css);
	$width = ' size="' . $width . '"';
}
else
{
	$width = '';
}

$searchword = JRequest::getString('searchword');

$zoo   = App::getInstance('zoo');
$areas = (array)$zoo->jbrequest->get('areas', array());
$area  = count($areas) ? (int)$areas[0] : 0;



?>
<div id="verh-poisk" class="search<?php echo $moduleclass_sfx ?>">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
		<?php



			$output = ' ';
			$output .= '<input name="searchword" '
            . 'maxlength="' . $maxlength . '" '
            . 'value="' . $zoo->jbrequest->get('searchword') . '" '
            . 'class="jsAutocompleteSearch col-lg-9 col-sm-9 col-xs-6 " '
            . 'type="text" '
            . 'placeholder="' . $text . '" />';

			if ($button) :
				if ($imagebutton) :
					$btn_output = ' <input type="image" alt="' . $button_text . '" class="button" src="/images/i/pb.png" onclick="this.form.searchword.focus();"/>';
				else :
					$btn_output = ' <button class="col-lg-3 col-sm-3 col-xs-6 btn btn-primary butpoisk" onclick="this.form.searchword.focus();">' . $button_text . '</button>';
				endif;

				switch ($button_pos) :
					case 'top' :
						$output = $btn_output . '<br />' . $output;
						break;

					case 'bottom' :
						$output .= '<br />' . $btn_output;
						break;

					case 'right' :
						$output .= $btn_output;
						break;

					case 'left' :
					default :
						$output = $btn_output . $output;
						break;
				endswitch;

			endif;

			echo $output;
		?>
		<input type="hidden" name="limit" value="20" />
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo (int)$mitemid; ?>" />
	</form>
</div>
