<?php 
/* add_ons_php */


defined( 'ABSPATH' ) || exit; 

// http://scripthere.com/how-to-lazy-load-images-in-wordpress-without-plugin/
class Esb_Class_Lazy_Load{ 
    private static $plugin_url;
    public static function init(){ 
        self::$plugin_url = plugin_dir_url(ESB_PLUGIN_FILE);     
        // add_action( 'wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'), 999 );   
        // add_action( 'wp_head', array(__CLASS__, 'modify_imgs'), 999 );   
        add_action( 'wp_footer', array(__CLASS__, 'footer_scripts'), 999 );  

        // add_filter( 'post_thumbnail_html', array(__CLASS__, 'lazyload_placeholders') , 11 ); 

        self::modify_imgs();
    }

    public static function enqueue_scripts(){

        wp_enqueue_script( 'lazyload', self::$plugin_url ."assets/js/lazyload.js?render=explicit#cthasync" , array('townhub-addons'), null , true );

    }

    public static function footer_scripts(){
    ?>
        <script>
            function lazyListingsChanged(){
                /* create new window event for listing change */
                var evt = new CustomEvent('listingsChanged', { detail: 'lazy-load' });
                window.dispatchEvent(evt);
            }
            function lazyGalChanged(el){
                /* create new window event for listing change */
                var evt = new CustomEvent('galChanged', { detail: el });
                window.dispatchEvent(evt);
            }
            /* Set the options to make LazyLoad self-initialize  */
            window.lazyLoadOptions = {  
                elements_selector: ".lazy", 
                /* ... more custom settings?  */
                callback_loaded: function(el){
                    /* console.log("Loaded", el) */
                    if(window.listingItemsEle != null){
                        /* console.log('has #listing-items'); */
                        if(window.listingItemsEle.contains(el)){
                            /* console.log('el inside #listing-items'); */
                            lazyListingsChanged()
                        }
                    }
                    lazyGalChanged(el)
                }, 
                callback_finish: function(){
                    /* console.log("Finish") */
                },   
            };
            /* Listen to the initialization event and get the instance of LazyLoad   */
            window.addEventListener('LazyLoad::Initialized', function (event) { 
                window.lazyLoadInstance = event.detail.instance; 

                window.listingItemsEle = document.getElementById('listing-items')   
            }, false);
        </script>
        <script async src='<?php echo self::$plugin_url ."assets/js/lazyload.js"; ?>'></script>
    <?php
    }

    public static function modify_imgs(){
        // //add image placeholders to post/page content
        // add_filter( 'the_content', array(__CLASS__, 'lazyload_placeholders') , 99 );
        // //run this later, so other content filters have run, including image_add_wh on WP.com
        // add_filter( 'post_thumbnail_html', array(__CLASS__, 'lazyload_placeholders') , 11 );
        add_filter( 'get_avatar', array(__CLASS__, 'lazyload_placeholders'), 999 );

        // // for wp_get_attachment_image
        // add_filter( 'azp_single_content', array(__CLASS__, 'lazyload_placeholders'), 11 );

        add_filter( 'wp_get_attachment_image_attributes', array(__CLASS__, 'lazyload_image_attributes'), 999 );

        // apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );

    }

    public static function lazyload_image_attributes($attrs){
        if(isset($attrs['class']) && strpos($attrs['class'], 'swiper-lazy') !== false){
            // src attribute
            if(isset($attrs['src'])){
                $attrs['data-src'] = $attrs['src'];
                // $attrs['data-lazy'] = $attrs['src'];
                unset($attrs['src']);
            }
            return $attrs;
        }
        if(isset($attrs['class']) && strpos($attrs['class'], 'no-lazy') !== false) return $attrs;
        if(isset($attrs['class']) && strpos($attrs['class'], 'woocommerce') !== false) return $attrs;
        // image class
        if(isset($attrs['class'])){
            $attrs['class'] .= ' lazy';
        }else{
            $attrs['class'] = 'lazy';
        }
        // src attribute
        if(isset($attrs['src'])){
            $attrs['data-src'] = $attrs['src'];
            $attrs['data-lazy'] = $attrs['src'];
            unset($attrs['src']);
        }
        // sizes attribute
        if(isset($attrs['sizes'])){
            $attrs['data-sizes'] = $attrs['sizes'];
            unset($attrs['sizes']);
        }
        // srcset attribute
        if(isset($attrs['srcset'])){
            $attrs['data-srcset'] = $attrs['srcset'];
            unset($attrs['srcset']);
        }
        $lazy_placeholder = townhub_addons_get_option('lazy_placeholder');
        $default_load_img_src = '';
        if(!empty($lazy_placeholder['id'])) $default_load_img_src = wp_get_attachment_url( $lazy_placeholder['id'] );
        if(!empty($default_load_img_src)){
            $attrs['src'] = $default_load_img_src;
        }

        return $attrs;
    }

    public static function lazyload_placeholders($content){

        if(!class_exists('DOMDocument')) return $content;

        $lazy_placeholder = townhub_addons_get_option('lazy_placeholder');
        $default_load_img_src = '';
        if(!empty($lazy_placeholder['id'])) $default_load_img_src = wp_get_attachment_url( $lazy_placeholder['id'] );
        //init dom object
        // https://stackoverflow.com/questions/1685277/warning-domdocumentloadhtml-htmlparseentityref-expecting-in-entity
        $dom_obj = new DOMDocument();
        $dom_obj->recover = true;
        $dom_obj->strictErrorChecking = false;
        //load content
        @$dom_obj->loadHTML($content);
        //loop html objects which contains image tag
        foreach ($dom_obj->getElementsByTagName('img') as $node) {
            if ( $node->hasAttribute( 'src' ) ){
                //getting original image source path
                $original_img_src = $node->getAttribute('src');
                //set a new attribute to image "i.e data-src" tag and set image source path
                $node->setAttribute("data-src", $original_img_src );
                // add data-lazy for slick slider
                $node->setAttribute("data-lazy", $original_img_src );
                // //init default load image path
                // $default_load_img_src = 'http://localhost/wp/wp-content/uploads/2018/01/sun-300x225.gif';
                // //set or replace a src attribute value to default load image path
                // $node->setAttribute("src", $default_load_img_src);
                if(!empty($default_load_img_src)){
                    $node->setAttribute("src", $default_load_img_src);
                }else{
                    //remove original image src
                    $node->removeAttribute( 'src' );
                }
                //check for responsive post data
                if ( $node->hasAttribute( 'sizes' ) && $node->hasAttribute( 'srcset' ) ) {
                    //getting original image sizes
                    $sizes_attr = $node->getAttribute( 'sizes' );
                    //getting original image srcsets
                    $srcset     = $node->getAttribute( 'srcset' );
                    //set a new attribute to image "i.e data-sizes" tag and set original image sizes
                    $node->setAttribute( 'data-sizes', $sizes_attr );
                    //set a new attribute to image "i.e data-srcset" tag and set original image srcsets
                    $node->setAttribute( 'data-srcset', $srcset );
                    //remove original image sizes
                    $node->removeAttribute( 'sizes' );
                    //remove original image srcsets
                    $node->removeAttribute( 'srcset' );
                }
                //check for any class included for image tag append class, if not add our class name i.e "lazy"
                if ( $node->hasAttribute( 'class' ) ) {
                    $class = $node->getAttribute( 'class' );
                    $class .=" lazy";
                    $node->setAttribute( 'class',$class );
                }else{
                    $node->setAttribute( 'class', "lazy" );
                }
            }   
        }
        

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom_obj->saveHTML());

        //save modification
        $newContent = $dom_obj->saveHtml();
        //return
        return $newContent;
    }
}
Esb_Class_Lazy_Load::init();