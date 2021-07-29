<?php
/* add_ons_php */
$current_user = wp_get_current_user(); 
$delete_user = isset($_GET['delete']) ? $_GET['delete'] : '0';
?>
<div class="dashboard-title fl-wrap">
    <h3><?php _e( 'Your Profile', 'townhub-add-ons' ); ?></h3>
</div>
<div class="user-info-form-wrap">
    <?php 
    if( townhub_addons_get_option('delete_user') == 'yes' && $delete_user === '1' ): ?>
    <form class="delete-account-form" action="#" method="POST">
        <div class="pad-30 block_box mb-30">
            <div class="profile-edit-header fl-wrap mb-10">
                <h3><?php esc_html_e( 'Delete the account?', 'townhub-add-ons' );?></h3>
            </div>
            <div class="delete-account-inner custom-form">

                <p><?php _e( 'Are you sure want to delete your account?', 'townhub-add-ons' ); ?></p>
                <p><?php _e( 'All related data created by this account will be permanently deleted.', 'townhub-add-ons' ); ?></p>
                                                             
                <div class="del-btns flex-items-center">
                    <button class="btn del-bg flat-btn" type="submit"><?php esc_html_e( 'Delete', 'townhub-add-ons' );?><i class="fal fa-trash"></i></button>
                    <a class="flat-btn btn-border mt-20 ml-30" href="<?php echo Esb_Class_Dashboard::screen_url('profile');?>"><?php esc_html_e( 'Cancel', 'townhub-add-ons' );?></a>
                </div>
                <input type="hidden" name="action"  value="del_account">
                <input type="hidden" name="uid"  value="<?php echo $current_user->ID; ?>">
                <input type="hidden" name="_wpnonce"  value="<?php echo wp_create_nonce( 'cth_del_account' ); ?>">
                
            </div>
        </div>
    </form>
    <?php endif; ?>
    <form id="user-info-form" action="#" method="POST">
        <!-- profile-edit-container--> 
        <div class="profile-edit-container fl-wrap block_box">
            <div class="ajax-result-message"></div>
            <div class="custom-form">
                <div class="row">
                    <?php do_action( 'cth_user_profile_before' ); ?>
                    <div class="col-sm-6">
                        <label><?php _e( 'First Name ', 'townhub-add-ons' ); ?><i class="fal fa-user"></i></label>
                        <input name="first_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'First Name', 'townhub-add-ons' ); ?>" value="<?php echo $current_user->first_name;?>"/>                                              
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Last Name ', 'townhub-add-ons' ); ?><i class="fal fa-user"></i></label>
                        <input name="last_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Last Name', 'townhub-add-ons' ); ?>" value="<?php echo $current_user->last_name;?>"/>                                            
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Display Name ', 'townhub-add-ons' ); ?><i class="fal fa-user-minus"></i></label>
                        <input name="display_name" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Display Name', 'townhub-add-ons' ); ?>" value="<?php echo $current_user->display_name;?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Registered Email', 'townhub-add-ons' ); ?><i class="far fa-envelope"></i></label>
                        <input type="text" class="has-icon" value="<?php echo $current_user->user_email;?>" disabled="disabled">                                      
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Contact Email', 'townhub-add-ons' ); ?><i class="far fa-envelope"></i></label>
                        <input type="text" class="has-icon" name="email" placeholder="<?php esc_attr_e( 'Contact Email', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr(get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true ));?>" >                                      
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Phone', 'townhub-add-ons' ); ?><i class="far fa-phone"></i>  </label>
                        <input name="phone" class="has-icon" type="text" placeholder="<?php esc_attr_e( '+7(123)987654', 'townhub-add-ons' ); ?>" value="<?php echo  esc_attr(get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true ));?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Address', 'townhub-add-ons' ); ?><i class="far fa-map-marker"></i>  </label>
                        <input name="address" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'USA 27TH Brooklyn NY', 'townhub-add-ons' ); ?>" value="<?php echo  esc_attr(get_user_meta($current_user->ID,  ESB_META_PREFIX.'address', true ));?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Website', 'townhub-add-ons' ); ?><i class="far fa-globe"></i>  </label>
                        <input name="user_url" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'http://website.com', 'townhub-add-ons' ); ?>" value="<?php echo  esc_url($current_user->user_url);?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label><?php _e( 'Company', 'townhub-add-ons' ); ?><i class="far fa-building"></i>  </label>
                        <input name="company" class="has-icon" type="text" placeholder="<?php esc_attr_e( 'Chamber Company', 'townhub-add-ons' ); ?>" value="<?php echo  esc_attr(get_user_meta($current_user->ID,  ESB_META_PREFIX.'company', true ));?>"/>
                    </div>
                    <?php do_action( 'cth_user_profile_after' ); ?>
                </div>
                <label><?php esc_html_e( 'Description', 'townhub-add-ons' );?></label>                                              
                <textarea cols="40" rows="3" name="description" placeholder="<?php esc_attr_e( 'About Me', 'townhub-add-ons' ); ?>"><?php echo $current_user->description;?></textarea>
                
                <div class="clearfix"></div>
                <div class="row images-row" id="profile-images">
                    <div class="col-md-6">

                        <label><?php _e( 'Change Avatar', 'townhub-add-ons' ); ?></label> 
                        <div class="edit-profile-photo fl-wrap upload-photo-js-wrap">
                            <div class="profile-photo-wrap"><?php 
                                // https://wordpress.stackexchange.com/questions/7620/how-to-change-users-avatar
                                echo get_avatar($current_user->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $current_user->display_name );
                            ?>
                            </div> 
                            <div class="change-photo-btn">
                                <div class="photoUpload">
                                    <span><i class="fal fa-image"></i><?php _e( 'Upload Photo', 'townhub-add-ons' ); ?></span>
                                    <?php 
                                    if( current_user_can( 'upload_files' ) ){
                                        $avatar_data = get_user_meta($current_user->ID,  ESB_META_PREFIX.'custom_avatar', true );
                                        if(is_array($avatar_data) && count($avatar_data)){
                                            $custom_ava_id = reset($avatar_data);
                                            if(!is_numeric($custom_ava_id)) $avatar_data = array(key($avatar_data));
                                        }
                                        townhub_addons_get_template_part( 'template-parts/images-select', false, array( 'is_single' => true, 'name'=>'custom_avatar', 'datas'=> $avatar_data ) );
                                    }else{ ?>
                                        <input type="file" class="upload cth-avatar-upload" name="custom_avatar_upload">
                                    <?php
                                    } ?>
                                </div>
                            </div>
                            <a href="#" class="del-user-photo" data-name="custom_avatar"><i class="fal fa-times"></i></a>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <?php 
                        $imgs_data = get_user_meta($current_user->ID,  ESB_META_PREFIX.'cover_bg', true ); 
                        $img_id = '';
                        if(is_array($imgs_data) && count($imgs_data)){
                            $img_id = reset($imgs_data);
                        }
                        
                        ?>
                        <label><?php _e( 'Cover Background', 'townhub-add-ons' ); ?></label> 
                        
                        <div class="edit-profile-photo fl-wrap upload-photo-js-wrap">
                            <div class="profile-photo-wrap"><?php 
                                if($img_id != '') echo wp_get_attachment_image( $img_id );
                            ?>
                            </div> 
                            <div class="change-photo-btn">
                                <div class="photoUpload">
                                    <span><i class="fal fa-image"></i><?php _e( 'Upload Photo', 'townhub-add-ons' ); ?></span>
                                    <?php 
                                    if( current_user_can( 'upload_files' ) ){
                                        
                                        townhub_addons_get_template_part( 'template-parts/images-select', false, array( 'is_single' => true, 'name'=>'cover_bg', 'datas'=> $imgs_data ) );
                                    }else{ ?>
                                        <input type="file" class="upload cth-avatar-upload" name="cover_bg_upload">
                                    <?php
                                    } ?>
                                </div>
                            </div>
                            <a href="#" class="del-user-photo" data-name="cover_bg"><i class="fal fa-times"></i></a>
                        </div>

                    </div>
                </div>
                        

            </div>
        </div>
        <!-- profile-edit-container end--> 
        <div class="dashboard-title dt-inbox fl-wrap">
            <h3><?php esc_html_e( 'Socials', 'townhub-add-ons' );?></h3>
        </div>
        <!-- profile-edit-container--> 
        <div class="profile-edit-container fl-wrap block_box">
            <?php 
            $socials = get_user_meta($current_user->ID,  '_cth_socials', true );
            ?>
            <div class="custom-forms">
                <div class="repeater-fields-wrap"  data-tmpl="tmpl-user-social">
                    <div class="repeater-fields">
                    <?php 
                    if(!empty($socials)){
                        foreach ($socials as $key => $social) {
                            townhub_addons_get_template_part('templates-inner/social',false, array('index'=>$key,'name'=>$social['name'],'url'=>$social['url']));
                        }
                    }
                    ?>
                    </div>
                    <button class="btn-link btn-add" type="button"><?php  esc_html_e( 'Add Social','townhub-add-ons' );?></button>
                </div>

                <button id="edit-profile-submit" class="btn color2-bg" type="submit"><?php esc_html_e( 'Save Changes', 'townhub-add-ons' );?><i class="fal fa-save"></i></button>
                
                <?php 
                if( townhub_addons_get_option('delete_user') == 'yes' ): ?>
                <a class="btn-link del-link del-account" href="<?php echo add_query_arg( array('dashboard' => 'profile', 'delete' => '1',), get_permalink(townhub_addons_get_option('dashboard_page')) ); ?>"><?php esc_html_e( 'Delete account', 'townhub-add-ons' );?></a>
                <?php endif; ?>
                            
            </div>
        </div>
        <!-- profile-edit-container end-->  
    </form>
</div>
<!-- user-info-form-wrap end-->  
