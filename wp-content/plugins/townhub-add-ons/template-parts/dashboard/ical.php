<?php
/* add_ons_php */
// don't show on customer dashboard
$current_user = wp_get_current_user();    

$result = isset($_GET['_result']) ? $_GET['_result'] : '';
$message = Esb_Class_iCal::get_result_message($result);
$is_export_screen = isset($_GET['export']) ? true : false;
?>
<div class="dashboard-content-wrapper dashboard-content-ical-sync">
    <div class="dashboard-content-inner">
        
        <div class="dashboard-title   fl-wrap">
            <h3><?php echo $is_export_screen ? _x( 'iCal Export', 'Dashboard iCal', 'townhub-add-ons' ) : _x( 'iCal Import', 'Dashboard iCal', 'townhub-add-ons' ); ?></h3>
        </div>

        <?php 
        if( !$is_export_screen ){
            $url = add_query_arg('export', '', Esb_Class_Dashboard::screen_url('ical') );  
            ?>
            <div class="bkreports-btn-wrap mb-20">
                <a href="<?php echo esc_url( $url ); ?>" class="bkreports-btn btn btn-noicon color2-bg"><?php echo esc_html_x( 'iCal Export', 'Dashboard iCal', 'townhub-add-ons' ); ?></a>
            </div>
            <?php
        }
        ?>

        <?php 
        if( !empty($message) ): ?>
        <div class="esb_msg esb_msg-info">
            <div class="esb_msg-message"><?php echo $message; ?></div>
        </div>
        <?php endif; ?>

        
        
        <div class="dashboard-ical-wrap">
            
            <div class="profile-edit-container block_box dashboard-block-box clearfix">

                <form method="post" enctype="multipart/form-data">
                    <?php 
                    $meta_queries = array();
                    $listing_args = array(
                        'post_type'         => 'listing',
                        'author'            =>  $current_user->ID,
                        'post_status'       => 'publish',
                        'posts_per_page'    => -1,
                    );

                    if(!empty($meta_queries)) $listing_args['meta_query'] = $meta_queries;

                    $listing_posts = get_posts($listing_args);
                    if(empty($listing_posts)) echo '<div class="ad-no-listing-msg">'._x( 'You have no Published listings yet!','Dashboard iCal', 'townhub-add-ons' ).'</div>';
                    ?>
                    <label for="ical-listing-select" class="field-lbl"><?php _ex( 'Select a listing','Dashboard iCal', 'townhub-add-ons' ); ?></label>
                    <select name="ical-listing" id="ical-listing-select" class="chosen-select clearfix mb-20"<?php if(empty($listing_posts)) echo ' disabled="disabled"'; ?>>
                        <option value=""><?php echo esc_html_x( 'Select a listing','Dashboard iCal',  'townhub-add-ons' );?></option>
                        <?php 
                        if(!empty($listing_posts)){
                            foreach ($listing_posts as $listing) {
                                echo '<option value="'.$listing->ID.'">'.$listing->post_title.'</option>';
                            }
                        }
                        ?>
                    </select>

                    <?php 
                    $rooms = get_posts(
                        array(
                            'post_type'         => 'lrooms',
                            'author'            =>  $current_user->ID,
                            'post_status'       => 'publish',
                            'posts_per_page'    => -1,
                        )
                    );
                    if(!empty($rooms)):
                    ?>
                    <div class="row ical-room-row">
                        <div class="col-md-4">
                            <?php 
                            $swid = uniqid('onoffswitch'); ?>
                            <div class="onoffswitch-wrap mb-20">
                                <label for="<?php echo esc_attr($swid);?>" class="field-lbl"><?php echo $is_export_screen ? _x( 'Or export room dates?','Dashboard iCal', 'townhub-add-ons' ) : _x( 'Or import dates to room?','Dashboard iCal', 'townhub-add-ons' ); ?></label>
                                <div class="onoffswitch">
                                    <input type="checkbox" name="for_room" class="onoffswitch-checkbox" id="<?php echo esc_attr($swid);?>" value="yes">
                                    <label class="onoffswitch-label" for="<?php echo esc_attr($swid);?>">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="ical-room-select" class="field-lbl"><?php _ex( 'Select a room','Dashboard iCal', 'townhub-add-ons' ); ?></label>
                            <select name="ical-room" id="ical-room-select" class="chosen-select clearfix mb-20">
                                <option value=""><?php echo esc_html_x( 'Select a room','Dashboard iCal',  'townhub-add-ons' );?></option>
                                <?php 
                                foreach ($rooms as $room) {
                                    echo '<option value="'.$room->ID.'">'.$room->post_title.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                            
                    <?php endif; ?>

                    <?php if( false == $is_export_screen ):?>

                        <div class="upload-file-col mb-20">

                            <label class="field-lbl"><?php _ex( 'iCal file','Dashboard iCal', 'townhub-add-ons' ); ?></label> 
                            <div class="file-uploader-wrap fl-wrap">
                                <div class="file-uploader-inner">
                                    <div class="photoUpload">
                                        <span><i class="fal fa-image"></i><?php _ex( 'Upload .ics file','Dashboard iCal', 'townhub-add-ons' ); ?></span>
                                        <input type="file" accept=".ics" class="upload file-uploader" name="ical_file" required="required">
                                        
                                    </div>
                                    <div class="uploaded-file-wrap"></div>
                                </div>
                            </div>

                        </div>

                        
                        <?php 
                        $swid = uniqid('onoffswitch'); ?>
                        <div class="onoffswitch-wrap mb-20">
                            <label for="<?php echo esc_attr($swid);?>" class="field-lbl"><?php _ex( 'Import as unavailable dates?','Dashboard iCal', 'townhub-add-ons' ); ?></label>
                            <p class="field-desc field-desc-top"><?php _ex( 'Toggle on this option if you want to import dates as unvailable for booking.','Dashboard iCal', 'townhub-add-ons' ); ?></p>
                            <div class="onoffswitch">
                                <input type="checkbox" name="ical_unavailable" class="onoffswitch-checkbox" id="<?php echo esc_attr($swid);?>" value="yes">
                                <label class="onoffswitch-label" for="<?php echo esc_attr($swid);?>">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                            
                        </div>

                        <button class="btn color2-bg mt-30" type="submit"><?php echo esc_html_x( 'Import', 'Dashboard iCal', 'townhub-add-ons' );?><i class="fal fa-file-upload"></i></button>
                    
                        <input type="hidden" name="action" value="ical_import">
                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('ical_import'); ?>">

                    <?php else : ?>
                        <button class="btn color2-bg mt-30" type="submit"><?php echo esc_html_x( 'Export', 'Dashboard iCal', 'townhub-add-ons' );?><i class="fal fa-file-download"></i></button>
                    
                        <input type="hidden" name="action" value="ical_export">
                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('ical_export'); ?>">
                    <?php endif; ?>
                    
                </form>

            </div>
                

            
        </div>
    </div>
</div>
