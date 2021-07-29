<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_thumbnail_size' ) ) {
        class ReduxFramework_thumbnail_size {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since ReduxFramework 1.0.0
             */
            function __construct( $field = array(), $value = '', $parent ) {
                $this->parent = $parent;
                $this->field  = $field;
                $this->value  = $value;
            } //function

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 1.0.0
             */
            function render() {

                /*
                 * So, in_array() wasn't doing it's job for checking a passed array for a proper value.
                 * It's wonky.  It only wants to check the keys against our array of acceptable values, and not the key's
                 * value.  So we'll use this instead.  Fortunately, a single no array value can be passed and it won't
                 * take a dump.
                 */

                // No errors please
                $defaults = array(
                    'width'             => true,
                    'height'            => true,
                    'hard_crop'         => true,
                    // 'units'          => 'px',
                    // 'mode'           => array(
                    //     'width'  => false,
                    //     'height' => false,
                    // ),
                );
                // width 
                // height 
                // hard_crop 
                // class 

                $this->field = wp_parse_args( $this->field, $defaults );

                $defaults = array(
                    'width'  => '',
                    'height' => '',
                    'hard_crop'  => false
                );

                $this->value = wp_parse_args( $this->value, $defaults );


                echo '<fieldset id="' . $this->field['id'] . '" class="redux-thumbnail-size-container" data-id="' . $this->field['id'] . '">';


                /**
                 * Width
                 * */
                if ( $this->field['width'] === true ) {
                    if ( ! empty( $this->value['width'] ) ) {
                        $this->value['width'] = filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                    }
                    echo '<div class="field-thumbnail-size-input input-prepend">';
                    echo '<span class="add-on"><i class="el el-resize-horizontal icon-large"></i></span>';
                    echo '<label for="' . $this->field['id'] . '-width">' . esc_html__( 'Width ', 'townhub' ) . '</label>';
                    echo '<input type="text" id="' . $this->field['id'] . '-width" placeholder="' . esc_html__( 'Width', 'townhub' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[width]' . '" value="' . $this->value['width'] . '"></div>';
                }

                echo '<div class="field-thumbnail-size-input input-prepend"><span  class="ts-add-on"> x </span></div>';

                /**
                 * Height
                 * */
                if ( $this->field['height'] === true ) {
                    if ( ! empty( $this->value['height'] ) ) {
                        $this->value['height'] = filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                        
                    }
                    echo '<div class="field-thumbnail-size-input input-prepend">';
                    echo '<span class="add-on"><i class="el el-resize-vertical icon-large"></i></span>';
                    echo '<label for="' . $this->field['id'] . '-height">' . esc_html__( 'Height ', 'townhub' ) . '</label>';
                    echo '<input type="text" id="' . $this->field['id'] . '-height" placeholder="' . esc_html__( 'Height', 'townhub' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[height]' . '" value="' . $this->value['height'] . '"></div>';
                }

                echo '<div class="field-thumbnail-size-input input-prepend"><span class="ts-add-on"> px </span></div>';

                /**
                 * Hard_Crop
                 * */
                if ( $this->field['hard_crop'] === true ) {

                    echo '<div class="field-thumbnail-size-input input-hard_crop">';
                    echo '<label for="' . $this->field['id'] . '-hard_crop">';
                    echo '<input type="checkbox" id="' . $this->field['id'] . '-hard_crop" name="' . $this->field['name'] . $this->field['name_suffix'] . '[hard_crop]' . '" value="1"';
                    if(isset($this->value['hard_crop']) && $this->value['hard_crop'] == 1){
                        echo ' checked="checked"';
                    }

                    echo  '>'.esc_html__( 'Hard Crop ', 'townhub' ) . '</label>';
                    
                    echo '</div>';
                }

                echo "</fieldset>";
            } //function

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            function enqueue() {
                wp_enqueue_style( 'redux-add-fields' , get_template_directory_uri() . '/includes/redux_add_fields/redux-add-fields.css');

            } //function

            

            
        } //class
    }


