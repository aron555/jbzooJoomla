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

$align     = $this->app->jbitem->getMediaAlign($item, $layout);
$bootstrap = $this->app->jbbootstrap;
$rowClass  = $bootstrap->getRowClass();
?>



<div class="col">
    <?php if ($this->checkPosition('image')) : ?>
    
            <div class="item-image text-center d-flex pull-<?php echo $align; ?>">
                <?php echo $this->renderPosition('image'); ?>
            </div>

          
       
    <?php endif; ?>
</div>

<?php if ($this->checkPosition('title')) : ?>
    <h4 class="item-title fs-6 text-center"><?php echo $this->renderPosition('title'); ?></h4>
<?php endif; ?>

<?php if ($this->checkPosition('price')) : ?>
<div class="col">
     <?php echo $this->renderPosition('price', array('style' => 'block')); ?>
</div>
<?php endif; ?>


<!--
    <div class="<?php echo $rowClass; ?>">
        <div class="<?php echo $bootstrap->gridClass(6); ?>">
            <div class="item-price">
               
            </div>
        </div>
    </div>
-->


<?php if ($this->checkPosition('text')) : ?>
    <div class="item-text <?php echo $rowClass; ?>">
        <div class="<?php echo $bootstrap->gridClass(12); ?>">
            <?php echo $this->renderPosition('text', array('style' => 'block')); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->checkPosition('quick-view')) : ?>
    <div class="item-quick-view <?php echo $rowClass; ?>">
        <div class="<?php echo $bootstrap->gridClass(12); ?>">
            <?php echo $this->renderPosition('quick-view', array('style' => 'block')); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->checkPosition('buttons')) : ?>
    <div class="<?php echo $rowClass; ?> item-buttons clearfix">
        <div class="<?php echo $bootstrap->gridClass(12); ?>">
            <?php echo $this->renderPosition('buttons'); ?>
        </div>
    </div>
<?php endif; ?>
