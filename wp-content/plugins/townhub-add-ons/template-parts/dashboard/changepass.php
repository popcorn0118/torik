<?php
/* add_ons_php */
?>
<div class="dashboard-title   fl-wrap">
    <h3><?php esc_html_e( 'Change Password', 'townhub-add-ons' );?></h3>
</div>
<!-- profile-edit-container--> 
<div class="profile-edit-container fl-wrap block_box">
    <form id="user-changepass-form" action="#" method="POST">
        <div class="ajax-result-message"></div>
        <div class="custom-form">
            <div class="pass-input-wrap fl-wrap">
                <label><?php esc_html_e( 'Current Password', 'townhub-add-ons' );?></label>
                <input type="password" name="old_pass" class="pass-input" required="required">
                <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
            </div>
            <div class="pass-input-wrap fl-wrap">
                <label><?php esc_html_e( 'New Password', 'townhub-add-ons' );?></label>
                <input type="password" name="new_pass" class="pass-input" required="required">
                <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
            </div>
            <div class="pass-input-wrap fl-wrap">
                <label><?php esc_html_e( 'Confirm New Password', 'townhub-add-ons' );?></label>
                <input type="password" name="confirm_pass" class="pass-input" required="required">
                <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
            </div>
            <button id="change-pass-submit" class="btn color2-bg" type="submit"><?php _e( 'Save Changes', 'townhub-add-ons' ); ?><i class="fal fa-save"></i></button>
        </div>
    </form>
</div>
<!-- profile-edit-container end-->  




