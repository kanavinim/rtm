<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;



/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
    <div class="tab-text">
        <span class="info-tab">Информация о продукте</span>
        <span class="reviews-tab">Отзывы</span> 
        <span class="compability-tab">Совместимость</span>        
    </div>
    <div  class="pip-scroll-indicator" tabindex="0" role="scrollbar" aria-controls="pip-carousel__default" aria-orientation="horizontal" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0"><span class="pip-scroll-indicator__bar-wrapper"><span id="tab-line" class="pip-scroll-indicator__bar" style="width: 34%; transform: translateX(0%);"></span></span></div>
    <div id="description">
            <?php
                $product_description = get_post($item['product_id'])->post_content;
                echo $product_description;
            ?>
    </div>
    <div class="woocommerce-tabs wc-tabs-wrapper">
        <ul class="tabs wc-tabs" role="tablist">

            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                    <a href="#tab-<?php echo esc_attr( $key ); ?>">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
            <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                <?php
                if ( isset( $product_tab['callback'] ) ) {
                    call_user_func( $product_tab['callback'], $key, $product_tab );
                }
                ?>
            </div>
        <?php endforeach; ?>

        <?php do_action( 'woocommerce_product_after_tabs' ); ?>
    </div>


<?php

if ( ! comments_open() ) {
    return;
}

?>

<div id="compability">
    <p>"Этот раздел сайта находится в разработке"</p>
    <p>"Контент для вкладки Совместимость"</p>
    <?php
      echo get_post_meta('id поста',"_prim",true);
    ?>
</div>
<div id="reviews-tab-box">
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="woocommerce-Reviews-title">
            <?php
            $count = $product->get_review_count();
            if ( $count && wc_review_ratings_enabled() ) {
                /* translators: 1: reviews count 2: product name */
                $reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
                echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
            } else {
                esc_html_e( 'Reviews', 'woocommerce' );
            }
            ?>
        </h2>

        <?php if ( have_comments() ) : ?>
            <ol class="commentlist">
                <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
            </ol>

            <?php
            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                echo '<nav class="woocommerce-pagination">';
                paginate_comments_links(
                    apply_filters(
                        'woocommerce_comment_pagination_args',
                        array(
                            'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                            'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                            'type'      => 'list',
                        )
                    )
                );
                echo '</nav>';
            endif;
            ?>
        <?php else : ?>
            <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
        <?php endif; ?>
    </div>

    <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
        <div id="review_form_wrapper">
            <div id="review_form">
                <?php
                $commenter    = wp_get_current_commenter();
                $comment_form = array(
                    /* translators: %s is product title */
                    'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                    /* translators: %s is product title */
                    'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
                    'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
                    'title_reply_after'   => '</span>',
                    'comment_notes_after' => '',
                    'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
                    'logged_in_as'        => '',
                    'comment_field'       => '',
                );

                $name_email_required = (bool) get_option( 'require_name_email', 1 );
                $fields              = array(
                    'author' => array(
                        'label'    => __( 'Name', 'woocommerce' ),
                        'type'     => 'text',
                        'value'    => $commenter['comment_author'],
                        'required' => $name_email_required,
                    ),
                    'email'  => array(
                        'label'    => __( 'Email', 'woocommerce' ),
                        'type'     => 'email',
                        'value'    => $commenter['comment_author_email'],
                        'required' => $name_email_required,
                    ),
                );

                $comment_form['fields'] = array();

                foreach ( $fields as $key => $field ) {
                    $field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
                    $field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

                    if ( $field['required'] ) {
                        $field_html .= '&nbsp;<span class="required">*</span>';
                    }

                    $field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

                    $comment_form['fields'][ $key ] = $field_html;
                }

                $account_page_url = wc_get_page_permalink( 'myaccount' );
                if ( $account_page_url ) {
                    /* translators: %s opening and closing link tags respectively */
                    $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
                }

                if ( wc_review_ratings_enabled() ) {
                    $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
                        <option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
                        <option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
                        <option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
                        <option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
                        <option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
                        <option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
                    </select></div>';
                }

                $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

                comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                ?>
            </div>
        </div>
    <?php else : ?>
        <p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
    <?php endif; ?>

    <div class="clear"></div>
</div>
 </div>

<?php endif; ?>
<script>
    $(document).ready(function(){
        var reviewsTabBox = document.getElementById('reviews-tab-box');
        var compability = document.getElementById('compability');
        var description = document.getElementById('description');
        var tabLine = document.getElementById('tab-line');

        var infoTab = document.getElementsByClassName('info-tab')[0];
        var compabilityTab = document.getElementsByClassName('compability-tab')[0];
        var descriptionTab = document.getElementsByClassName('reviews-tab')[0];

        $('.info-tab').on('click', function(){
            infoTab.style.color = "#000";
            compabilityTab.style.color = "#6d6d6d";
            descriptionTab.style.color = "#6d6d6d";
            tabLine.style.transform = "translateX(0%)";
            reviewsTabBox.style.display = "none";
            compability.style.display = "none";
            description.style.display = "block";

        });
        $('.compability-tab').on('click', function(){
            infoTab.style.color = "#6d6d6d";
            compabilityTab.style.color = "#000";
            descriptionTab.style.color = "#6d6d6d";
            tabLine.style.transform = "translateX(200%)";
            reviewsTabBox.style.display = "none";
            description.style.display = "none";
            compability.style.display = "block";

        });
        /**
        $('.reviews-tab').on('click', function(){
            infoTab.style.color = "#6d6d6d";
            compabilityTab.style.color = "#6d6d6d";
            descriptionTab.style.color = "#000";
            tabLine.style.transform = "translateX(200%)";
            compability.style.display = "none";
            description.style.display = "none";
            reviewsTabBox.style.display = "block";
        });
        **/
    });

    $(document).ready(function(){
        document.getElementsByClassName('info-tab')[0].style.color = "#000";
        document.getElementById('reviews-tab-box').style.display = "none";
        document.getElementById('compability').style.display = "none";
    });
</script>