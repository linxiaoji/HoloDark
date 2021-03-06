<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Android 1.0
 */

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    if (have_posts()) : ?>
        [
    <?php while (have_posts()) : the_post(); ?>
        ["<?php the_title()?>","<?php the_permalink()?>"],
        <?php endwhile; ?>
[]]
    <?php else : ?>
    []
    <?php endif;
} else {
    get_header(); ?>
<?php get_sidebar(); ?>
<section id="primary">
    <div id="content" role="main">

        <?php if (have_posts()) : ?>

        <header class="page-header">
            <h1 class="page-title"><?php printf(__('%s 的搜索结果', 'HD'), '<span>' . get_search_query() . '</span>'); ?></h1>
        </header>

        <?php android_content_nav('nav-above'); ?>

        <?php /* Start the Loop */ ?>
        <?php while (have_posts()) : the_post(); ?>

            <?php
            /* Include the Post-Format-specific template for the content.
                            * If you want to overload this in a child theme then include a file
                            * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                            */
            get_template_part('content', get_post_format());
            ?>

            <?php endwhile; ?>

        <?php android_content_nav('nav-below'); ?>

        <?php else : ?>

        <article id="post-0" class="post no-results not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e('Nothing Found', 'HD'); ?></h1>
            </header>
            <!-- .entry-header -->
            <div class="entry-content">
                <p><?php _e('神马都米有，呵呵', 'HD'); ?></p>
                <h2><?php printf('Recent Posts');?></h2>
	   </div>   <!-- .entry-content -->
         </article><!-- #post-0 -->	<?query_posts('posts_per_page=5');?>
	
        <?php /* Start the Loop */ ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            /* Include the Post-Format-specific template for the content.
                            * If you want to overload this in a child theme then include a file
                            * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                            */
            get_template_part('content', get_post_format());
            ?>

            <?php endwhile; ?>


        <?php endif; ?>

    </div>
    <!-- #content -->
</section><!-- #primary -->

<?php get_footer();
} ?>