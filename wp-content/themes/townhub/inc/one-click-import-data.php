<?php
/* banner-php */
//http://proteusthemes.github.io/one-click-demo-import/
//https://wordpress.org/plugins/one-click-demo-import/

function townhub_import_files() {
    return array(
        array(
            'import_file_name'             => esc_html__('TownHub theme - Full Demo Content (widgets included)','townhub' ),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo_data_files/all-content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo_data_files/widgets.wie',
            'import_notice'                => esc_html__( 'TownHub theme - Full Demo Content (widgets included)', 'townhub' ),
        ),

        
    );
}
add_filter( 'pt-ocdi/import_files', 'townhub_import_files' );