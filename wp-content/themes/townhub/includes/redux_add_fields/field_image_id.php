<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ReduxFramework_image_id')) {
    class ReduxFramework_image_id
    {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since ReduxFramework 1.0.0
         */
        public function __construct($field = array(), $value = '', $parent)
        {
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
        public function render()
        {

            if (is_array($this->value)) {
                $this->value = reset($this->value);
            }

            $imgclass = str_replace(array('[', ']'), "_", $this->field['name'] . $this->field['name_suffix']);
            $imageurl = '';
            if ($this->value) {
                $imageurl = wp_get_attachment_url($this->value);
            }

            echo '<fieldset id="' . $this->field['id'] . '" class="redux-image-id-container" data-id="' . $this->field['id'] . '">';

                echo '<div class="form-field media-field-wrap">';
                    echo '<img class="' . $imgclass . '_preview auwid-avatar" src="' . $imageurl . '" ' . ($imageurl != '' ? ' style="display:block;"' : '') . '>';
                    echo '<input type="hidden" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" class="' . $imgclass . '_id" value="' . $this->value . '">';
                    // echo '<input type="hidden" name="" class="' . $imgclass . '_url" value="' . $this->value . '">';

            ?>
                    <p class="descriptions">
                        <a href="#" data-uploader_title="<?php esc_attr_e('Upload Image', 'townhub');?>" class="button button-primary cth-redux-image metakey-<?php echo esc_attr( $this->field['name'] ); ?> fieldkey-<?php echo esc_attr( $this->field['name_suffix'] ); ?>"><?php esc_html_e('Upload Image', 'townhub');?></a>
                        <a href="#" class="button button-secondary cth-redux-image-remove metakey-<?php echo esc_attr( $this->field['name'] ); ?> fieldkey-<?php echo esc_attr( $this->field['name_suffix'] ); ?>"><?php esc_html_e('Remove', 'townhub');?></a>
                    </p>
        <?php

                echo '</div>';

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
