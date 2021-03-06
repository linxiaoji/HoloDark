<?php
$is_ad = false; // 庙的广告,默认关闭
add_theme_support( 'post-thumbnails', array( 'post', 'movie' ) );
add_theme_support( 'post-formats', array( 'aside', 'gallery','chat','link','image','quote','status','video'));
function console($log){
    echo <<<EOF
<script>console.log("$log")</script>
EOF;

}
function duoshuo_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com"),"gravatar.duoshuo.com",$avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'duoshuo_avatar', 10, 3 );

if (!function_exists('android_content_nav')
) :
    /**
     * Display navigation to next/previous pages when applicable
     */
    function android_content_nav($nav_id)
    {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) : ?>
        <nav id="<?php echo $nav_id; ?>">
            <h3 class="assistive-text"><?php _e('文章导航', 'HD'); ?></h3>

            <div
                class="nav-previous"><?php next_posts_link(__(sprintf('<span class="meta-nav">&larr;</span> %s','Older Posts'), 'HD')); ?></div>
            <div
                class="nav-next"><?php previous_posts_link(__(sprintf('%s <span class="meta-nav">&rarr;</span>','Newer Posts'), 'HD')); ?></div>
        </nav><!-- #nav-above -->
        <?php endif;
    }
endif; // android_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Android 1.0
 * @return string|bool URL or false when no link is present.
 */
function android_url_grabber()
{
    if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches))
        return false;

    return esc_url_raw($matches[1]);
}


if (!function_exists('android_comment')
) :
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own android_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Android 1.0
     */
    function android_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
	<li class="post pingback">
		<p><?php _e('Pingback:', 'HD'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'HD'), '<span class="edit-link">', '</span>'); ?></p>
                    <?php
                break;
            default :
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
                        <footer class="comment-meta">
                            <div class="comment-author vcard">
                                <?php
                                $avatar_size = 68;
                                if ('0' != $comment->comment_parent)
                                    $avatar_size = 39;

                                echo get_avatar($comment, $avatar_size);

                                /* translators: 1: comment author, 2: date and time */
                                printf(__('%1$s on %2$s <span class="says">said:</span>', 'HD'),
                                    sprintf('<span class="fn" itemprop="creator">%s</span>', get_comment_author_link()),
                                    sprintf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                        esc_url(get_comment_link($comment->comment_ID)),
                                        get_comment_time('c'),
                                        /* translators: 1: date, 2: time */
                                        sprintf(__('%1$s at %2$s', 'HD'), get_comment_date(), get_comment_time())
                                    )
                                );
                                ?>

                                <?php edit_comment_link(__('Edit', 'HD'), '<span class="edit-link">', '</span>'); ?>
                            </div>
                            <!-- .comment-author .vcard -->

                            <?php if ($comment->comment_approved == '0') : ?>
                            <em class="comment-awaiting-moderation"><?php _e('评论审核中', 'HD'); ?></em>
                            <br/>
                            <?php endif; ?>

                        </footer>

                        <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>

                        <div class="reply">
                            <?php comment_reply_link(array_merge($args, array('reply_text' => __(sprintf('%s <span>&darr;</span>','评论'), 'HD'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div>
                        <!-- .reply -->
                    </article>
                    <!-- #comment-## -->

                    <?php
                break;
        endswitch;
    }
endif; // ends check for android_comment()

if (!function_exists('android_posted_on')
) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     * Create your own android_posted_on to override in a child theme
     *
     * @since Android 1.0
     */
    function android_posted_on()
    {
        printf(__('<meta itemprop="datePublished" content="%8$s"><span class="sep">Posted on </span><time class="entry-date" datetime="%3$s">%4$s</time><span class="by-author"> <span class="sep"> by </span> <span class="author vcard" itemprop="author"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'HD'),
            esc_url(get_permalink()),
            esc_attr(get_the_time()),
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_attr(sprintf(__('View all posts by %s', 'HD'), get_the_author())),
            get_the_author(),
			get_the_time('Y-m-d')
        );
    }
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Android 1.0
 */
function android_body_classes($classes)
{

    if (function_exists('is_multi_author') && !is_multi_author())
        $classes[] = 'single-author';

    if (is_singular() && !is_home() && !is_page_template('showcase.php') && !is_page_template('sidebar-page.php'))
        $classes[] = 'singular';

    return $classes;
}

add_filter('body_class', 'android_body_classes');

function article_nav($content)
{
    /**
     * 文章目录
     */

    $matches = array();
    $ul_li = '';

    $r = "/<h2>(.*?)<\\/h2>/ims";
    if (preg_match_all($r, $content, $matches)) {
        foreach ($matches[1] as $num =>$title) {
            $titles=preg_replace("/<[^<>]*>/ims","",$title);
            $titles=preg_replace("/\s+/im"," ",$titles);
            $content = str_replace($matches[0][$num], '<h2 id="article_nav-' . $num . '">' . $title . '</h2>', $content);
            $ul_li .= '<li><a href="#article_nav-' . $num . '" title="' . $titles . '">' . $titles . "</a></li>\n";
        }
        if (is_singular()) {
            $content = '<textarea id="smart-nav-containter" class="ui-hide"><li id="smart-nav-recent" class="nav-section"><div class="nav-section-header"><a href="javascript:vold(0)">Article Nav</a></div><ul>'
                . $ul_li . '<li><a href="#respond">发表评论</a></li></ul></li></textarea>' . $content;
        }
    }


    return $content;
}

add_filter("the_content", "article_nav");


// A trim function to remove the last character of a utf-8 string
// by following instructions on http://en.wikipedia.org/wiki/UTF-8
// dotann

function utf8_trim($str)
{   
    $hex = '';
    for ($i = strlen($str) - 1; $i >= 0; $i -= 1) {
        $hex .= ' ' . ord($str[$i]);
        $ch = ord($str[$i]);
        if (($ch & 128) == 0) return (substr($str, 0, $i));
        if (($ch & 192) == 192) return (substr($str, 0, $i));
    }
    return ($str . $hex);
}

function android_excerpt($excerpt)
{
    //$tmp_excerpt = substr($excerpt, 0, 255);
    //return utf8_trim($tmp_excerpt) . '... ';
    return utf8_trim($excerpt) . '... ';
}

add_filter('the_excerpt', 'android_excerpt');
add_filter('the_excerpt_rss', 'android_excerpt');


function android_related()
{
    $posttags = get_the_tags();
    $postid = get_the_ID();
    if ($posttags) {
        foreach ($posttags as $tag) {
            query_posts('showposts=5&tag=' . $tag->name);
            while (have_posts()) : the_post();
                if ($postid != get_the_ID()) {
                    ?>
                <li><a href="<?php the_permalink() ?>"
                       title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                <?php
                }
            endwhile;
            wp_reset_query();
            break;
        }
    }
}


//ajax-comments

add_action('comment_post', 'ajaxcomments_stop_for_ajax', 20, 2);
function ajaxcomments_stop_for_ajax($comment_ID, $comment_status)
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //If AJAX Request Then
        switch ($comment_status) {
            case '0':
                //notify moderator of unapproved comment
                wp_notify_moderator($comment_ID);
            case '1': //Approved comment
                echo "success";
                $commentdata =& get_comment($comment_ID, ARRAY_A);
                $post =& get_post($commentdata['comment_post_ID']); //Notify post author of comment
                if (get_option('comments_notify') && $commentdata['comment_approved'] && $post->post_author != $commentdata['user_ID'])
                    wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
                break;
            default:
                echo "error";
        }
        exit;
    }
}

//comment mail
function android_mailtocommente_get_email($comments){
    $temp =array();
    foreach ($comments as $comment){
        $name = $comment->comment_author;
        if(!array_key_exists($name,$temp)){
            $email = $comment->comment_author_email;
            $temp["$name"] = $email;
        }
    }
    return $temp;
}
function android_mailtocommenter_get_names($content){
    $content = preg_replace('/<a\shref="#comment-[0-9]+">/s','',$content);
    $content = preg_replace('/<a\shref="#comment-([0-9])+"\srel="nofollow">/s','',$content);
    $content = preg_replace('/<a\srel="nofollow"\shref="#comment-([0-9])+">/s','',$content);
    $names  = explode(' ',$content);
    $output = array();
    foreach($names as $name){
        $name = $name;
        $number = substr_count($name,'@');
        if ($number >0 ){
            $length = strlen($name);
            $pos = strrpos($name,'@')+1;
            $n = substr($name,$pos,$length);
            $output["$n"] = $n;
        }
    }
    return $output;
}
function android_mailtocommenter_filter($comment,$username){
    global $wpdb;

    $contents[0] = 'Your comment on [%blog_name%] just been replied by %comment_author%';
    $contents[1] ='Hello, %user%.<br/>Your comment on 《<a href="%post_link%">%post_title%</a>》just been replied by（%comment_author%）. Why not check it rightnow. ^_^<br/><div style="padding:5px;border:1px solid #888;">Your comment:<br />%your_comment%<div style="margin-left:5px;margin-right:5px;padding:5px;border:1px solid #ccc;">   New reply:<br />%reply_comment%<br /><div align="right">%comment_time%</div></div></div><div style="margin-top:10px;padding-bottom:10px;border-bottom:1px solid #ccc;"><a href="%comment_link%" target="_blank">View reply</a>, or click <a href="mailto:%admin_email%">here</a> to send mail to Admin</div><div align="right">DO Not reply this mail</div><a href="%blog_link%">%blog_name%</a>，Welcom to subscribe to <a href="%rss_link%">%rss_name%</a>.';
    $comment_id = $comment['comment_ID'];
    $post_id = $comment['comment_post_ID'];
    $post = get_post($post_id);
    $admin_email = get_option('admin_email');
    $blog_name = get_option('blogname');
    $blog_link = get_option('home');
    $comment_author = $comment['comment_author'];
    $post_link =  get_permalink($post_id);
    $comment_link = $post_link."#comment-$comment_id";
    $comment_time = $comment['comment_date'];
    $post_title =  $post->post_title;
    $reply_comment = $comment['comment_content'];
    $your_comment = $wpdb->get_var("SELECT $wpdb->comments.comment_content FROM $wpdb->comments WHERE $wpdb->comments.comment_post_ID='$post_id' AND $wpdb->comments.comment_author='$username' ORDER BY $wpdb->comments.comment_date DESC");
    $index = 0;
    foreach ($contents as $content){
        $filter = $content;
        $filter= str_replace("%admin_email%",$admin_email,$filter);
        $filter= str_replace("%blog_name%",$blog_name,$filter);
        $filter= str_replace("%blog_link%",$blog_link,$filter);
        $filter= str_replace("%comment_author%",$comment_author,$filter);
        $filter= str_replace("%comment_link%",$comment_link,$filter);
        $filter= str_replace("%comment_time%",$comment_time,$filter);
        $filter= str_replace("%your_comment%",$your_comment,$filter);
        $filter= str_replace("%post_link%",$post_link,$filter);
        $filter= str_replace("%post_title%",$post_title,$filter);
        $filter= str_replace("%reply_comment%",$reply_comment,$filter);
        $filter= str_replace("%rss_name%","RSS",$filter);
        $filter= str_replace("%rss_link%",get_bloginfo_rss('rss2_url'),$filter);
        $filter= str_replace("%user%",$username,$filter);
        $output[$index]= $filter;
        $index++;
    }
    return $output;
}
function android_mailtocommenter_send_email($to,$subject,$message){
    $blogname = get_option('blogname');
    $charset = get_option('blog_charset');
    $headers  = "From: $blogname \n" ;
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html;charset=\"$charset\"\n";
    $to = strtolower($to);
    return @wp_mail($to, $subject, $message, $headers);
}
function android_mailtocommenter($cid){
    global $wpdb;
    $cid = (int)$cid;
    $commentdata = get_commentdata($cid,1,false);
    $owner_email = $commentdata['comment_author_email'];
    $post_id = (int)$commentdata['comment_post_ID'];
    $comments = get_approved_comments($post_id);
    $commentcontent = $commentdata['comment_content'];
    $output = android_mailtocommenter_get_names($commentcontent);
    if (!$output) return;
    $mails = android_mailtocommente_get_email($comments);
    $n = array();
    $admin_email = get_option('admin_email');
    $result = 0;
    foreach ($output as $name){
        if ((array_key_exists($name,$mails)) and ($mails["$name"]!=$owner_email)){
            $to = $mails["$name"];
            $filter = android_mailtocommenter_filter($commentdata,$name);
            $subject =$filter[0];
            $message = $filter[1];
            $message = apply_filters('comment_text', $message);
            if(android_mailtocommenter_send_email($to,$subject,$message)){
                $result++;
            }
            $n["$name"] = $name;
        }
    }

    if ($result>0){
        $subject = "CC. $subject";
        $n = implode(',',$n);
        $n = "<br/>This comment has been sent to {$n}.<br/>";
        $m = $n.'Backup copy sent to admin<br/>'.$message;
        $to = strtolower(get_option('admin_email'));
        android_mailtocommenter_send_email($to,$subject,$m);
    }
}
add_action('comment_post', create_function('$cid', 'return android_mailtocommenter($cid);'));

/**
 * 判断是否移动终端
 */

function is_mobile()
{
    if (stristr($_SERVER['HTTP_USER_AGENT'], 'android') || stristr($_SERVER['HTTP_USER_AGENT'], 'iphone')||
stristr($_SERVER['HTTP_USER_AGENT'], 'mobile')||
stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')||
stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
        return true;
    } else {
        return false;
    }
}
function holodark_the_title($thetitle)
{
    return preg_replace('~<([a-z]+?)\s+?.*?>~i','<$1>',($thetitle));
}
add_filter('the_title','holodark_the_title');

function holodark_generate_qr($value, $errorCorrectionLevels="M", $matrixPointSizes="4")
{
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'tempqr'.DIRECTORY_SEPARATOR;
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
   
    $errorCorrectionLevel="M";
    if (in_array($errorCorrectionLevels, array('L','M','Q','H')))
         $errorCorrectionLevel = $errorCorrectionLevels;
     $matrixPointSize = 4;
     $matrixPointSize = min(max((int)$matrixPointSizes, 1), 10);
     $filename = $PNG_TEMP_DIR.'qr'.md5($value.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
     if (!file_exists($filename)){include "phpqrcode/phpqrcode.php";
             QRcode::png($value,  $filename, $errorCorrectionLevel, $matrixPointSize);
      }
     return  get_template_directory_uri().'/tempqr/'.'qr'.md5($value.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
}
function holodark_init_sidebar() {
  register_sidebar( array (
  'name' => 'Static',
  'id' => 'static',
  'before_widget' => '<li id="%1$s">',
  'after_widget' => '</li>',
  'before_title' => '<h2>',
  'after_title' => '</h2>',
  ) );
  register_sidebar( array (
  'name' => 'Fix',
  'id' => 'fix',
  'before_widget' => '<li id="%1$s">',
  'after_widget' => '</li>',
 'before_title' => '<h2>',
 'after_title' => '</h2>',
  ) );
  register_sidebar( array (
  'name' => 'Footer',
  'id' => 'footer',
  'before_widget' => '<div id="%1$s">',
  'after_widget' => '</div>',
 'before_title' => '<span>',
 'after_title' => '</span>',
  ) );
}
function is_sidebar_active( $index ){
  global $wp_registered_sidebars;
  $widgetcolums = wp_get_sidebars_widgets();
  if ($widgetcolums[$index]) return true;
  return false;
}
function holodark_l18n()
{
	load_theme_textdomain('HD', get_template_directory() . '/languages');
 }
 require_once(get_template_directory() . '/theme-updates/theme-update-checker.php'); 
$HD_update_checker = new ThemeUpdateChecker(
	'HoloDark', 
	'http://im.librazy.org/holodark/info.json' 
);
add_action( 'init', 'holodark_init_sidebar' );
add_action('after_setup_theme', 'holodark_l18n');
remove_action ('wp_head', 'wp_generator');
remove_action( 'wp_head', 'rsd_link' );   
remove_action( 'wp_head', 'wlwmanifest_link'); 
remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );


class hdoptions {
	static function getopts() {
		$options = get_option('HoloDark_options');
		if (!is_array($options)) {
			$options['show_qr'] = '1';
			$options['show_autmeta']  = '1';
			$options['logo_URI'] = '';
			update_option('HoloDark_options', $options);
		}
		return $options;
	}

	static function init() {
		if(isset($_POST['holodark_save'])) {
			$options = hdoptions::getopts();
			$options['show_qr'] = !(!($_POST['show_qr']));
			$options['show_autmeta'] = !(!($_POST['show_autmeta']));
			$options['logo_URI'] = $_POST['logo_URI'];
			$options['updated'] = true;
			update_option('HoloDark_options', $options);
		} else {
			hdoptions::getopts();
		}
		add_theme_page("HoloDark 主题设置", "HoloDark 主题设置", 'edit_themes', basename(__FILE__), array('hdoptions', 'display'));
	}

	static function display() {
		$options = hdoptions::getopts();
?>
<form action="#" method="post" enctype="multipart/form-data" name="holodark_form" id="holodark_form">
<div class="wrap">
	<h2>HoloDark 主题设置</h2>
	<?php  if($options['updated']):echo '<div class="updated"><p><strong>' .'设置成功' . '</strong></p></div>';$options['updated']=false;update_option('HoloDark_options', $options);endif;?>
		<h3>显示设置</h3>
		<div class="settings">
			<label for="show_autmeta">显示作者信息：</label>
			<input id="show_autmeta" name="show_autmeta" type="checkbox" <?php if($options['show_autmeta'])echo 'checked="checked"'?> />
			<span class="description">选中则启用显示作者信息</span>
		</div>
		<div class="settings">
			<label for="show_qr">显示QR码：（需显示作者信息）</label>
			<input id="show_qr" name="show_qr" type="checkbox" <?php if($options['show_qr'])echo 'checked="checked"'?>/>
			<span class="description">选中则启用自动生成QR码</span>
		</div>
		<div class="settings">
			<label for="show_qr">Logo：</label>
			<input id="logo_URI" name="logo_URI" type="text" value="<?php echo $options['logo_URI']?>" />
			<span class="description">左上角Logo地址</span>
		</div>
		<!-- 提交按钮 -->
		<p class="submit">
			<input type="submit" name="holodark_save" class="button button-primary" value="更新设置" />
		</p>
</div>
</form>
<?php
	}
}

//登记初始化方法
add_action('admin_menu', array('hdoptions', 'init'));

//引用
$blogOption = hdoptions::getopts();

?>