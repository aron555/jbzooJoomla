<?php
/**
 * @package   ZOO Tag
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted access');

// include css
$zoo->document->addStylesheet('mod_zootag:tmpl/cloud/style.css');

$count = count($tags);

?>

<?php if ($count) : ?>

<ul class="zoo-tagcloud">
	<?php $i = 0; foreach ($tags as $tag) : ?>
	<li class="weight<?php echo $tag->weight; ?>">
		<a href="<?php echo Route::_($tag->href); ?>"><?php echo $tag->name; ?></a>
	</li>
	<?php $i++; endforeach; ?>
</ul>

<?php else : ?>
<?php echo Text::_('COM_ZOO_NO_TAGS_FOUND'); ?>
<?php endif;
