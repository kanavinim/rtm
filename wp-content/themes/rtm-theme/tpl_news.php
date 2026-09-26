<?php
/*
Template Name: news page
*/
?>
<?php get_header(); ?>


<div class="content">


            <div class="container row">
                <div class="left_block sticky-sidebar col-md-2" style="margin-left: -20px;">

</div>
<div class="col-md-9" style="margin-bottom: 40px;">
	<h1>
	Новости
	</h1>
    <?php
// запрос
$wpb_all_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>-1)); ?>

<?php if ( $wpb_all_query->have_posts() ) : ?>

<ul>

    <!-- the loop -->
    <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
        <div style="margin-bottom: 10px;" onclick="window.open('<?php the_permalink(); ?>','_self')">

            <div class="news-img"> <?php echo get_the_post_thumbnail()?></div>
        <div class="without-sides-padding" >
            <div  class="without-sides-padding" style="display: grid;">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <a><?php the_date();?></a>
            </div>
            <button class="news-btn"><a href="<?php the_permalink(); ?>" style="font-size: 14px;">Подробнее</a></button>
        </div>
        </div>

    <?php endwhile; ?>
    <!-- end of the loop -->

</ul>
    <?php wp_reset_postdata(); ?>

<?php else : ?>
    <p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
<?php endif; ?>

</div>
</div>
</div>


<?php get_footer(); ?>