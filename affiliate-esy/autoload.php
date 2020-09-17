<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Main plugin object to define the plugin
if ( ! class_exists( 'AFFILIATE_ESY_BUILD' ) ) {

	final class AFFILIATE_ESY_BUILD {


		public function installation() {

			if (class_exists('AFFILIATE_ESY_INSTALL')) {

				$install = new AFFILIATE_ESY_INSTALL();
				$install->textDomin = 'affiliate-esy';
				$install->phpVerAllowed = '5.4';
				$install->execute();
			}
		}


		public function db_install() {

			if ( class_exists( 'AFFILIATE_ESY_DB' ) ) {
				$db = new AFFILIATE_ESY_DB();
				$db->table = 'affiliate_esy';
				$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
							article_no smallint(3) NOT NULL,
							insert_post_id mediumint(9) NOT NULL,
							title text,
							date datetime NOT NULL,
							permalink varchar(512) NOT NULL,
							UNIQUE KEY ID (ID)";
				$db->build();
			}

			if (get_option('_aesy_db_exist') == '0') {
				add_action( 'admin_notices', array( $this, 'db_error_msg' ) );
			}
		}



		//Notice of DB
		public function db_error_msg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Database table Not installed correctly.', 'textdomain' ); ?></p>
			</div>
			<?php
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


		public function custom_cron_hook_cb() {

			add_action('affiliate_esy_cron', array( $this, 'do_cron_job_function'));
		}


		public function do_cron_job_function() {

			$attempts = get_option('aesy_cron_import_per_day');
			if (false != $attempts && $attempts != 0) {

				for ($i=1; $i <= $attempts; $i++) {
					if ( class_exists( 'AFFILIATE_ESY_IMPORT' ) ) new AFFILIATE_ESY_IMPORT();
				}
			}
		}


		public function force_import() {

			if (isset($_GET['page']) && isset($_GET['import'])) {

				$page = $_GET['page'];
				$import = $_GET['import'];
				if ($page == 'affiliate-esy-force-import' && $import == 'true') {

					if ( class_exists( 'AFFILIATE_ESY_IMPORT' ) ) new AFFILIATE_ESY_IMPORT();
				}
			}
		}

		public function cron_uninstall() {

			wp_clear_scheduled_hook('affiliate_esy_cron');
		}


		//Include settings pages
		public function theme_installer() {

			if ( class_exists( 'AFFILIATE_ESY_THEME_INSTALLER' ) ) new AFFILIATE_ESY_THEME_INSTALLER();
		}


		//Include settings pages
		public function settings() {

			if ( class_exists( 'AFFILIATE_ESY_SETTINGS' ) ) new AFFILIATE_ESY_SETTINGS();
		}


		//Add functionality files
		public function functionality() {

			require_once ('src/db.php');
			require_once ('src/install.php');
			require_once ('src/settings.php');
		}



		//Call the dependency files
		public function helpers() {

			require_once ('lib/cron.php');
			require_once ('lib/table.php');
			require_once ('lib/import.php');
			require_once ('lib/installer.php');
		}



		public function __construct() {

			$this->helpers();
			$this->functionality();

			register_activation_hook( AFFILIATE_ESY_FILE, array( $this, 'db_install' ) );
			register_activation_hook( AFFILIATE_ESY_FILE, array($this, 'cron_activation' ));
			register_activation_hook( AFFILIATE_ESY_FILE, array($this, 'theme_installer' ));

			register_uninstall_hook( AFFILIATE_ESY_FILE, array( 'AFFILIATE_ESY_BUILD', 'cron_uninstall' ) );

			add_action('init', array($this, 'installation'));

			$this->settings();
			add_action('init', array($this, 'custom_cron_hook_cb'));
			add_action('init', array($this, 'force_import'));
		}
	}
} ?>
