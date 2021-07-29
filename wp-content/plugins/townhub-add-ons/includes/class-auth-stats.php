<?php
/* add_ons_php */

defined('ABSPATH') || exit;

class Esb_Class_Auth_Stats
{
    private static $_instance;
    protected $menu_slug = 'author_stats';
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu()
    {
        $this->slug = add_menu_page(
            _x('Author Statistics', 'TownHub Add-Ons', 'townhub-add-ons'),
            _x('Author Stats', 'TownHub Add-Ons', 'townhub-add-ons'),
            'manage_options',
            $this->menu_slug,
            array($this, 'author_stats_contents'),
            'dashicons-money',
            42
        );
    }

    public function author_stats_list(){
        $singleUrl = menu_page_url( $this->menu_slug, false );
        $paged = isset($_GET['paged']) && (int)$_GET['paged'] > 0 ? (int)$_GET['paged'] : 1; // get_query_var('paged') ? get_query_var('paged') : 1;
        $number = get_option( 'posts_per_page', 5 );
        $users_args = array( 
            'role__in'          => array('administrator','listing_author','seller','shop_manager','wcfm_vendor'), 
            'paged'             => $paged,
            'number'            => $number,
            'offset'            => ( $paged - 1 ) * $number,
            'count_total'       => true,
        );
        $authUsersQuery = new WP_User_Query( $users_args );
        $authUsers = $authUsersQuery->get_results();
        ?>
        <div class="wrap">
            <h2><?php _ex('Author Statistics', 'TownHub Add-Ons', 'townhub-add-ons');?></h2>
            
                <?php 
                if( !empty($authUsers) ):
                $totals = ceil( $authUsersQuery->get_total() / $number ); ?>
                <div class="tablenav top lauth-stats-nav">
                    
                    <h2 class="screen-reader-text">Pages list navigation</h2>
                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(_x( '%d items', 'Author Stats', 'townhub-add-ons' ), $authUsersQuery->get_total() ) ?></span>
                        <?php if ( $totals > 1 )  {
                             // get the current page
                             // if ( !$current_page = get_query_var('paged') )
                             //      $current_page = 1;
                             // structure of "format" depends on whether we're using pretty permalinks
                             // if( get_option('permalink_structure') ) {
                             //     $format = '&paged=%#%';
                             // } else {
                             //     $format = 'page/%#%/';
                             // }
                            $big = 999999999; // need an unlikely integer
                            $page_links = paginate_links(array(
                                    'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                    'format'   => '&paged=%#%',
                                    'current'  => $paged,
                                    'total'    => $totals,
                                    'mid_size' => 4,
                                    'type'     => 'plain', // 'list',
                                    'prev_text' => _x( '&laquo;', 'Author Stats', 'townhub-add-ons' ),
                                    'next_text' => _x( '&raquo;', 'Author Stats', 'townhub-add-ons' ),
                             ));
                            $re = '/class="([^\"]*)(page-numbers)([^\"]*)"/m';
                            $subst = 'class="$1 button $2$3"';
                            echo preg_replace($re, $subst, $page_links);
                        } ?>
                        
                    </div>
                    <br class="clear">
                    
                </div>
            <?php endif; ?>

                <h2 class="screen-reader-text">Pages list</h2>
                
                <table class="wp-list-table widefat fixed striped table-view-list pages">
                    <thead>
                        <tr>
                            <td id="cb" class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-1"><?php _ex( 'Select all', 'Author Stats', 'townhub-add-ons' ); ?></label>
                                <input id="cb-select-all-1" type="checkbox">
                            </td>
                            <th scope="col" id="author" class="manage-column column-author column-primary sortable desc">
                                <!-- <a href="http://localhost:8888/townhub/wp-admin/edit.php?post_type=listing&amp;orderby=title&amp;order=asc"><span><?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?></span><span class="sorting-indicator"></span></a> -->
                                <?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" id="author-plan" class="manage-column column-author_plan"><?php _ex( 'Membership Plan', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" id="total-sales" class="manage-column column-total_sales"><?php _ex( 'Total All-Time Sales (Including tax and fee)', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" id="number-of-withdrawals" class="manage-column column-number_of_withdrawals"><?php _ex( '# of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" id="withdrawals-total" class="manage-column column-withdrawals_total"><?php _ex( 'Total of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" id="total-commissions-paid" class="manage-column column-total_commissions_paid sortable asc">
                                <!-- <a href="http://localhost:8888/townhub/wp-admin/edit.php?post_type=listing&amp;orderby=date&amp;order=desc"><span><?php // _ex( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?></span><span class="sorting-indicator"></span></a> -->
                                <?php _ex( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" id="current-balance" class="manage-column column-current_balance"><?php _ex( 'Current Account Balance', 'Author Stats', 'townhub-add-ons' ); ?></th>
                        </tr>
                    </thead>

                    <tbody id="the-list">

                        <?php 
                        
                        // Array of WP_User objects.
                        foreach ( $authUsers as $user ) {
                            $authUrl = add_query_arg( 'author', $user->ID, $singleUrl );
                            ?>
                            <tr id="auth-<?php echo esc_attr($user->ID);?>" class="iedit author-self level-0 auth-<?php echo esc_attr($user->ID);?> type-listing status-publish has-post-thumbnail hentry">
                                <th scope="row" class="check-column">
                                    <label class="screen-reader-text" for="cb-select-<?php echo esc_attr($user->ID);?>"><?php echo $user->display_name; ?></label>
                                    <input id="cb-select-<?php echo esc_attr($user->ID);?>" type="checkbox" name="post[]" value="<?php echo esc_attr($user->ID);?>">
                                    <div class="locked-indicator">
                                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                                        <span class="screen-reader-text"><?php echo $user->display_name; ?></span>
                                    </div>
                                </th>
                                <td class="title column-author column-primary page-title" data-colname="<?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?>">
                                    <div class="locked-info">
                                        <span class="locked-avatar"></span> 
                                        <span class="locked-text"></span>
                                    </div>
                                    <strong>
                                        <a class="row-title" href="<?php echo $authUrl;?>" aria-label="“<?php echo $user->display_name; ?>” (Details)"><?php echo $user->display_name; ?></a>
                                        <?php // echo $user->display_name; ?>
                                    </strong>
                                    <div class="row-infos"><?php echo $user->user_email; ?></div>
                                </td>
                                <?php 
                                $plan_id = Esb_Class_Membership::current_plan($user->ID); ?>
                                <td class="author column-author_plan" data-colname="<?php echo esc_attr_x( 'Membership Plan', 'Author Stats', 'townhub-add-ons' ); ?>"><a href="<?php echo get_edit_post_link( $plan_id ); ?>"><?php echo get_the_title( $plan_id ); ?></a></td>
                                <td class="column-total_sales" data-colname="<?php  echo esc_attr_x( 'Total All-Time Sales', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo townhub_addons_get_price_formated( Esb_Class_Earning::total_sales( $user->ID ) ); ?></td>
                                <td class="column-number_of_withdrawals" data-colname="<?php  echo esc_attr_x( '# of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo Esb_Class_Earning::withdrawals_count( $user->ID ); ?></td>

                                <td class="column-withdrawals_total" data-colname="<?php  echo esc_attr_x( 'Total of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo townhub_addons_get_price_formated( abs( Esb_Class_Earning::withdrawals_total( $user->ID ) ) ); ?></td>
                                <td class="column-total_commissions_paid" data-colname="<?php  echo esc_attr_x( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo townhub_addons_get_price_formated( Esb_Class_Earning::commissions_paid( $user->ID ) ); ?></td>
                                <td class="column-current_balance" data-colname="<?php  echo esc_attr_x( 'Current Account Balance', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo townhub_addons_get_price_formated( Esb_Class_Earning::getBalance( $user->ID ) ); ?></td>
                                
                            </tr>
                    <?php
                            
                        }
                        ?>
                                    
                                    

                    </tbody>

                    <tfoot>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-2"><?php _ex( 'Select all', 'Author Stats', 'townhub-add-ons' ); ?></label>
                                <input id="cb-select-all-2" type="checkbox">
                            </td>
                            <th scope="col" class="manage-column column-author column-primary sortable desc">
                                <!-- <a href="http://localhost:8888/townhub/wp-admin/edit.php?post_type=listing&amp;orderby=title&amp;order=asc"><span><?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?></span><span class="sorting-indicator"></span></a> -->
                                <?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" class="manage-column column-author_plan"><?php _ex( 'Membership Plan', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-total_sales"><?php _ex( 'Total All-Time Sales', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-number_of_withdrawals"><?php _ex( '# of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-withdrawals_total"><?php _ex( 'Total of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-total_commissions_paid sortable asc">
                                <!-- <a href="http://localhost:8888/townhub/wp-admin/edit.php?post_type=listing&amp;orderby=date&amp;order=desc"><span><?php // _ex( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?></span><span class="sorting-indicator"></span></a> -->
                                <?php _ex( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" class="manage-column column-current_balance"><?php _ex( 'Current Account Balance', 'Author Stats', 'townhub-add-ons' ); ?></th>
                        </tr>
                    </tfoot>

                </table>

            
        </div>
        <?php
    }

    public function author_stats_single($author_id){
        // $_GET for set paged
        $earnings = Esb_Class_Earning::getEarningsPosts($author_id, $_GET);
        ?>
        <div class="wrap">
            <h2><?php _ex('Author Earnings', 'TownHub Add-Ons', 'townhub-add-ons');?></h2>

            <!-- <h3><?php _ex('Author Earnings', 'Author Stats', 'townhub-add-ons');?></h3> -->
            <?php 
                if( !empty($earnings['posts']) ):
            ?>
                <div class="tablenav top lauth-stats-nav">

                    <p style="float: left;"><a href="<?php menu_page_url( $this->menu_slug, true ); ?>"><?php esc_html_e( '← Back to Authors', 'townhub-add-ons' ); ?></a></p>

                    <h2 class="screen-reader-text">Pages list navigation</h2>
                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(_x( '%d items', 'Author Stats', 'townhub-add-ons' ), $earnings['pagi']['found_posts'] ); ?></span>
                        <?php if ( $earnings['pagi']['pages'] > 1 )  {
                            $big = 999999999; // need an unlikely integer
                            $page_links = paginate_links(array(
                                    'base'      => str_replace( $big, '%#%', add_query_arg( 'paged', $big, menu_page_url( $this->menu_slug, false ) ) ), // str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                    'format'   => '&paged=%#%',
                                    'current'  => $earnings['pagi']['paged'],
                                    'total'    => $earnings['pagi']['pages'],
                                    'mid_size' => 4,
                                    'type'     => 'plain', // 'list',
                                    'prev_text' => _x( '&laquo;', 'Author Stats', 'townhub-add-ons' ),
                                    'next_text' => _x( '&raquo;', 'Author Stats', 'townhub-add-ons' ),
                                    
                             ));
                            $re = '/class="([^\"]*)(page-numbers)([^\"]*)"/m';
                            $subst = 'class="$1 button $2$3"';
                            echo preg_replace($re, $subst, $page_links);
                        } ?>
                    </div>
                    <br class="clear">
                    
                </div>
            <?php endif; ?>

            
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                    <thead>
                        <tr>
                            <th scope="col" class="manage-column column-author column-primary sortable desc">
                                <?php _ex( 'Booking ID', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" class="manage-column column-author_plan"><?php _ex( 'Booking Title', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-total_sales"><?php _ex( 'Total', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-number_of_withdrawals"><?php _ex( 'VAT - Services', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-withdrawals_total"><?php _ex( 'Author Fee', 'Author Stats', 'townhub-add-ons' ); ?></th>
                            <th scope="col" class="manage-column column-total_commissions_paid sortable asc">
                                <?php _ex( 'Author Earning', 'Author Stats', 'townhub-add-ons' ); ?>
                            </th>
                            <th scope="col" class="manage-column column-current_balance"><?php _ex( 'Date', 'Author Stats', 'townhub-add-ons' ); ?></th>
                        </tr>
                    </thead>

                    <tbody id="the-list">

                        <?php
                        if( !empty($earnings['posts']) ):

                            foreach ($earnings['posts'] as $earning) {
                                ?>
                                <tr id="earning-<?php echo esc_attr( $earning->order_id );?>" class="iedit author-self level-0 earning-earning-<?php echo esc_attr( $earning->order_id );?> type-listing status-publish has-post-thumbnail hentry">
                                    <td class="title column-author column-primary page-title" data-colname="<?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?>">
                                        <?php echo $earning->order_id; ?>
                                    </td>
                                    <td class="title column-author column-primary page-title" data-colname="<?php _ex( 'Author', 'Author Stats', 'townhub-add-ons' ); ?>">
                                        <strong>
                                            <?php echo preg_replace('/<br>#\s*\d+/m', '', $earning->order_data) ; ?>
                                        </strong>
                                        
                                    </td>
                                    
                                    <td class="author column-author_plan" data-colname="<?php echo esc_attr_x( 'Membership Plan', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo $earning->total; ?></td>
                                    <td class="column-total_sales" data-colname="<?php  echo esc_attr_x( 'Total All-Time Sales', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo $earning->vatSer; ?></td>
                                    <td class="column-number_of_withdrawals" data-colname="<?php  echo esc_attr_x( '# of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo $earning->fee; ?></td>

                                    <td class="column-withdrawals_total" data-colname="<?php  echo esc_attr_x( 'Total of Withdrawals', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo $earning->earning; ?></td>
                                    <td class="column-total_commissions_paid" data-colname="<?php  echo esc_attr_x( 'Total Commissions Paid', 'Author Stats', 'townhub-add-ons' ); ?>"><?php echo $earning->time; ?></td>

                               
                                </tr>
                            <?php
                            }

                        endif; 
                        ?>
                        
                    </tbody>

                </table>
        </div>
        <?php
    }

    public function author_stats_contents()
    {
        $author_id = isset($_GET['author']) && !empty( $_GET['author'] ) ? abs($_GET['author']) : 0; 
        if( get_user_by( 'ID', $author_id ) ){
            $this->author_stats_single($author_id);
        }else{
            $this->author_stats_list();
        }
        
    }

}

Esb_Class_Auth_Stats::getInstance();
