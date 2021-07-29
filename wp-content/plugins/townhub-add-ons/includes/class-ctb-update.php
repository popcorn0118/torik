<?php 
/* add_ons_php */
class CTB_Update {
	/**
	 * Instance.
	 *
	 * Holds the CTB_Update instance.
	 *
	 */
	public static $instance = null;

	public static $messages = array('<p>Thank you for using this plugin! <strong>'.CITYBOOK_ADD_ONS_VERSION.'</strong>.</p>');

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 *
	 * @return CTB_Update An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function update(){
		set_transient( 'ctb-update-admin-notice', true, 30 );

		self::$messages[] = '<p>Thank you for using this plugin! <strong>'.ESB_VERSION.'</strong>.</p>';
	}

	public function admin_notices(){

		/* Check transient, if available display notice */
	    if( get_transient( 'ctb-update-admin-notice' ) ){
	        ?>
	        <div class="updated notice is-dismissible">
	            <?php echo implode("<br>", self::$messages); ?>
	        </div>
	        <?php
	        /* Delete transient, only display this notice once. */
	        delete_transient( 'ctb-update-admin-notice' );
	    }
	}
	private function __construct() {
		register_activation_hook(ESB_PLUGIN_FILE, array('CTB_Update', 'update'));

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}

}

// CTB_Update::instance();


function townhub_addons_update_message_system() {
	global $wpdb;

    $chat_table = $wpdb->prefix . 'cth_chat';
    $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';
    $charset_collate = $wpdb->get_charset_collate();

    $chat_sql = "CREATE TABLE IF NOT EXISTS $chat_table (
        c_id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        user_one bigint(20) UNSIGNED NOT NULL,
        user_two bigint(20) UNSIGNED NOT NULL,
        ip varchar(30) DEFAULT NULL,
        time int(11) DEFAULT NULL,
        FOREIGN KEY (user_one) REFERENCES $wpdb->users(ID),
        FOREIGN KEY (user_two) REFERENCES $wpdb->users(ID)
    ) $charset_collate;";

    $chat_reply_sql = "CREATE TABLE IF NOT EXISTS $chat_reply_table (
        cr_id bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        reply text,
        user_id_fk bigint(20) UNSIGNED NOT NULL,
        ip varchar(30) DEFAULT NULL,
        time int(11) DEFAULT NULL,
        c_id_fk int(11) UNSIGNED NOT NULL,
        status TINYINT(1) DEFAULT 0,
        FOREIGN KEY (user_id_fk) REFERENCES $wpdb->users(ID),
        FOREIGN KEY (c_id_fk) REFERENCES {$chat_table}(c_id)
    ) $charset_collate;";

    


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $chat_sql );
    dbDelta( $chat_reply_sql );
}

function townhub_addons_update_to_version_1_2_9() {

	$working_days = Esb_Class_Date::week_days();
	foreach ($working_days as $day => $dayLbl ) {
		if($day != $dayLbl){
			$statics = townhub_addons_get_meta_values( ESB_META_PREFIX."wkh_status_{$dayLbl}", 'listing', array('publish', 'pending') ); 
			if(is_array($statics) && count($statics)){
	            foreach ($statics as $l_ID => $static) {
	                update_post_meta( $l_ID, ESB_META_PREFIX."wkh_status_{$day}", $static );

	                delete_post_meta( $l_ID, ESB_META_PREFIX."wkh_status_{$dayLbl}" );
	            }
	        }
	        $old_hours = townhub_addons_get_meta_values( ESB_META_PREFIX."wkh_hours_{$dayLbl}", 'listing', array('publish', 'pending') ); 
	        if(is_array($old_hours) && count($old_hours)){
	            foreach ($old_hours as $l_ID => $old_hour) {
	                update_post_meta( $l_ID, ESB_META_PREFIX."wkh_hours_{$day}", $old_hour );

	                delete_post_meta( $l_ID, ESB_META_PREFIX."wkh_hours_{$dayLbl}" );
	            }
	        }
		}
    }

}

function townhub_addons_update_to_version_1_3_0(){
	global $wpdb;

	// $dbname = $wpdb->dbname;
	$chat_table = $wpdb->prefix . 'cth_chat';

	// $chat_tables = $wpdb->get_results( 
	// 	"
	// 	SELECT * 
	// 	FROM information_schema.tables
	// 	WHERE table_schema = $dbname 
	// 	    AND table_name = $chat_table
	// 	LIMIT 1
	// 	"
	// );

	if($wpdb->get_var("SHOW TABLES LIKE '$chat_table'") != $chat_table) {
	    // do something
	    townhub_addons_update_message_system();
	}

	// if ( !$chat_tables ){
	// 	townhub_addons_update_message_system();
	// }

}