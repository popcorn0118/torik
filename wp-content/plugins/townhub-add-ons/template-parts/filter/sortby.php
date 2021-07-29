<?php
/* add_ons_php */
?>
<!-- price-opt-->
<div class="price-opt flex-items-center">
    <span class="price-opt-title"><?php _e( 'Sort by:', 'townhub-add-ons' ); ?></span>
    <div class="listsearch-input-item">
        <select id="lfilter-orderby" data-placeholder="<?php esc_attr_e( 'Popularity', 'townhub-add-ons' ); ?>" class="chosen-select no-search-select" name="morderby">
            <option value=""><?php esc_html_e( 'Default',  'townhub-add-ons' );?></option>
            <option value="most_viewed"><?php esc_html_e( 'Popularity',  'townhub-add-ons' );?></option>
            <option value="most_liked"><?php esc_html_e( 'Most Liked',  'townhub-add-ons' );?></option>
            <option value="highest_rated"><?php esc_html_e( 'Most Rated',  'townhub-add-ons' );?></option>
            <option value="price_low"><?php esc_html_e( 'Price: low to high',  'townhub-add-ons' );?></option>
            <option value="price_high"><?php esc_html_e( 'Price: high to low',  'townhub-add-ons' );?></option>
        </select>
        
    </div>
</div>
<!-- price-opt end-->