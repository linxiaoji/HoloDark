﻿﻿<?php
$blogOption = hdoptions::getopts();
/*
Template Name:博文格式：普通
*/
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Android 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
	<header class="entry-header">
                <h1 class="entry-title" itemprop="headline"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php android_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
<!--<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
            <rdf:Description
            rdf:about="<?php echo wp_get_shortlink(); ?>"
            dc:identifer="<?php echo get_permalink(); ?>"
            dc:title="<?php
        /*
       * Print the <title> tag based on what is being viewed.
       */
        global $page, $paged;
        wp_title('|', true, 'right');
        // Add the blog name.
        bloginfo('name');
        // Add the blog description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page()))
            echo " | $site_description";
        // Add a page number if necessary:
        if ($paged >= 2 || $page >= 2)
            echo ' | ' . sprintf(__('Page %s', 'HD'), max($paged, $page));
        ?>"
            trackback:ping="<?php bloginfo('pingback_url'); ?>" />
        </rdf:RDF>-->
	<div class="entry-content"  itemprop="articleBody">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'HD' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'HD' ) );
			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'HD' ) );
			if ( '' != $tag_list ) {
				$utility_text = __( 'Posted in %1$s and tagged %2$s by <a href="%6$s"  itemprop="author">%5$s</a>.', 'HD' );
			} elseif ( '' != $categories_list ) {
				$utility_text = __( 'Posted in %1$s by <a href="%6$s"  itemprop="author">%5$s</a>.', 'HD' );
			} else {
				$utility_text = __( 'Posted by <a href="%6$s"  itemprop="author">%5$s</a>.', 'HD' );
			}
			printf(
				$utility_text,
				$categories_list,
				$tag_list,
				esc_url( get_permalink() ),
				the_title_attribute( 'echo=0' ),
				get_the_author(),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
			);
		?>

		<?php if ($blogOption['show_autmeta'] ):  ?>
		<div id="author-info" class="vcard" itemprop="author"  itemscope itemtype="http://schema.org/Person">
			<div class="hiddenvc">
				<span class="fn" itemprop="name"><?php echo get_the_author_meta( 'display_name' );?></span>
				<span class="email" itemprop="email"><?php echo get_the_author_meta( 'user_email' );?></span>
				<span class="url" itemprop="url"><?php echo get_the_author_meta( 'user_url' );?></span>
            </div>
			<div id="author-description">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'android_author_bio_avatar_size', 68 ) ); ?>
				</div><!-- #author-avatar -->
				<h2><span>A</span><?php printf( __( 'bout %s', 'HD' ), get_the_author() ); ?></h2>
				<?php if ($blogOption['show_qr']) : ?>
				<div class="qrcode">
					<img src="<?php echo holodark_generate_qr(wp_get_shortlink()); ?>" width="160" height="160" alt="二维码" rel="nofollow noindex" />
				</div><!-- .qrcode -->
				<?php endif; ?> 
				<div id="author-description-content" itemprop="description"><?php the_author_meta( 'description' ); ?></div>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" class="url" itemprop="url">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'HD' ), get_the_author() ); ?>
					</a>
					<br/>
				</div><!-- #author-link	-->
			</div><!-- #author-description -->
		</div><!-- #author-info -->
		<?php endif; ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->