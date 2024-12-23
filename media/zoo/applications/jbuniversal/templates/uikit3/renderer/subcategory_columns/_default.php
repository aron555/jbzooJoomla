<?php
/**
 * JBZoo Application
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Application
 * @license    GPL-2.0
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/JBZoo
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$this->app->jbdebug->mark('layout::subcategory_columns::start');

if ($vars['count']) {
    $i     = 0;
    $count = $vars['count'];
    //$vars['cols_num']

    echo '<div class="subcategories subcategory-col-' . $vars['cols_num'] . '">


';

    $rowSubcategories = array_chunk($vars['objects'], $vars['cols_num']);

    foreach ($rowSubcategories as $row) {

        echo '<div class="jsHeightFixRow uk-grid-match  subcategory-row uk-grid-small uk-child-width-expand@s uk-text-center uk-child-width-1-' . $vars['cols_num'].'@s" uk-grid>';

        $j = 0;
        $i++;

        foreach ($row as $subcategory) {

            $classes = array(
               // 'inner-subcategory-column uk-card uk-card-default uk-card-body',
                //'uk-child-width-1-' . $vars['cols_num'].'@m'
            );

            $first = ($j == 0) ? $classes[] = 'first' : '';
            $last  = ($j == $count - 1) ? $classes[] = 'last' : '';
            $j++;

            $isLast = $j % $vars['cols_num'] == 0 && $vars['cols_order'] == 0;

            if ($isLast) {
                $classes[] = 'last';
            }

            echo '<div class="' . implode(' ', $classes) . '">' .
                '<div class="subcategory-inner uk-card uk-card-default uk-card-body">' . $subcategory . '</div>' .
                '</div>';
        }

        echo '</div>';
    }

    echo '<hr /></div>';
}

$this->app->jbdebug->mark('layout::subcategory_columns::finish');