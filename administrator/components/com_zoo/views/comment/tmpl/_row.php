<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

// get comment author
$author = $this->comment->getAuthor();

// filter output
OutputFilter::objectHTMLSafe($author, ENT_QUOTES, array('app', 'application'));

?>
<tr class="comment-row <?php if ($this->comment->state == Comment::STATE_UNAPPROVED) echo 'unapproved' ?>">
	<td class="checkbox">
		<input type="checkbox" name="cid[]" value="<?php echo $this->comment->id; ?>" />
	</td>
	<td class="author">
		<div class="avatar">
			<?php if (!$author->isGuest()) : ?><div class="authentication <?php echo $author->getUserType(); ?>"></div><?php endif; ?>
			<?php echo $author->getAvatar(40); ?>
		</div>
		<div class="details">
			<span class="name"><?php echo $author->name ? $author->name : Text::_('Anonymous'); ?></span>
			<?php if ($author->url) : ?>
				<br /><a class="url" href="<?php echo $author->url; ?>" title="<?php echo $author->url; ?>"><?php echo $author->url; ?></a>
			<?php endif; ?>
			<?php if ($author->email) : ?>
				<br /><a class="email" href="mailto:<?php echo $author->email; ?>"><?php echo $author->email; ?></a>
			<?php endif; ?>
		</div>
	</td>
	<td class="comment">
		<div class="created">
			<?php if ($this->comment->state == Comment::STATE_UNAPPROVED) : ?>
				<span><?php echo Text::_('NOT APPROVED'); ?></span>
			<?php endif; ?>
			<?php echo $this->app->html->_('date', $this->comment->created, Text::_('DATE_FORMAT_LC2'), $this->app->date->getOffset()); ?>
		</div>
		<p><?php echo $this->app->comment->filterContentOutput($this->comment->content); ?></p>
		<div class="actions-wrapper">
			<span class="actions-links">&rsaquo;
				<a href="#" class="reply"><?php echo Text::_('Reply'); ?></a> |
				<a href="#" class="edit"><?php echo Text::_('Edit'); ?></a> |
				<?php if ($this->comment->state == Comment::STATE_UNAPPROVED) : ?>
					<span class="approve"><a href="#"><?php echo Text::_('Approve'); ?></a> | </span>
					<span class="spam"><a href="#"><?php echo Text::_('Spam'); ?></a> | </span>
				<?php endif; ?>
				<?php if ($this->comment->state == Comment::STATE_APPROVED) : ?>
					<span class="unapprove"><a href="#"><?php echo Text::_('Unapprove'); ?></a> | </span>
					<span class="spam"><a href="#"><?php echo Text::_('Spam'); ?></a> | </span>
				<?php endif; ?>
				<?php if ($this->comment->state == Comment::STATE_SPAM) : ?>
					<span class="no-spam"><a href="#"><?php echo Text::_('No Spam'); ?></a> | </span>
				<?php endif; ?>
				<span class="delete"><a href="#"><?php echo Text::_('Delete'); ?></a></span>
			</span>
		</div>
	</td>
	<td class="comment-on">
		<?php $item = $this->comment->getItem(); ?>
		<?php $link = $this->app->link(array('controller' => 'item', 'task' => 'edit', 'cid[]' => $item->id)); ?>
		<a href="<?php echo $link; ?>"><?php echo $item->name; ?></a>
	</td>
</tr>
