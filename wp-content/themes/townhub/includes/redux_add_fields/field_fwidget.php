<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_fwidget' ) ) {
        class ReduxFramework_fwidget {

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

                

                $defaults = array();

                $this->value = wp_parse_args( $this->value, $defaults );

                // var_dump($this->value);

                echo '<fieldset id="' . $this->field['id'] . '" class="redux-fwidget-container" data-id="' . $this->field['id'] . '">';

                ?>
                <div class="custom-forms">
                    <div class="repeater-fields-wrap"  data-tmpl="tmpl-redux-fwidget">
                        <div class="repeater-fields three-cols">
                        <?php 
                        $option_field_name = $this->field['name'] . $this->field['name_suffix'];
                        if(!empty($this->value)){
                            foreach ($this->value as $key => $widget) {
                                townhub_get_template_part('includes/redux_add_fields/tmpl-fwidget',false, array( 'index'=>$key,'name'=>$option_field_name,'widget'=>$widget ) );
                            }
                        }
                        ?>
                        </div>
                        <button class="btn-link btn-add" type="button" data-name="<?php echo esc_attr( $option_field_name ); ?>" ><?php  esc_html_e( 'Add Widget','townhub' );?></button>
                    </div>
                </div>
                <?php
                echo "</fieldset>";
            } //function

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            public function enqueue()
            {
                wp_enqueue_media();
                wp_enqueue_script('cth-redux-fields', get_template_directory_uri() . '/includes/redux_add_fields/cth-redux-fields.js', array('jquery'), null, true);

                wp_enqueue_style('redux-add-fields', get_template_directory_uri() . '/includes/redux_add_fields/redux-add-fields.css');

            } //function

            

            
        } //class
    }


