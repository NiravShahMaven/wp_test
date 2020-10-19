<?php

/*
Plugin Name: Booking-Vehicle
Plugin URI:
Description: Booking-Vehicle 
Version: 1.0
Author: WP-Test
Author URI:
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class booking_vehicle_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}

	/**
	 * Retrieve booking_vehicle customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}booking_vehicle";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a booking_vehicle customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}booking_vehicle",
			[ 'booking_id' => $id ],
			[ '%d' ]
		);
		return;
	}
	/**
	* Pending a booking customer record.
	*
	* @param int $id customer ID
	*/
	public static function pending_customer( $id ) {
		global $wpdb;
		$pending='Pending';
		$wpdb->update(
			"{$wpdb->prefix}booking_vehicle" ,
			array(
	            'booking_status'=>$pending
        	),
				[ 'booking_id' => $id ],
				[ '%s' ] 
		);	
	}
	/**
	* Approved a booking customer record.
	*
	* @param int $id customer ID
	*/
	public static function approved_customer( $id ) {
		global $wpdb;
		$approved='Approved';
		$wpdb->update(
			"{$wpdb->prefix}booking_vehicle" ,
			array(
	            'booking_status'=>$approved
        	),
				[ 'booking_id' => $id ],
				[ '%s' ] 
		);
	}
	/**
	* Reject a booking customer record.
	*
	* @param int $id customer ID
	*/
	public static function reject_customer( $id ) {
		global $wpdb;
		$reject='Reject';
		$wpdb->update(
			"{$wpdb->prefix}booking_vehicle" ,
			array(
	            'booking_status'=>$reject
        	),
				[ 'booking_id' => $id ],
				[ '%s' ] 
		);
	}
	/**
	* On the way a booking customer record.
	*
	* @param int $id customer ID
	*/
	public static function on_the_way_customer( $id ) {
		global $wpdb;
		$on_the_way='On the way';
		$wpdb->update(
			"{$wpdb->prefix}booking_vehicle" ,
			array(
	            'booking_status'=>$on_the_way
        	),
				[ 'booking_id' => $id ],
				[ '%s' ] 
		);
	}
	/**
	* complete a booking customer record.
	*
	* @param int $id customer ID
	*/
	public static function complete_customer( $id ) {
		global $wpdb;
		$complete='Complete';
		$wpdb->update(
			"{$wpdb->prefix}booking_vehicle" ,
			array(
	            'booking_status'=>$complete
        	),
				[ 'booking_id' => $id ],
				[ '%s' ] 
		);
	}
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}booking_vehicle";

		return $wpdb->get_var( $sql );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No Booking avaliable.', 'sp' );
	}
	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'first_name':
			case 'last_name':
			case 'email':
			case 'phone':
			case 'vehicle_type':
			case 'vehicle':
			case 'vehicle_price':
			case 'booking_status':
			case 'message':
			case 'created_date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk[]" value="%s" />', $item['booking_id']
		);
	}
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_customer');
		$pending_nonce = wp_create_nonce( 'sp_pending_customer');
		$approved_nonce = wp_create_nonce( 'sp_approved_customer');
		$reject_nonce = wp_create_nonce( 'sp_reject_customer');
		$on_the_way_nonce = wp_create_nonce( 'sp_on_the_way_customer');
		$complete_nonce = wp_create_nonce( 'sp_complete_customer');

		$title = '<strong>' . $item['d_name'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['booking_id'] ), $delete_nonce ),

			'pending' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Pending</a>', esc_attr( $_REQUEST['page'] ), 'pending', absint( $item['booking_id'] ), $pending_nonce ),

			'approved' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Approved</a>', esc_attr( $_REQUEST['page'] ), 'approved', absint( $item['booking_id'] ), $approved_nonce ),

			'reject' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Reject</a>', esc_attr( $_REQUEST['page'] ), 'reject', absint( $item['booking_id'] ), $reject_nonce ),

			'on_the_way' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">On the way</a>', esc_attr( $_REQUEST['page'] ), 'on_the_way', absint( $item['booking_id'] ), $on_the_way_nonce ),

			'complete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Complete</a>', esc_attr( $_REQUEST['page'] ), 'complete', absint( $item['booking_id'] ), $complete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb' => '<input type="checkbox" />',
			'first_name'=> __( 'First Name Type', 'sp' ),
			'last_name'=> __( 'Last Name', 'sp' ),
			'email'=> __( 'Email', 'sp' ),
			'phone'=> __( 'Phone', 'sp' ),
			'vehicle'=> __( 'Vehicle', 'sp' ),
			'vehicle_type'=> __( 'Type', 'sp' ),
			'vehicle_price'	=> __( 'Price ($)', 'sp' ),
			'booking_status'=> __( 'Status', 'sp' ),
			'message'=> __( 'Message', 'sp' ),
			'created_date'=> __( 'Date', 'sp' )
		];
		return $columns;
	}
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'booking_status' => array( 'booking_status', true),
			'vehicle_type' => array( 'vehicle_type', true)
		);

		return $sortable_columns;
	}
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete',
			'bulk-pending' => 'Pending',
			'bulk-approved'=> 'Approved',
			'bulk-reject'=> 'Reject',
			'bulk-on_the_way'=> 'On the way',
			'bulk-complete'=> 'Complete'
		];

		return $actions;
	}
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_customers( $per_page, $current_page );
	}
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_customer( absint( $_GET['customer'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_customer( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}

		//for pending start
		if ( 'pending' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_pending_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::pending_customer( absint( $_GET['customer'] ) );
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the pending bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-pending' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-pending' )
		) {
			$pending_ids = esc_sql( $_POST['bulk'] );
			foreach ( $pending_ids as $id ) {
				self::pending_customer( $id );
			}
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
		//for pending END

		//for approved start
		if ( 'approved' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_Approved_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::approved_customer( absint( $_GET['customer'] ) );
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the approved bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approved' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approved' )
		) {
			$approved_ids = esc_sql( $_POST['bulk'] );
			foreach ( $approved_ids as $id ) {
				self::approved_customer( $id );
			}
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
		//for approved END

		//for reject start
		if ( 'reject' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_reject_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::reject_customer( absint( $_GET['customer'] ) );
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the reject bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-reject' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-reject' )
		) {
			$reject_ids = esc_sql( $_POST['bulk'] );
			foreach ( $reject_ids as $id ) {
				self::reject_customer( $id );
			}
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
		//for reject END

		//for on_the_way start
		if ( 'on_the_way' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_on_the_way_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::on_the_way_customer( absint( $_GET['customer'] ) );
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the on_the_way bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-on_the_way' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-on_the_way' )
		) {
			$on_the_way_ids = esc_sql( $_POST['bulk'] );
			foreach ( $on_the_way_ids as $id ) {
				self::on_the_way_customer( $id );
			}
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
		//for on_the_way END

		//for complete start
		if ( 'complete' === $this->current_action() ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_complete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::complete_customer( absint( $_GET['customer'] ) );
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
		}
		// If the complete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-complete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-complete' )
		) {
			$complete_ids = esc_sql( $_POST['bulk'] );
			foreach ( $complete_ids as $id ) {
				self::complete_customer( $id );
			}
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
		//for complete END
	}
}

class DA_Plugin {
	// class instance
	static $instance;
	// customer WP_List_Table object
	public $customers_obj;
	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {
		$hook = add_menu_page(
			'Booking Vehicle',
			'Booking Vehicle',
			'manage_options',
			'booking_vehicle',
			[ $this, 'plugin_settings_page' ],
			'dashicons-calendar-alt'
		);
		add_action( "load-$hook", [ $this, 'screen_option' ] );
	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Shortcode For The Booking Form : [booking-form-01]</h2>
			<h2>Booking Vehicle</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2" style="margin-right: 0px !important;">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->customers_obj->prepare_items();
								$this->customers_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}
	/**
	 * Screen options
	 */
	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 5,
			'option'  => 'customers_per_page'
		];
		add_screen_option( $option, $args );
		$this->customers_obj = new booking_vehicle_List();
	}
	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
add_action( 'plugins_loaded', function () {
	DA_Plugin::get_instance();
} );

/*Booking form code start*/
function booking_form( $first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message ) { 
    $style = "<style>
    	div {
        	margin-bottom:2px;
    	}  
 	   input{
    	    margin-bottom:4px;
    	}
    </style>";
    echo $style;

    $formHtml = "<form action=". $_SERVER['REQUEST_URI']." method='post'>";
    $formHtml .= "<div>";
    $formHtml .= "<label for='firstnam'>First Name</label>";
    $formHtml .= "<input type='text' name='first_name' value=". ( isset( $_POST['first_name']) ? $first_name : null ) . ">";
    $formHtml .= "</div>";
     
    $formHtml .= "<div>";
    $formHtml .= "<label for='last_name'>Last Name</label>";
    $formHtml .= "<input type='text' name='last_name' value=" . ( isset( $_POST['last_name']) ? $last_name : null ) . ">";
    $formHtml .= "</div>";

    $formHtml .= "<div>";
    $formHtml .= "<label for='email'>Email</label>";
    $formHtml .= "<input type='text' name='email' value=" . ( isset( $_POST['email']) ? $email : null ) . ">";
     $formHtml .= "</div>";

    $formHtml .= "<div>";
    $formHtml .= "<label for='phone'>Phone</label>";
    $formHtml .= "<input type='text' name='phone' value=" . ( isset( $_POST['phone'] ) ? $phone : null ) . ">";
     $formHtml .= "</div>";
     
    $formHtml .= "<div>";
    $formHtml .= "<label for='vehicle_type'>Type</label>";
    	
		$categories = get_categories('category');
 
		$formHtml .= "<select name='vehicle_type'>";
		foreach($categories as $category){
		    if($category->count > 0){
		        $formHtml .="<option value='".$category->slug."'>".$category->name."</option>";
		    }
		  }
		$formHtml .= "</select>";
    	
    $formHtml .= "</div>";

    $formHtml .= "<div>";
    $formHtml .= "<label for='vehicle'>Vehicle</label>";
    $formHtml .= "<select name='vehicle' class='PostId' id='category'>";
	$formHtml .= "<option selected=''>Select Vehicle</option>";
			$args = array('post_type'=>'post');
			$the_query = get_posts( $args );
			foreach ($the_query as $post) {
				$formHtml .= "<option value=".$post->post_title.">".$post->post_title."</option>";
			}
	$formHtml .= "</select>";
	$formHtml .= "</div>";
     
    $formHtml .= "<div>";
    $formHtml .= "<label for='vehicle'>Vehicle</label>";
    $formHtml .= "<input type='text' name='vehicle' value=" . ( isset( $_POST['vehicle']) ? $vehicle : null ) . ">";
     $formHtml .= "</div>";
     
    $formHtml .= "<div>";
    $formHtml .= "<label for='vehicle_price'>Price</label>";
    $formHtml .= "<input type='text' name='vehicle_price' value=" . ( isset( $_POST['vehicle_price']) ? $vehicle_price : null ) . ">";
    $formHtml .= "</div>";

    $formHtml .= "<div>";
    $formHtml .= "<input type='hidden' name='booking_status' value='Pending'>";
    $formHtml .= "</div>";

    $formHtml .= "<div>";
    $formHtml .= "<label for='message'>Message</label>";
    $formHtml .= "<textarea name='message'>" . ( isset( $_POST['message']) ? $message : null ) . "</textarea>";
    $formHtml .= "</div>";
    $formHtml .= "<input type='submit' name='submit' value='Submit'/>";
    $formHtml .= "</div>";
    echo $formHtml;
}

function booking_validation($first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message )  {
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( empty( $first_name ) || empty( $email ) || empty( $phone ) || empty( $vehicle_type ) || empty( $vehicle ) || empty( $vehicle_price ) ) {
	    $reg_errors->add('field', 'Required form field is missing');
	}
	if ( !is_email( $email ) ) {
    	$reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    	$reg_errors->add( 'email', 'Email Already in use' );
	}
	if ( is_wp_error( $reg_errors ) ) {
	    foreach ( $reg_errors->get_error_messages() as $error ) {
	        echo '<div>';
	        echo '<strong>ERROR</strong>:';
	        echo $error . '<br/>';
	        echo '</div>';   
	    }
	}
}

function complete_booking() {
    global $reg_errors, $first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'vehicle_type' => $vehicle_type,
        'vehicle' => $vehicle,
        'vehicle_price' => $vehicle_price,
        'booking_status' => 'Pending',
        'message' => $message,
        );

        $user = wp_insert_user( $userdata );

        global $wpdb;
		$insert = $wpdb->insert("{$wpdb->prefix}booking_vehicle" ,$userdata);
		if ($insert) {
			echo 'Booking complete';   
		}
		else{echo 'Booking Fail!'; }
        
    }
}

function custom_booking_function() {
    if ( isset($_POST['submit'] ) ) {
        booking_validation(
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['vehicle_type'],
        $_POST['vehicle'],
        $_POST['vehicle_price'],
        $_POST['message']
        );
         
        // sanitize user form input
        global $first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message;
        $first_name   =   sanitize_user( $_POST['first_name'] );
        $last_name   =   sanitize_user( $_POST['last_name'] );
        $email      =   sanitize_email( $_POST['email'] );
        $phone =   sanitize_text_field( $_POST['phone'] );
        $vehicle_type  =   sanitize_text_field( $_POST['vehicle_type'] );
        $vehicle =   sanitize_text_field( $_POST['vehicle'] );
        $vehicle_price  =   sanitize_text_field( $_POST['vehicle_price'] );
        $message        =   esc_textarea( $_POST['message'] );
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_booking($first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message);
    }
 
    	booking_form($first_name, $last_name, $email, $phone, $vehicle_type, $vehicle , $vehicle_price, $message);
}

// booking a new shortcode: [booking-form-01]
add_shortcode( 'booking-form-01', 'custom_booking_shortcode' );
 
// The callback function that will replace [book]
function custom_booking_shortcode() {
    ob_start();
    custom_booking_function();
    return ob_get_clean();
}
