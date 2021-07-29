<?php
/* add_ons_php */
if(!isset($ltype)){
    if(isset($_GET['ltype'])) 
        $ltype = $_GET['ltype'];
    else
        $ltype = false;
}
$ltype = apply_filters( 'townhub_addons_filter_ltype', $ltype );
?>
					<div class="fl-wrap filters-search-wrap list-search-page-form-wrap">

                        <?php do_action('townhub_addons_before_filters');?>

                        <form id="list-search-page-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="list-search-page-form list-search-form-js">

                            <div class="listsearch-inputs-wrapper">
                                <?php
                                do_action('townhub_addons_filter_before');
                                echo townhub_addons_azp_parser_listing($ltype, 'filter');
                                do_action('townhub_addons_filter_after');
                                ?>
                            </div>
                            <?php
                        if (isset($posts_per_page)) {
                            echo '<input type="hidden" name="lposts_per_page" value="' . $posts_per_page . '">';
                        }

                        if (isset($orderby)) {
                            echo '<input type="hidden" name="lorderby" value="' . $orderby . '">';
                        }

                        if (isset($order)) {
                            echo '<input type="hidden" name="lorder" value="' . $order . '">';
                        }

                        ?>
                            <input type="hidden" name="morderby" value="">

                        </form>
                        <div class="loading-indicator filter-form-loading">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>