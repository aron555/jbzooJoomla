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

?>

<span class="jsRemoveFromCart jsRemoveElement uk-button-default uk-button-default-danger uk-button-default-small jbprice-buttons-remove">
    <i uk-icon="icon: trash-o"></i>
    <?php echo JText::_($params->get('remove_label', 'JBZOO_ELEMENT_PRICE_BUTTONS_REMOVE_LABEL_DEFAULT')); ?>
</span>
