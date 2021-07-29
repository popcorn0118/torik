<?php
/* add_ons_php */


class AZPParser{
    private static $_instance;

	public $toStoreGlobalVar = array();

    private $azpShortcodes = array();
    private $customShortcodes = array();
    private $elementsOptions = array();

    public function __construct() {
        $this->elementsOptions = AZPElements::getEles();
    }

    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    public function doContentShortcode($content = ''){
        if(empty($content)) return $content;
        $pageSections = $this->parseContentShortcodeToSections($content);

        // echo'<pre>';
        // var_dump($pageSections);


        
        if((!is_array($pageSections) || empty($pageSections)) && $content != '') return $content;
        $articletext = '';
        foreach ($pageSections as $key => $row) {
            $articletext .= $this->parseArticleElement($row);
        }
        return $articletext;
    }

    public function getContentShortcodeEles($content = ''){
        if(empty($content)) return $content;
        $pageSections = $this->parseContentShortcodeToSections($content);

        return $pageSections;
    }

    protected function parseContentShortcodeToSections($content = '') {
        // replace p tag wrap shortcode 
        $content = preg_replace("/<p>(\[.*\])<\/p>/m", "$1", $content);
        //preg_match_all("/<p>(\[\/?[^<>]*\])<\/p>/m", $content, $matchees);
        
        $this->azpShortcodes = array_filter( array_keys($this->elementsOptions), function($sc){return $sc != 'AZPStyleOptions' && $sc != 'AZPTypoOptions' && $sc != 'AZPAnimationOptions' && $sc != 'AZPRespOptions';}) ;

        //var textShortcodes = s.match(/\[\/?(\w)*/g).map(stripShortcodeChar);//console.log(textShortcodes);
        preg_match_all("/\[\/?(\w)*/", $content, $matchees);

        $textShortcodes = array();
        if(count($matchees[0])){
            $textShortcodes = array_map( 
                function($sc) { return preg_replace("/\[\/?/", "", $sc); }, 
                $matchees[0] 
            ); 
        }

        $textShortcodes = array_filter( $textShortcodes );

        $this->customShortcodes = array_diff(array_unique($textShortcodes), $this->azpShortcodes);
        if(count($this->customShortcodes)){
            preg_match_all("/\[\/?(".implode("|", $this->azpShortcodes).").*?\]|\[{.*?}\]|[^\[]+/", $content, $matchees);

        }else{
            preg_match_all("/\[\/?\w.*?\]|\[{.*?}\]|[^\[]+/", $content, $matchees);
        }
        

        //echo'<pre>';var_dump( $matchees);die;
        if(!count($matchees[0])) return $content;
        //if(!count($matchees[0])) return '';

        //echo'<pre>';var_dump($matchees[0]);die;

        $startisendtag = $this->isEndTag($matchees[0][0]) ;
        if($startisendtag){
            array_unshift($matchees[0], '<div>', '['.$startisendtag.']');
        }elseif($this->checkSelfClosedShortcode( $matchees[0][0] )){
            array_unshift($matchees[0], '<div>');
        }
        $endisendtag = $this->isEndTag($matchees[0][count($matchees[0]) - 1]) ;
        if($endisendtag){
            $matchees[0][] = '<div>';
        }elseif($this->checkSelfClosedShortcode($matchees[0][ count($matchees[0]) - 1 ]) ){
            $matchees[0][] = '<div>';
        }




        $return = $this->doRecurse($matchees[0]);
        
        //echo '<pre>';var_dump($return);die;
        $return = array_filter($return, function($sec){
            if(is_array($sec)) {
                return true;
            }elseif(strpos($sec, '[') === 0){
                $lg = strlen($sec);
                if(strrpos($sec, ']') == $lg - 1) return true;
                
            }
            return false;
        });
        //echo '<pre>';var_dump($return);die;

        $pageSections = array_map(function($item){
            return $this->parseElementModel($item);
        }, $return);

        return $pageSections;
    }

    protected function doRecurse($matches, &$i = 0,$endtag=null){
        for($res = array(); $i < count($matches) ; $i++){
            $val = $matches[$i];
            if($val == $endtag){
                $res[] = $val;
                return array($res); // return as nested
            }else if(preg_match("/^\[\/\w.*?\]$/", $val)){
                $i--;
                return $res; // return as non-nested
            }else if( preg_match("/^\[\w.*?\]$/", $val)!= 1 || !count($res)){
                // normal text or opening tag at start of 
                // new part
                $res[] = $val;
            }else{
                // opening tag: recurse
                if(preg_match("/\w+/", $val, $nmats)){
                    $newres = $this->doRecurse($matches, $i,'[/' .$nmats[0] .']');
                    foreach($newres as $nr){
                        $res[] = $nr;
                    }
                }
                
                
            }
        }
        return $res ;
    }

    protected function isEndTag($str){
        if(preg_match('/\[\/([\w_]+)\]/', $str, $matches)){
            return $matches[1];
        }
        return false;
    }

    protected function checkSelfClosedShortcode($str){
        if(preg_match('/(\[[\w_]+.*?\])/', $str, $matches)){
            //var_dump($matches);
            return $matches[0];
        }
        return false;
    }

    protected function parseShortcodeName($sc_tag){
        $reg = "/\[\/?([\w_]+).*?\]/";
        if(preg_match($reg, $sc_tag, $matches)){
            return $matches[1];
        }
        return '';
    }

    protected  function parseShortcodeAttrs($sc_tag){
        $attributes = (object)array();
        if(preg_match_all('/[\w\-_]+="[^"]*"/', $sc_tag, $matches)!== false){
            if($matches){
                foreach ((array)$matches[0] as $attr) {
                    if(preg_match('/([\w\-_]+)="([^"]*)"/',$attr,$attrs)){
                        $attributes->{$attrs[1]} = $attrs[2];
                    }
                }
            }
        }

        return $attributes;
    }

    public function getEleDefaultContent($ele){
        $defaultContent = '';

        if($ele->type && isset($this->elementsOptions[$ele->type])){
            $newele = $this->elementsOptions[$ele->type];
            if(isset($newele['attrs']) && $newele['attrs']){
               
                foreach ((array)$newele['attrs'] as $attr) {
                    if((isset($attr['param_name']) && $attr['param_name'] == 'content') || (isset($attr['iscontent']) && $attr['iscontent']) ){
                        if(isset($attr['default'])){
                            $defaultContent = $attr['default'];
                        }
                    }
                }
                
            }
        }
        return $defaultContent;
    }

    protected function generateAttrs($options_array = array()){
        $defaultAttrs = (object)array();
        if(count($options_array) ) {
            foreach ((array)$options_array as $attr) {
                //var_dump($attr);
                if(isset($attr['param_name']) && $attr['param_name'] != 'content' && ($attr['type'] != 'label'|| $attr['type'] != 'clearfix' || $attr['type'] != 'previewimage') ){
                    if(isset($attr['default'])){
                        $defaultAttrs->{$attr['param_name']} = $attr['default'];
                    }else{
                        $defaultAttrs->{$attr['param_name']} = '';
                    }
                }
            }
        }
        return $defaultAttrs;
    }

    public function getEleDefaultAttrs($ele){
        $defaultAttrs = (object)array();
        //AzuraElements::loadElementsOptions();

        if($ele->type && isset($this->elementsOptions[$ele->type])){
            $newele = $this->elementsOptions[$ele->type];
            if(isset($newele['attrs'])){
                $newAttrs = $this->generateAttrs($newele['attrs']);
                $defaultAttrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
            }
            if(isset($newele['showStyleTab']) && $newele['showStyleTab'] ){
                if(isset($this->elementsOptions['AZPStyleOptions'])){
                    if(isset($this->elementsOptions['AZPStyleOptions']['attrs'])){
                        $newAttrs = $this->generateAttrs($this->elementsOptions['AZPStyleOptions']['attrs']);
                        $defaultAttrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
                    }
                }
            }
            if(isset($newele['showTypographyTab']) && $newele['showTypographyTab'] ){
                if(isset($this->elementsOptions['AZPTypoOptions'])){
                    if(isset($this->elementsOptions['AZPTypoOptions']['attrs'])){
                        $newAttrs = $this->generateAttrs($this->elementsOptions['AZPTypoOptions']['attrs']);
                        $defaultAttrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
                    }
                }
            }

            if(isset($newele['showAnimationTab']) && $newele['showAnimationTab'] ){
                if(isset($this->elementsOptions['AZPAnimationOptions'])){
                    if(isset($this->elementsOptions['AZPAnimationOptions']['attrs'])){
                        $newAttrs = $this->generateAttrs($this->elementsOptions['AZPAnimationOptions']['attrs']);
                        $defaultAttrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
                    }
                }
            }
            if(isset($newele['showResponsiveTab']) && $newele['showResponsiveTab'] ){
                if(isset($this->elementsOptions['AZPRespOptions'])){
                    if(isset($this->elementsOptions['AZPRespOptions']['attrs'])){
                        $newAttrs = $this->generateAttrs($this->elementsOptions['AZPRespOptions']['attrs']);
                        $defaultAttrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
                    }
                }
            }
            
            
            
        }
        //var_dump($defaultAttrs);

        $defaultAttrs->el_disable = 'no';

        if(!isset($defaultAttrs->azp_bwid))  $defaultAttrs->azp_bwid = '100';

        return $defaultAttrs;
    }


    protected function parseElementModel($item){
        $model = (object) array(
        	'type' => 'AzuraElement',
        	'name' => 'Element 2',
        	// 'published' => '1',
        	// 'language' => '*',
        	'content' => null,
        	'attrs' => null,
        	'children'=>array() 
        );
        //var_dump($item);
        



        foreach ((array)$item as $index => $sub) {
            if($index == 0){
                $model->type = $this->parseShortcodeName($sub);
                $defaultAttrs = $this->getEleDefaultAttrs($model);
                $newAttrs = $this->parseShortcodeAttrs($sub);
                $model->attrs = (object) array_merge((array) $defaultAttrs, (array) $newAttrs);
            }else{
                if(is_array($sub)){
                    $model->children[] = $sub;
                }elseif(is_string($sub)){
                    if($selfele = $this->checkSelfClosedShortcode($sub)){
                        $model->children[] = $selfele;
                    }else{
                        $tag_end = $this->parseShortcodeName($sub);
                        if($tag_end && $tag_end == $model->type) {
                            $model->isContainer = true;
                            if(!isset($model->content)) $model->content = '';
                        }else if($tag_end == ''){
                            if(count($this->customShortcodes)) {
                                //echo'<pre>';
                                if(preg_match("/^\/?(".implode("|", $this->customShortcodes).")/", $sub)) $sub = "[" . $sub;
                                //var_dump($sub);
                            }
                            if(isset($model->content)) {
                                $model->content .= $sub;
                            }else{
                                $model->content = $sub;
                            }

                        }
                    }
                }
            }
        }
        if(!isset($model->content))  $model->content = $this->getEleDefaultContent($model);
        if(!isset($model->attrs->azp_bwid))  $model->attrs->azp_bwid = '100';

        return $model ;
    }

    protected function parseArticleElement($element){
        if(!isset($element->content)) $element->content = '';
        // if(isset($element->published) && $element->published == '0') return '';
        if(isset($element->attrs->el_disable) && $element->attrs->el_disable == 'yes') return '';
        if(isset($element->children) && !empty($element->children)){
            foreach ((array)$element->children as $child) {
                $child = $this->parseElementModel($child);
                $element->content .= $this->parseArticleElement($child) ;
            }
        }
        return $this->contentNew($element->attrs, $element->content, $element->type);
    }

    public function contentNew($attrs, $content = null,$element) {
        return $this->loadTemplateNew( (array)$attrs, $content ,$element);
    }

    protected function loadTemplateNew( $azp_attrs, $azp_content = null ,$azp_element = '') {
        $shortcodeTemp = false;
        $child_folder = '';
        if($azp_element && isset($this->elementsOptions[$azp_element])){
            if(isset($this->elementsOptions[$azp_element]['template_folder']) && $this->elementsOptions[$azp_element]['template_folder'] != ''){
                $child_folder = $this->elementsOptions[$azp_element]['template_folder'];
            }
            if(isset($this->elementsOptions[$azp_element]['template_path']) && $this->elementsOptions[$azp_element]['template_path'] != ''){
                if(file_exists($this->elementsOptions[$azp_element]['template_path'])){
                    $shortcodeTemp = $this->elementsOptions[$azp_element]['template_path'] ;
                }
            }
        }

        if(!$shortcodeTemp) $shortcodeTemp = $this->checkShortcodeTemplateNew(strtolower($azp_element), $child_folder);
        
        
        // $buffer = ob_get_clean();
        // https://wordpress.org/support/topic/ob_get_clean-creates-conflict/
        // https://wordpress.org/support/topic/new-update-creates-syntax-error/
        // https://tommcfarlin.com/buffering-wordpress-content/
        
        ob_start();
        
        if($shortcodeTemp !== false) require $shortcodeTemp;
        
        $content = ob_get_clean();
        
        // ob_start();
        
        // echo $buffer;
        
        return $content;

    }

    protected function checkShortcodeTemplateNew($file, $child_folder = ''){
    	$template = "{$child_folder}{$file}.php";

    	if($located = locate_template( 'azp_templates/'.$template )){
            return $located;
        }else{
        	if(file_exists( ESB_ABSPATH ."azp_templates/$template" )) return ESB_ABSPATH ."azp_templates/$template";
            return false;
        }
    }


}