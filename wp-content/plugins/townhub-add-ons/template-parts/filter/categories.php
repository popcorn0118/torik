<?php
/* add_ons_php */
if(!isset($is_slider)) $is_slider = true;

                    $term_args = array(
                        'taxonomy' => 'listing_cat',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => (int)townhub_addons_get_option('cats_num'),
                    );
                    $term_args = apply_filters( 'cth_lcategories_tab_args', $term_args );
                    $listing_terms = get_terms( $term_args );
                    if ( ! empty( $listing_terms ) && ! is_wp_error( $listing_terms ) ){ ?>
                        <?php 
                        if($is_slider): ?>
                        <!-- category-carousel-wrap -->
                        <div class="category-carousel-wrap fl-wrap">
                            <div class="category-carousel fl-wrap full-height">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                        <?php else: ?>
                            <!-- categories-list-wrap -->
                            <div class="fl-wrap hc-item categories-list-wrap">
                        <?php endif; ?>
                                    <?php 
                                    $search_cats = array();
                                    if(is_tax('listing_cat')){
                                        $search_cats = array(get_queried_object_id());
                                    }else{
                                        if(isset($_GET['lcats'])&&is_array($_GET['lcats'])){
                                            $search_cats = array_filter($_GET['lcats']);
                                        } 
                                    }
                                    foreach ($listing_terms as $cat) {
                                        $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
                                        $act_cls = '';
                                        if(in_array($cat->term_id, $search_cats)) $act_cls = ' checket-cat';
                                        ?>
                                        <?php if($is_slider): ?>
                                        <!-- category-carousel-item -->
                                        <div class="swiper-slide">
                                        <?php endif; ?>
                                            <a class="category-carousel-item fl-wrap full-height<?php echo esc_attr( $act_cls );?>" href="<?php echo townhub_addons_get_term_link( $cat->term_id, 'listing_cat' ); ?>">
                                                <?php if($term_metas['featured']) echo wp_get_attachment_image( $term_metas['featured'], 'medium' ); ?>
                                                <?php echo $term_metas['icon'] != '' ? '<div class="category-carousel-item-icon '.$term_metas['color'].'"><i class="'.$term_metas['icon'].'"></i></div>' : ''; ?>
                                                <div class="category-carousel-item-container">
                                                    <div class="category-carousel-item-title"><?php echo $cat->name; ?></div>
                                                    <div class="category-carousel-item-counter"><?php echo sprintf(__( '%d listings', 'townhub-add-ons' ), $cat->count); ?></div>
                                                </div>
                                            </a>
                                        <?php if($is_slider): ?>
                                        </div>
                                        <!-- category-carousel-item end -->
                                        <?php endif; ?>
                                    <?php
                                    } ?>
                        <?php 
                        if($is_slider): ?>
                                    </div>
                                </div>
                            </div>
                            <!-- category-carousel-wrap end-->
                        </div>
                        <div class="catcar-scrollbar fl-wrap">
                            <div class="hs_init"></div>
                            <div class="cc-contorl">
                                <div class="cc-contrl-item cc-prev"><i class="fal fa-angle-left"></i></div>
                                <div class="cc-contrl-item cc-next"><i class="fal fa-angle-right"></i></div>
                            </div>
                        </div>
                        <?php else: ?>
                            
                            </div><!-- categories-list-wrap end -->
                        <?php endif; ?>                
                                    
                    <?php 
                    } ?>