<?php
/**
 * Implimentation of WordPress inbuilt functions for creating an extension of a default table class.
 *
 *
 */
if ( ! class_exists( 'AFFILIATE_ESY_TABLE' ) ) {

  if ( ! class_exists( 'WP_List_Table' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/screen.php' );
      require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
  }

	final class AFFILIATE_ESY_TABLE extends WP_List_Table {


		public function __construct() {

			parent::__construct( [
				'singular' => __( 'Import', 'affiliate-esy' ),
				'plural'   => __( 'Imports', 'affiliate-esy' ),
				'ajax'     => false,
			] );
		}


		//fetch the data using custom named method function
		public static function get_Table( $per_page = 5, $page_number = 1 ) {

			global $wpdb;

			//Build the db query base
			$sql = "SELECT * FROM {$wpdb->prefix}affiliate_esy";

			//Set filters in the query using $_REQUEST
			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			//get the data from database
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}


		//Delete individual data
		public static function delete_url( $id ) {

			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}affiliate_esy", array( 'ID' => $id ), array( '%s' ) );
		}


		//If there is no data to show
		public function no_items() {

			_e( 'No imports done yet.', 'affiliate-esy' );
		}


		//How many rows are present there
		public static function record_count() {

			global $wpdb;

			//Build the db query base
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}affiliate_esy";

			return $wpdb->get_var( $sql );
		}


		//Display columns content
		public function column_title( $item ) {

			$title = sprintf( '<strong>%s</strong>', $item['title'] );

			return $title;
		}


    //Display columns content
    public function column_view( $item ) {

      $view = sprintf( '<a href="%s" class="button button-secondary" target="_blank">%s</a>', $item['permalink'], __('view', 'affiliate-esy') );

      return $view;
    }


		//set coulmns name
		public function column_default( $item, $column_name ) {

			switch ( $column_name ) {

        //case 'ID':
          //return $item[ $column_name ];
				case 'title':
					//This is the first column
					return $this->column_title( $item );
				case 'date':
          return mysql2date( 'F j, Y', $item[ $column_name ] );
				case 'permalink':
					return $this->column_view( $item );

				default:

					//Show the whole array for troubleshooting purposes
					return print_r( $item, true );
			}
		}


		//Columns callback
		public function get_columns() {

			$columns = array(
              //'ID' => __( 'ID', 'affiliate-esy' ),
							'title'	=> __( 'Title', 'affiliate-esy' ),
							'date'	=> __( 'Date', 'affiliate-esy' ),
							'permalink'	=> __( 'VIew', 'affiliate-esy' )
						);
			return $columns;
		}


		//Decide columns to be sortable by array input
		public function get_sortable_columns() {

			$sortable_columns = array();
			return $sortable_columns;
		}


		//Determine bulk actions in the table dropdown
		public function get_bulk_actions() {

			$actions = array();
			return $actions;
		}


		//Prapare the display variables for screen options
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			/** Process bulk action */
			$this->process_bulk_action();
			$per_page     = $this->get_items_per_page( 'aesy_item_per_page', 10 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();
			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) );

			$this->items = self::get_Table( $per_page, $current_page );
		}
	}
} ?>
