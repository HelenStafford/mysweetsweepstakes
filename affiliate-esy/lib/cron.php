<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * Add cron task callback
 *
 */
if ( ! class_exists( 'AFFILIATE_ESY_CRON' ) ) {

	final class AFFILIATE_ESY_CRON {

		//Called in autoload.php
		public function schedule_task($task) {

			if( ! $task ) {
				return false;
			}

			$required_keys = array(
						'timestamp',
						'recurrence',
						'hook'
					);
			$missing_keys = array();
			foreach( $required_keys as $key ){
				if( ! array_key_exists( $key, $task ) ) {
					$missing_keys[] = $key;
				}
			}

			if( ! empty( $missing_keys ) ){
				return false;
			}

			if( wp_next_scheduled( $task['hook'] ) ){
				wp_clear_scheduled_hook($task['hook']);
			}

			wp_schedule_event($task['timestamp'], $task['recurrence'], $task['hook']);
			return true;
		}
	}
} ?>
