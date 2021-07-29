<?php
/* add_ons_php */

// $contact_infos = get_post_meta( get_the_ID(), '_cth_contact_infos', true );
// $contact_infos = array(
//     'address' => get_post_meta( get_the_ID(), '_cth_address', true ),
//     'latitude' => get_post_meta( get_the_ID(), '_cth_latitude', true ),
//     'longitude' => get_post_meta( get_the_ID(), '_cth_longitude', true ),
//     'phone' => get_post_meta( get_the_ID(), '_cth_phone', true ),
//     'email' => get_post_meta( get_the_ID(), '_cth_email', true ),
//     'website' => get_post_meta( get_the_ID(), '_cth_website', true ),
// );

if(null == $hide_logout) $hide_logout = false;
if(null == $hide_contacts_on) $hide_contacts_on = 'false';
?>
<div class="list-single-header-contacts fl-wrap  authplan-hide-<?php echo $hide_contacts_on;?>">
    <div class="for-hide-on-author"></div>
    <?php if( $hide_contacts_on !== 'true'){ 
        $address = get_post_meta( get_the_ID(), '_cth_address', true );
        $latitude = get_post_meta( get_the_ID(), '_cth_latitude', true );
        $longitude = get_post_meta( get_the_ID(), '_cth_longitude', true );
        $phone = get_post_meta( get_the_ID(), '_cth_phone', true );
        $email = get_post_meta( get_the_ID(), '_cth_email', true );
        // $website = get_post_meta( get_the_ID(), '_cth_website', true );
    ?>
    <ul>
    	<?php if( $phone != ''): ?>
        <li class="list-contact-phone"><i class="fa fa-phone"></i><a  href="tel:<?php echo esc_attr( $phone );?>"><?php echo esc_html( $phone ) ;?></a></li>
        <?php endif;?>
        <?php if( $latitude != '' && $longitude != '' && $address != ''): ?>
        <li class="list-contact-address"><i class="fa fa-map-marker"></i><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $latitude.','.$longitude;?>" target="_blank"><?php echo $address ;?></a></li>
        <?php endif;?>
        <?php if( $email != '' ): ?>
        <li class="list-contact-email"><i class="fa fa-envelope-o"></i><a  href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo $email ;?></a></li>
        <?php endif;?>
    </ul>
    <?php }elseif($hide_logout) _e( '<p>Login to see contact details.</p>','townhub-add-ons'); ?>
</div>
