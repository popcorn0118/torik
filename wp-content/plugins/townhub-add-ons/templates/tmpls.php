<?php
/* add_ons_php */
?>
<script type="text/template" id="tmpl-load-listings">
    <div class="listings-loader">
        <div class="lload-icon-wrap">
            <i class="fal fa-spinner fa-pulse fa-3x"></i>
        </div>
        <div class="lload-text-wrap"><?php esc_html_e('Loading', 'townhub-add-ons');?></div>
    </div>
</script>

<script type="text/template" id="tmpl-no-results">
    <?php townhub_addons_get_template_part('template-parts/search-no');?>
</script>
<script type="text/template" id="tmpl-map-info">
    <# 
    var one_review_text = "<?php echo esc_html_x( '{REVIEW} review', 'map popup card', 'townhub-add-ons' ); ?>",
        other_review_text = "<?php echo esc_html_x( '{REVIEW} reviews', 'map popup card', 'townhub-add-ons' ); ?>";
    #>
    <div class="map-popup-wrap">
        <div class="map-popup">
            <div class="infoBox-close"><i class="fal fa-times"></i></div>
            <a href="{{data.url}}" class="listing-img-content fl-wrap">
                <?php if( townhub_addons_get_option('map_card_hide_status') !='yes' ): ?>
                <div class="infobox-status wkhour-{{data.status}} {{data.status}}">{{data.statusText}}</div>
                <?php endif; ?>
                <img src="{{data.thumbnail}}" alt="{{data.title}}">
                <# if( data.rating.rating ){ #>
                <div class="card-popup-raining map-card-rainting" data-rating="{{data.rating.rating}}" data-stars="<?php echo esc_attr(townhub_addons_get_option('rating_base')); ?>">
                    <span class="map-popup-reviews-count">( <# print( data.rating.count > 1 ? other_review_text.replace('{REVIEW}', data.rating.count) : one_review_text.replace('{REVIEW}', data.rating.count)  ) #> )</span>
                </div>
                <# } #>
            </a>
            <div class="listing-content">
                <div class="listing-content-item fl-wrap">
                    {{{data.cat}}}
                    <div class="listing-title fl-wrap">
                        <h4><a href="{{data.url}}">{{{data.title}}}</a></h4>
                        <# if(data.address){ #>
                        <div class="map-popup-location-info"><i class="fas fa-map-marker-alt"></i>{{{data.address}}}</div>
                        <# } #>
                        <?php if( townhub_addons_get_option('map_card_hide_author') !='yes' ): ?>
                        <div class="map-popup-author"><?php esc_html_e( 'By ', 'townhub-add-ons' );?><a href="{{data.author_url}}"><span>{{{data.author_name}}}</span></a></div>
                        <?php endif; ?>
                        
                        
                    </div>
                    <div class="map-popup-footer">
                        <a href="{{data.url}}" class="main-link"><?php esc_html_e('Details ', 'townhub-add-ons');?><i class="fal fa-long-arrow-right"></i></a>
                    

                        <?php if( is_user_logged_in() && townhub_addons_get_option('map_provider') == 'googlemap' ): ?>
                        <# if(data.bookmarked){ #>
                            <a href="javascript:void(0);" class="infowindow_wishlist-btn" data-id="{{data.ID}}"><i class="fas fa-heart"></i></a>
                        <# }else{ #>
                            <a href="#" class="infowindow_wishlist-btn bookmark-listing-btn" data-id="{{data.ID}}" data-map="1"><i class="fal fa-heart"></i></a>
                        <# } #>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-feature-search">
<# _.each(data.features, function(fea){ #>
<?php townhub_addons_get_template_part('templates-inner/feature-search');?>
<# }) #>
</script>
<script type="text/template" id="tmpl-filter-subcats">
<# _.each(data.subcats, function(subcat){ #>
<?php townhub_addons_get_template_part('templates-inner/subcats-filter');?>
<# }) #>
</script>
<div id="ol-popup" class="ol-popup">
    <a href="#" id="ol-popup-closer" class="ol-popup-closer"></a>
    <div id="ol-popup-content"></div>
</div>