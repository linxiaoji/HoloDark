<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Android 1.0
 */
?>
<!DOCTYPE html>
<html <?php if(is_mobile()){echo 'class="is_mobile" ';} language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title><?php
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
        ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name');?> Feed RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js" type="text/javascript"></script>
    <?php
    /* We add some JavaScript to pages with the comment form
      * to support sites with threaded comments (when in use).
      */
    if (is_singular() && get_option('thread_comments'))
//        wp_enqueue_script('comment-reply'); 减少请求数

    /* Always have wp_head() just before the closing </head>
      * tag of your theme, or you will break many plugins, which
      * generally use this hook to add elements to <head> such
      * as styles, scripts, and meta tags.
      */

if(is_home()){
    eval("su_head();");} //hack SEO Ultimate
    wp_head();
   ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/Blog">
<!-- Header -->
<header id="header">
    <div class="lay_wrap clearfix" id="header-wrap">
        <div class="col-3 logo">
            <a href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
               rel="home"><span class="link-logo"></span><span class="blog-name" itemprop="name"><?php bloginfo('name'); ?></span></a>
            <div class="btn-quicknav" id="btn-quicknav">
                <a href="#" class="arrow-inactive">Quicknav</a>
                <a href="#" class="arrow-active">Quicknav</a>
            </div>
        </div>
        <?php
//list terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
        $categories = get_the_category(); //get all categories for this post
	if(!$categories){
	    $args = array(
		'number' => 7,
            	'orderby' => 'id',
          	'show_count' => 0,
          	'pad_counts' => 0,
         	'hierarchical' => 0,
          	'title_li' => '',
		'depth' => 1
        );
	}else{
        $args = array(
		'number' => 7,
		'orderby' => 'id',
		'show_count' => 0,
		'pad_counts' => 0,
		'hierarchical' => 0,
		'current_category' => $categories[0]->cat_ID,
		'title_li' => '',
		'depth' => 1
        );
	}
        ?>
        <ul class="nav-x col-9">
            <?php wp_list_categories($args); ?>
        </ul>
        <!-- New Search -->
        <div class="menu-container">
            <div class="moremenu">
                <div id="more-btn"></div>
            </div>
            <div class="morehover" id="moremenu">
                <div class="top"></div>
                <div class="mid">
                    <div class="header">Pages</div>
                    <ul>
                        <?php
                            wp_list_pages('title_li=');
                        ?>
                    </ul>
                    <div class="header">RSS</div>
                    <ul>
                        <li><a href="<?php bloginfo('rss2_url');?>" rel="alternate" type="application/rss+xml" title="<?php bloginfo('name');?> Feed RSS 2.0"  target="_blank">订阅<?php bloginfo('name'); ?></a></li>
                    </ul>
                </div>
                <div class="bottom"></div>
            </div>
            <?php get_search_form(); ?>
        </div>
  
     <!-- Expanded quicknav -->
        <div id="quicknav" class="col-9">
            <ul>
                <?php
                $categories = get_categories($args);
                foreach ($categories as $category) {
                    echo '<li class="' . $category->name . '"><ul>';
                    query_posts('showposts=5&cat=' . $category->term_id);
                    while (have_posts()) : the_post();
                        ?>
                        <li><a href="<?php the_permalink() ?>"
                               title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile;
                    wp_reset_query();
                    echo "</ul></li>";
                } ?>
            </ul>
        </div>
        <!-- /Expanded quicknav -->
    </div>
</header>
<!-- /Header -->
<div class="clearfix" id="body-content">
     <div class="lay_wrap">