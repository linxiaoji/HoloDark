<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Android 1.0
 */
?>
<div  id="sidebar-right" class="wrap">
<ul id="sidebar">
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>
 <li id="placeholder">
  <h2>placeholder</h2>
  <p>placeholder</p>
 </li>
<?php endif; ?>
</ul>
</div>
</div><!-- #main -->
<footer id="footer" class="wrap" role="contentinfo">
    <div class="lay_wrap">
        <?php do_action('android_credits'); ?>
        <?php bloginfo('description'); ?>.
        <a href="<?php echo esc_url(__('http://wordpress.org/', 'android')); ?>"
           title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'android'); ?>"
           target="_blank"><?php printf(__('%s', 'android'), 'WordPress'); ?></a>
        &amp;&amp; <a href="http://ooxx.me/theme-android.orz" title="Android Developer Style Theme" target="_blank">Android</a>
	 &amp;&amp; <a href="http://im.librazy.org/wordpress-holodark/" title="HoloDark Theme" target="_blank">HoloDark</a>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
<script type="text/javascript">
    var home_url="<?php echo esc_url(home_url('/')); ?>";
    var is_mobile="<?php if(is_mobile()){echo 'true';}?>";
</script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/js/android.js"></script>
</body>
</html>