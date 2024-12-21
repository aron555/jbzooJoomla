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

$url        = $this->pagination_link;
$pagination = $this->pagination;

?>

<?php if (!$pagination->getShowAll()) : ?>
<div class="pagination">

    <?php

    $html = '';

    if ($pagination->pages() > 1) {

        $range_start = max($pagination->current() - $pagination->range(), 1);
        $range_end = min($pagination->current() + $pagination->range() - 1, $pagination->pages());

        if ($pagination->current() > 1) {
            $link = $url;
            $html .= '<a class="first" href="' . Route::_($link) . '">' . Text::_('First') . '</a>';
            $link = $pagination->current() - 1 == 1 ? $url : $pagination->link($url, $pagination->name() . '=' . ($pagination->current() - 1));
            $html .= '<a class="previous" href="' . Route::_($link) . '">«</a>';
        }

        for ($i = $range_start; $i <= $range_end; $i++) {
            if ($i == $pagination->current()) {
                $html .= '<strong>' . $i . '</strong>';
            } else {
                $link = $i == 1 ? $url : $pagination->link($url, $pagination->name() . '=' . $i);
                $html .= '<a href="' . Route::_($link) . '">' . $i . '</a>';
            }
        }

        if ($pagination->current() < $pagination->pages()) {
            $link = $pagination->link($url, $pagination->name() . '=' . ($pagination->current() + 1));
            $html .= '<a class="next" href="' . Route::_($link) . '">»</a>';
            $link = $pagination->link($url, $pagination->name() . '=' . ($pagination->pages()));
            $html .= '<a class="last" href="' . Route::_($link) . '">' . Text::_('Last') . '</a>';
        }
    }

    echo $html;
    ?>
</div>
<?php endif;
