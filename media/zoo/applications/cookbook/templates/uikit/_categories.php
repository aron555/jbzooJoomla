<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// remove empty categories
$selected_categories = $this->selected_categories;
if (!$this->params->get('config.show_empty_categories', false)) {
	$selected_categories = array_filter($selected_categories, function($category) { return $category->totalItemCount(); });
}

// init vars
$i       = 0;
$columns = array();
$column  = 0;
$row     = 0;
$rows    = ceil(count($selected_categories) / $this->params->get('template.categories_cols'));

// create columns
foreach ($selected_categories as $category) {

	if ($this->params->get('template.categories_order')) {
		// order down
		if ($row >= $rows) {
			$column++;
			$row  = 0;
			$rows = ceil((count($selected_categories) - $i) / ($this->params->get('template.categories_cols') - $column));
		}
		$row++;
		$i++;
	} else {
		// order across
		$column = $i++ % $this->params->get('template.categories_cols');
	}

	if (!isset($columns[$column])) {
		$columns[$column] = '';
	}

	$columns[$column] .= $this->partial('category', compact('category'));
}

// render columns
$count = count($columns);
if ($count) {

	echo '<div class="uk-grid uk-grid-divider" data-uk-grid-margin data-uk-grid-match>';
	for ($j = 0; $j < $count; $j++) {
		echo '<div class="uk-width-small-1-2 uk-width-medium-1-'.$count.'">'.$columns[$j].'</div>';
	}
	echo '</div>';
}