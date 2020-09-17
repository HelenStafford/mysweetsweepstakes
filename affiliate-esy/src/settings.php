<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Backend settings page class, can have settings fields or data table
 */
if ( ! class_exists( 'AFFILIATE_ESY_SETTINGS' ) ) {

	final class AFFILIATE_ESY_SETTINGS {


		public $capability;
		public $menuPage;
		public $subMenuPage;


		// Add basic actions for menu and settings
		public function __construct() {

			$this->capability = 'manage_options';
			$this->menuPage = array(
				'name' => __( 'AffiliateEsy', 'affiliate-esy' ),
				'heading' => __( 'AffiliateEsy', 'affiliate-esy' ),
				'slug' => 'affiliate-esy' );
			$this->subMenuPage = array(
								array(
									'name' => __( 'Settings', 'affiliate-esy' ),
									'heading' => __( 'Settings', 'affiliate-esy' ),
									'slug' => 'affiliate-esy',
									'parent_slug' => 'affiliate-esy',
									'callback' => 'menu_page_callback',
									'screen' => false
								),
								array(
									'name' => __( 'Imports', 'affiliate-esy' ),
									'heading' => __( 'Imports', 'affiliate-esy' ),
									'slug' => 'affiliate-esy-force-import',
									'parent_slug' => 'affiliate-esy',
									'callback' => 'menu_page_callback_force_import',
									'screen' => true
								)
							);

			add_action( 'admin_init', array( $this, 'add_settings' ) );
			add_action( 'admin_menu', array( $this, 'menu_page' ) );
			add_action( 'admin_menu', array( $this, 'sub_menu_page' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );

			$this->update_data();
		}


		//Update the submitted settings data
		public function update_data() {

			if (isset($_POST['aesy_settings_submit'])) {

				$per_day = (isset($_POST['aesy_cron_import_per_day']) ? $_POST['aesy_cron_import_per_day'] : false);
				$import_ok = (isset($_POST['aesy_image_import_ok']) ? $_POST['aesy_image_import_ok'] : false);
				$affiliate_ID = (isset($_POST['aesy_affiliate_ID']) ? $_POST['aesy_affiliate_ID'] : false);

				update_option('aesy_cron_import_per_day', $per_day);
				update_option('aesy_image_import_ok', $import_ok);
				update_option('aesy_affiliate_ID', $affiliate_ID);

				$this->cron_activation();
			}
		}


		//Custom corn class, register it while activation
		public function cron_activation() {

			if ( class_exists( 'AFFILIATE_ESY_CRON' ) ) {
				$cron = new AFFILIATE_ESY_CRON();
				$schedule = $cron->schedule_task(
							array(
							'timestamp' => current_time('timestamp'),
							'recurrence' => 'daily',
							'hook' => 'affiliate_esy_cron'
						) );
			}
		}


		// Add a sample main menu page callback
		public function menu_page() {

			if ($this->menuPage) {
				add_menu_page(
					$this->menuPage['name'],
					$this->menuPage['heading'],
					$this->capability,
					$this->menuPage['slug'],
					array( $this, 'menu_page_callback' )
				);
			}
		}


		//Add a sample Submenu page callback
		public function sub_menu_page() {

			if ($this->subMenuPage) {
				foreach ($this->subMenuPage as $value) {
					$hook = add_submenu_page(
								$value['parent_slug'],
								$value['name'],
								$value['heading'],
								$this->capability,
								// For the first submenu page, slug should be same as menupage.
								$value['slug'],
								// For the first submenu page, callback should be same as menupage.
								array( $this, $value['callback'] )
							);
					if ($value['screen']) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}
		}


		// Menu page callback
		public function menu_page_callback() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<?php settings_errors(); ?>

					<form method="post" action="">
						<?php settings_fields("cron_settings_id");
						do_settings_sections("cron_settings_section");
						submit_button( __( 'Save', 'textdomain' ), 'primary', 'aesy_settings_submit' ); ?>
					</form>
				<br class="clear">
			</div>
		<?php
		}


		// Menu page callback
		public function menu_page_callback_force_import() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?>
				<a href="?page=<?php echo $_GET['page']; ?>&import=true<?php echo (isset($_GET['paged']) ? '&paged=' . $_GET['paged'] : false); ?>" class="page-title-action"><?php _e( 'Force Import', 'affiliate-esy' ); ?></a>
				</h1>
				<hr class="wp-header-end">
				<?php settings_errors();

					/**
					 * Following is the force import table
					 */
					 $table_css = '<style type="text/css">
					 							.wp-list-table .column-ID { width: 5%; }
												.wp-list-table .column-title { width: 60%; }
												.wp-list-table .column-date { width: 25%; }
												.wp-list-table .column-permalink { width: 10%; }
					 						</style>';
					 echo $table_css;

					 $table = new AFFILIATE_ESY_TABLE();
					 $table->prepare_items();
					 $table->display(); ?>
				<br class="clear">
			</div>
		<?php
		}


		//Set screen option
		public function set_screen($status, $option, $value) {

			if ( 'aesy_item_per_page' == $option ) return $value;
		}


		//Set screen option for Items table
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', 'affiliate-esy' ),
						'default' => 10,
						'option'  => 'aesy_item_per_page'
						);
			add_screen_option( $option, $args );
			$this->Table = new AFFILIATE_ESY_TABLE();
		}


		//Add different types of settings and corrosponding sections
		public function add_settings() {

			add_settings_section( 'cron_settings_id', __( 'Import settings', 'affiliate-esy' ), array( $this,'cron_section_cb' ), 'cron_settings_section' );

			register_setting( 'cron_settings_id', 'aesy_cron_import_per_day' );
			add_settings_field( 'aesy_cron_import_per_day', __( 'Articles to import per day', 'affiliate-esy' ), array( $this, 'cron_settings_field_cb' ), 'cron_settings_section', 'cron_settings_id' );
			register_setting( 'cron_settings_id', 'aesy_image_import_ok' );
			add_settings_field( 'aesy_image_import_ok', __( 'Import images', 'affiliate-esy' ), array( $this, 'image_settings_field_cb' ), 'cron_settings_section', 'cron_settings_id' );
			register_setting( 'cron_settings_id', 'aesy_affiliate_ID' );
			add_settings_field( 'aesy_affiliate_ID', __( 'Clickbank affiliate ID', 'affiliate-esy' ), array( $this, 'affiliate_ID_field_cb' ), 'cron_settings_section', 'cron_settings_id' );
		}


		//Section description
		public function cron_section_cb() {

			echo '<p class="description">' . __( 'Set up import instructions', 'affiliate-esy' ) . '</p>';
		}


		//Field explanation
		public function cron_settings_field_cb() {

			echo '<input type="number" class="small-text" name="aesy_cron_import_per_day" min="0" max="30" value="' . get_option('aesy_cron_import_per_day') . '" />';
		}


		//Field explanation
		public function image_settings_field_cb() {

			$import_image = get_option('aesy_image_import_ok');
			echo '<input type="radio" name="aesy_image_import_ok" value="true"' . checked( $import_image, 'true', false ) . ' /> ' . __( 'Yes', 'affiliate-esy' );
			echo '&nbsp;&nbsp;';
			echo '<input type="radio" name="aesy_image_import_ok" value="false"' . checked( $import_image, 'false', false ) . ' /> ' . __( 'No', 'affiliate-esy' );
		}


		//Field explanation
		public function affiliate_ID_field_cb() {

			echo '<input type="text" class="medium-text" name="aesy_affiliate_ID" value="' . get_option('aesy_affiliate_ID') . '" />';
		}
	}
} ?>
