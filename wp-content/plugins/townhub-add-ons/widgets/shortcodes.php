<?php
/* add_ons_php */



/* Enable shortcode in widget text content */
add_filter('widget_text', 'do_shortcode');

if(!function_exists('faicon_sc')) {

    function faicon_sc( $atts, $content="" ) {
    
        extract(shortcode_atts(array(
               'name' =>"magic",
               'class'=>'',
         ), $atts));

        $name = str_replace(array("fa fa-","fa-"), "", $name);

        $classes = 'fa fa-'.$name;
        if(!empty($class)){
            $classes .= ' '.$class;
        }
        
        return '<i class="'.$classes.'"></i>'. $content;
     
    }
        
    add_shortcode( 'faicon', 'faicon_sc' ); //Icon
}
if(!function_exists('fapro_sc')) {

    function fapro_sc( $atts, $content="" ) {
    
        extract(shortcode_atts(array(
               'icon' => "",
               'class'=>'',
         ), $atts));

        if(!empty($class)){
            $icon .= ' '.$class;
        }
        
        return '<i class="'.$icon.'"></i>'. $content;
     
    }
        
    add_shortcode( 'fapro', 'fapro_sc' ); //Icon
}
if(!function_exists('townhub_instagram_sc')){
    function townhub_instagram_sc($atts, $content = ''){

        extract(shortcode_atts(array(
               'limit' =>"6",
               'get'=>'user',//tagged
               'clientid'=>'5d9aa6ad29704bcb9e7e151c9b7afcbc',
               'access'=>'3075034521.5d9aa6a.284ff8339f694dbfac8f265bf3e93c8a',
               'userid'=>'3075034521',
               'tagged'=>'townhub-add-ons',
               'resolution'=>'thumbnail',
               'columns'=>'3'
         ), $atts));

        if($get == 'tagged'){
            $getval = $tagged;
        }else if($get == 'user'){
            $getval = $userid;
        }else {
            $getval = 'popular';
        }

        ob_start();

        ?>

        <div class="cththemes-instafeed grid-cols-<?php echo esc_attr($columns );?>" data-limit="<?php echo esc_attr($limit );?>" data-get="<?php echo esc_attr($get );?>" data-getval="<?php echo esc_attr($getval );?>" data-client="<?php echo esc_attr($clientid );?>" data-access="<?php echo esc_attr($access );?>" data-res="<?php echo esc_attr($resolution );?>"><div class='cth-insta-thumb'><ul class="cththemes-instafeed-ul" id="<?php echo uniqid('cththemes-instafeed');?>"></ul></div></div>

        <?php

        $output = ob_get_clean();

        return $output;

    }

    //add_shortcode( 'townhub_instagram', 'townhub_instagram_sc' ); 
}

if(!function_exists('townhub_subscribe_callback')) {

    function townhub_subscribe_callback( $atts, $content="" ) {
        
        extract(shortcode_atts(array(
           'class'=>'',
           // 'title'=>'Newsletter',
           'message'=>'',
           'placeholder'=>__( 'Enter Your Email', 'townhub-add-ons' ),
           'button'=> '',
           'list_id' => '',
        ), $atts));

        $return = '';

        ob_start();
        ?>
        
        <div class="subscribe-form <?php echo esc_attr( $class ); ?>">
            <?php echo $message; ?>
            <form class="townhub_mailchimp-form">
                <div class="subscribe-form-wrap">
                  <input class="enteremail" id="subscribe-email" name="email" placeholder="<?php echo esc_attr( $placeholder ); ?>" type="email" required="required">
                  <button type="submit" class="subscribe-button"><i class="fal fa-envelope"></i> <?php echo esc_html( $button ); ?></button>
  
                </div>
                
                
                <label class="subscribe-agree-label" for="subscribe-agree-checkbox">
                    <input id="subscribe-agree-checkbox" type="checkbox" name="sub-agree-terms" required="required" value="1"><?php echo sprintf( _x( 'I agree with the <a href="%s">Privacy Policy</a>', 'subscribe form', 'townhub-add-ons' ), get_the_permalink( townhub_addons_get_option('sub_policy_page') ) ); ?>
                </label>

                
                <label for="subscribe-email" class="subscribe-message"></label>
                <?php if ( function_exists( 'wp_create_nonce' ) ) { ?>
                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce( 'townhub_mailchimp' ) ?>">
                <?php } 
                if($list_id !=''){ ?>
                <input type="hidden" name="_list_id" value="<?php echo esc_attr( $list_id ); ?>">
                <?php } ?>
            </form>
        </div>
        <?php  
        return ob_get_clean();
            
    }
        
    add_shortcode( 'townhub_subscribe', 'townhub_subscribe_callback' ); //Mailchimp

}

if(!function_exists('townhub_tweets_sc')){
    function townhub_tweets_sc($atts, $content = ''){

        extract(shortcode_atts(array(
               'username' =>'',
               'list'=>'',
               'hashtag'=>'',
               'count'=>'3',
               'list_ticker'=>'no',
               'follow_url' => '',
               'extraclass'=>''
         ), $atts));

        if ( $count =='')
            $count = 3;

        ob_start();

        ?>
        <div class="tweet townhub-tweet tweet-count-<?php echo esc_attr($count );?> tweet-ticker-<?php echo esc_attr($list_ticker );?>" data-username="<?php echo esc_attr($username );?>" data-list="<?php echo esc_attr($list );?>" data-hashtag="<?php echo esc_attr($hashtag );?>" data-ticker="<?php echo esc_attr($list_ticker );?>" data-count="<?php echo esc_attr($count );?>"></div>
        <?php 
        if($follow_url != '') : ?>
        <div class="follow-wrap">
            <a  href="<?php echo esc_url( $follow_url );?>" target="_blank" class="twiit-button"><i class="fa fa-twitter"></i><?php _e(' Follow Us','townhub-add-ons');?></a>  
        </div>
        <?php endif;?>
        <?php

        $output = ob_get_clean();

        return $output;

    }

    add_shortcode( 'townhub_tweets', 'townhub_tweets_sc' ); 
}