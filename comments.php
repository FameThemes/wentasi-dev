<?php function ft_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>

		<article id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">

			<div class="comment-avatar span2">
				<?php echo get_avatar($comment,$size='90'); ?>
			</div>

			<div class="comment-content span10 right">

			<header class="comment-header clearfix">
				<div class="comment-meta">
					<?php printf(__('<cite class="fn">%s</cite>', 'ft'), get_comment_author_link()) ?>
					<time datetime="<?php echo comment_date('c') ?>"><?php _e('on', 'ft'); ?> <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s', 'ft'), get_comment_date(),  get_comment_time()) ?> <?php comment_time('H:i:s'); ?></a></time>
				</div>
			</header>

			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="notice">
					<p class="bottom"><?php  _e('Your comment is awaiting moderation.', 'ft'); ?></p>
          		</div>
			<?php endif; ?>
			
				<section class="comment-text clearfix">
					<?php comment_text() ?>
					<?php edit_comment_link(__('(Edit)', 'ft'), '', '') ?>
				</section>

				<div class="reply-rate clearfix">
					<div class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
				</div>

			</div>
			
		</article>

	</li>

<?php } ?>

<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'ft'));

	if ( post_password_required() ) { ?>
	<section id="comments">
		<div class="notice">
			<p class="bottom"><?php  _e('This post is password protected. Enter the password to view comments.', 'ft'); ?></p>
		</div>
	</section>
	<?php
		return;
	}
?>
<?php // You can start editing here. Customize the respond form below ?>
<?php if ( have_comments() ) : ?>
	<section id="comments">
		<div class="comment-title">
			<h3><?php comments_popup_link(__('0 Comments', 'ft'), __('1 Comment', 'ft'), '% '.__('Comments', 'ft')); ?> <?php  _e('to', 'ft'); ?> <?php the_title(); ?></h3>
		</div>
		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=ft_comments'); ?>
		</ol>
		<footer>
			<nav id="comments-nav">
				<div class="comments-previous"><?php previous_comments_link( __( '&larr; Older comments', 'ft' ) ); ?></div>
				<div class="comments-next"><?php next_comments_link( __( 'Newer comments &rarr;', 'ft' ) ); ?></div>
			</nav>
		</footer>
	</section>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( comments_open() ) : ?>
	<?php else : // comments are closed ?>
	<section id="comments">
		<div class="notice">
			<p class="bottom"><?php  _e('Comments are closed.', 'ft'); ?></p>
		</div>
	</section>
	<?php endif; ?>
<?php endif; ?>
<?php comment_form(); ?>