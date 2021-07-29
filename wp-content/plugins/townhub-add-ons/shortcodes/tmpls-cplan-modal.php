<?php
/* add_ons_php */
$payment_page_id = townhub_addons_get_option('payment_page');
?>
<div class="ctb-modal-wrap ctb-modal" id="ctb-cplan-modal">
    <div class="ctb-modal-holder">
        <div class="ctb-modal-inner modal_main">
            <div class="ctb-modal-close"><i class="fa fa-times"></i></div>
            <div class="ctb-modal-title"><?php _e( 'Change ', 'townhub-add-ons' );?><span><?php esc_html_e( 'plan', 'townhub-add-ons' ); ?></span></div>
            <div class="ctb-modal-content">
                
                <form action="<?php echo esc_url(get_permalink($payment_page_id));?>" method="post" name="change-plan-form">

                    <div class="select-plans-wrap custom-form">
                    <?php 
                    $args = array(
                        'post_type'     =>  'lplan', 
                        'meta_key'      => '_cth_price',
                        'orderby'       =>  'meta_value_num',
                        'order'         =>  'ASC',
                        'posts_per_page' => -1, // no limit

                        'post_status'   => 'publish',
                    );

                    // The Query
                    $posts_query = new WP_Query( $args );
                    if($posts_query->have_posts()){
                        $idx = 0;
                        while($posts_query->have_posts()){ 
                            $posts_query->the_post(); 
                            ?>
                            <div class="plan-item">
                                <label for="plan_id_field_<?php echo esc_attr( $idx );?>" class="radio inline">
                                    <input id="plan_id_field_<?php echo esc_attr( $idx );?>" type="radio" name="plan_id" value="<?php echo esc_attr( get_the_ID() );?>"<?php if($idx == 0) echo ' required="required"'; ?>>
                                    <span><?php the_title(); ?></span>
                                </label>
                                <div class="plan-price-wrap">
                                    <span class="price"><?php echo townhub_addons_get_price_formated( get_post_meta( get_the_ID(), '_cth_price', true ) ); ?></span>
                                    <span class="interval"><?php echo get_post_meta( get_the_ID(), '_cth_interval', true ); ?></span>
                                    <span class="period"><?php echo get_post_meta( get_the_ID(), '_cth_period', true ); ?></span>
                                </div>
                            
                            </div>
                            <?php
                            $idx++;
                        }
                        // end while
                    }
                    /* Restore original Post Data 
                     * NB: Because we are using new WP_Query we aren't stomping on the 
                     * original $wp_query and it does not need to be reset with 
                     * wp_reset_query(). We just need to set the post data back up with
                     * wp_reset_postdata().
                     */
                    wp_reset_postdata();
                    ?>
                    </div>
                    <input type="hidden" name="listing_id" value="">
                    <br>
                    <input class="btn color-bg" type="submit" name="change_plan" value="<?php esc_attr_e( 'Change Plan', 'townhub-add-ons' ); ?>">

                </form>
                    
            </div>
            <!-- end modal-content -->
        </div>
    </div>
</div>
<!-- end modal --> 