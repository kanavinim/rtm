<?php
/**
 * Template used to display post content on single pages.
 *
 * @package storefront
 */

?>
<?php echo get_the_post_thumbnail( $page->ID, 'images'); ?>
        <div style="height: 20px;">

        </div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
   <div class="news-detail">
    	<?php
    	do_action( 'storefront_single_post_top' );

    	/**
    	 * Functions hooked into storefront_single_post add_action
    	 *
    	 * @hooked storefront_post_header          - 10
    	 * @hooked storefront_post_content         - 30
    	 */
    	do_action( 'storefront_single_post' );

    	/**
    	 * Functions hooked in to storefront_single_post_bottom action
    	 *
    	 * @hooked storefront_post_nav         - 10
    	 * @hooked storefront_display_comments - 20
    	 */
    	do_action( 'storefront_single_post_bottom' );
    	?>
   </div>
<script src="/wp-content/themes/rtm-theme/js/slider.js"></script>
</article><!-- #post-## -->
