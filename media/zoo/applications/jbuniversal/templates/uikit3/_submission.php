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

$this->app->document->addScript('libraries:jquery/jquery-ui.custom.min.js');
$this->app->document->addStylesheet('libraries:jquery/jquery-ui.custom.css');
$this->app->document->addScript('libraries:jquery/plugins/timepicker/timepicker.js');
$this->app->document->addStylesheet('libraries:jquery/plugins/timepicker/timepicker.css');
$this->app->document->addStylesheet('assets:css/submission.css');
$this->app->document->addScript('assets:js/submission.js');
$this->app->document->addScript('assets:js/placeholder.js');
$this->app->document->addScript('assets:js/item.js');

if ($this->submission->showTooltip()) {
    $this->app->html->_('behavior.tooltip');
}

$formAction = JRoute::_($this->app->route->submission($this->submission, $this->type->id, null, $this->item->id, $this->redirectTo));

?>

<?php if ($this->errors) :

    $msg = count($this->errors) > 1 ? JText::_('Oops. There were errors in your submission.') : JText::_('Oops. There was an error in your submission.');
    $msg .= ' '.JText::_('Please take a look at all highlighted fields, correct your data and try again.'); ?>

    <div class="uk-alert" data-uk-alert>
        <a href="" class="uk-alert-close uk-close"></a>
        <?php echo $msg; ?>
    </div>

<?php endif; ?>

<form id="item-submission" class="uk-form" action="<?php echo $formAction; ?>"
      method="post" name="submissionForm" accept-charset="utf-8" enctype="multipart/form-data">

    <?php
        echo $this->renderer->render($this->layout_path, array(
            'item'       => $this->item,
            'submission' => $this->submission
        ));

        // Captcha support
        if ($this->captcha) {
            $this->app->html->_('behavior.framework');
            echo $this->captcha->display('captcha', 'captcha', 'captcha');
        }
    ?>

    <div class="uk-alert"><?php echo JText::_('REQUIRED_INFO'); ?></div>

    <div class="uk-margin uk-text-center jbform-actions">

        <button type="submit" id="submit-button" class="uk-button-default uk-button-default-large uk-button-default-success">
            <i uk-icon="icon: check"></i>
            <?php echo $this->item->id ? JText::_('Save') : JText::_('Submit Item'); ?>
        </button>

        <?php if ($this->cancelUrl) : ?>
            <a href="<?php echo JRoute::_($this->cancelUrl); ?>" id="cancel-button" class="uk-button-default uk-button-default-large uk-button-default-danger">
                <i uk-icon="icon: close"></i>
                <?php echo JText::_('Cancel'); ?>
            </a>
        <?php endif; ?>
    </div>

    <input type="hidden" name="option" value="<?php echo $this->app->component->self->name; ?>" />
    <input type="hidden" name="controller" value="submission" />
    <input type="hidden" name="task" value="save" />

    <?php echo $this->app->html->_('form.token'); ?>

</form>