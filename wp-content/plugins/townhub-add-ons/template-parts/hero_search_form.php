<?php
/* add_ons_php */
if(!isset($ltypes)){
    $p_args = array(
        'post_type'             => 'listing_type',
        'posts_per_page'        => -1,
        'orderby'               => 'date',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'suppress_filters'      => false,
        'fields'                => 'ids',
    );

    $ltypes = get_posts( $p_args );
}
?>
<div class="main-search-form-wrap clearfix">
    <?php if( $ltypes ): ?>
    <!-- main-search-input-tabs-->
    <div class="main-search-input-tabs  tabs-act fl-wrap tabs-wrapper ltypes-count-<?php echo count( (array)$ltypes );?>">
        <ul class="tabs-menu change_bg no-list-style">
        <?php foreach ( $ltypes as $key => $ltid ) { 
            $ltid = trim($ltid);
            $tabbg = '';
            $featured_image = get_post_meta( $ltid, ESB_META_PREFIX . 'featured_image', true );
            if(!empty($featured_image['id'])) $tabbg = townhub_addons_get_attachment_thumb_link( $featured_image['id'], 'bg-image' );
            ?>
            <li class="lfilter-tabitem<?php if($key == 0) echo ' current';?>"><a href="#lfilter-tab-<?php echo $ltid;?>" data-bgtab="<?php echo $tabbg;?>"><?php echo get_the_title( $ltid ); ?></a></li>
        <?php } ?>

        </ul>
        <!--tabs -->                       
        <div class="tabs-container fl-wrap">
            
            <?php foreach ( $ltypes as $key => $ltid ) { 
                $ltid = trim($ltid);
            ?>
            <!--tab -->
            <div class="tab lfilter-tab-<?php echo $ltid;?>">
                <div id="lfilter-tab-<?php echo $ltid;?>" class="tab-content<?php if($key == 0) echo ' first-tab';?>">
                    <div class="main-search-input-wrap fl-wrap">
                        

                        <form role="search" method="get" action="<?php echo esc_url(home_url( '/' ) ); ?>" class="list-search-hero-form list-search-form-js">
                            <?php 
                                echo townhub_addons_azp_parser_listing( $ltid , 'filter_herosec');
                            ?>
                            <?php if( !empty($ltid) && get_post_meta( $ltid, ESB_META_PREFIX.'filter_by_type', true ) ) echo '<input type="hidden" name="ltype" value="'.$ltid.'">'; ?>
                        </form>
                    </div>
                </div>
            </div>
            <!--tab end-->
            
            <?php } ?>

            
                                          
        </div>
        <!--tabs end-->
    </div>
    <!-- main-search-input-tabs end-->
<?php endif; ?>
</div>

    


    