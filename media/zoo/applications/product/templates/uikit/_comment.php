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

// set author name
$author->name = $author->name ? $author->name : Text::_('Anonymous');

?>
<li>
	<div id="comment-<?php echo $comment->id; ?>" class="uk-comment comment <?php if ($author->isJoomlaAdmin()) echo 'uk-comment-byadmin'; ?>">

		<div class="uk-comment-header">

			<?php if ($params->get('avatar', 0)) : ?>
				<div class="uk-comment-avatar"><?php echo $author->getAvatar(50); ?></div>
			<?php endif; ?>

			<?php if ($author->url) : ?>
				<h4 class="uk-comment-title"><a href="<?php echo Route::_($author->url); ?>" title="<?php echo $author->url; ?>" rel="nofollow"><?php echo $author->name; ?></a></h4>
			<?php else: ?>
				<h4 class="uk-comment-title"><?php echo $author->name; ?></h4>
			<?php endif; ?>

			<div class="uk-comment-meta">
				<?php echo $this->app->html->_('date', $comment->created, $this->app->date->format(Text::_('DATE_FORMAT_COMMENTS')), $this->app->date->getOffset()); ?>
				| <a href="#comment-<?php echo $comment->id; ?>">#</a>
			</div>

		</div>

		<div class="uk-comment-body">

			<p class="content"><?php echo $this->app->comment->filterContentOutput($comment->content); ?></p>

			<?php if ($comment->getItem()->isCommentsEnabled()) : ?>
				<p><a class="reply" href="#" rel="nofollow"><?php echo Text::_('Reply'); ?></a>
				<?php if ($comment->canManageComments()) : ?>
					<?php echo ' | '; ?>
					<a class="edit" href="#" rel="nofollow"><?php echo Text::_('Edit'); ?></a>
					<?php echo ' | '; ?>
					<?php if ($comment->state != Comment::STATE_APPROVED) : ?>
						 <a href="<?php echo 'index.php?option=com_zoo&controller=comment&task=approve&comment_id='.$comment->id; ?>" rel="nofollow"><?php echo Text::_('Approve'); ?></a>
					<?php else: ?>
						<a href="<?php echo 'index.php?option=com_zoo&controller=comment&task=unapprove&comment_id='.$comment->id; ?>" rel="nofollow"><?php echo Text::_('Unapprove'); ?></a>
					<?php endif; ?>
					<?php echo ' | '; ?>
					<a href="<?php echo 'index.php?option=com_zoo&controller=comment&task=spam&comment_id='.$comment->id; ?>" rel="nofollow"><?php echo Text::_('Spam'); ?></a>
					<?php echo ' | '; ?>
					<a href="<?php echo 'index.php?option=com_zoo&controller=comment&task=delete&comment_id='.$comment->id; ?>" rel="nofollow"><?php echo Text::_('Delete'); ?></a>
				<?php endif; ?>
				</p>
			<?php endif; ?>

			<?php if ($comment->state != Comment::STATE_APPROVED) : ?>
				<div class="uk-alert"><?php echo Text::_('COMMENT_AWAITING_MODERATION'); ?></div>
			<?php endif; ?>

		</div>

	</div>

	<?php if ($comment->hasChildren()) : ?>
	<ul>
		<?php
		foreach ($comment->getChildren() as $comment) {
			echo $this->partial('comment', array('level' => $level, 'comment' => $comment, 'author' => $comment->getAuthor(), 'params' => $params));
		}
		?>
	</ul>
	<?php endif ?>

</li>
