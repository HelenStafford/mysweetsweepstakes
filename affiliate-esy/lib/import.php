<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * Import articles
 *
 */
if ( ! class_exists( 'AFFILIATE_ESY_IMPORT' ) ) {

	final class AFFILIATE_ESY_IMPORT {


		public $table_name;


		/**
		 * The actuall process of reading file and importing data along with images
		 *
		 * @return void
		 */
    public function __construct() {

			$this->table_name = 'affiliate_esy';

      //get max article no
      $imported_upto = $this->article_no_upto();
			if ($imported_upto <= 29) {

				//find out the article to import
	      $next_article = (string) ($imported_upto + 1);

	      //import article
	      $file_data = $this->read_article($next_article);
				$import_data = $this->import($file_data);

	      //store import info to db
	      $this->store($import_data);
			}
    }


		/**
		 * Find out maximum article number imported to DB already
		 *
		 * @return 	$article_no		int 	Maximum article number found
		 */
    private function article_no_upto() {

			global $wpdb;

			$sql = "SELECT MAX(article_no) FROM {$wpdb->prefix}{$this->table_name}";
			$number = $wpdb->get_results($sql, ARRAY_A);
			$article_no_obj = $number[0];
			$article_no = $article_no_obj['MAX(article_no)'];

			return $article_no;
    }


		/**
		 * Import the content to db and images to folder
		 *
		 * @param 	$filename 	string 	Filename of the import text file
		 * @return 	$output			array 	Processed data ready to import
		 */
    private function read_article($file_name) {

			$output = false;
			$import_file = AFFILIATE_ESY_PATH . 'storage/txt/' . $file_name . '.txt';
			$affiliate_ID = get_option('aesy_affiliate_ID');

			//Read file content
			$file = fopen($import_file, "r");
			$file_content = @fread($file,filesize($import_file));
			fclose($file);

			//Fetch heading data
			preg_match('/<h1>(.*?)<\/h1>/',$file_content,$matches);
			$heading = (is_array($matches) && array_key_exists(1, $matches) ? $matches[1] : false);
			$h1_tag = (is_array($matches) && array_key_exists(0, $matches) ? $matches[0] : false);
			$filtered_content = str_replace($h1_tag,'',$file_content);

			//Fetch article affiliate link
			$link_filtered_content = str_replace('zzzzz',$affiliate_ID,$filtered_content);

			//Find and upload images
			$image_import_ok = get_option('aesy_image_import_ok');
			if ($image_import_ok && $image_import_ok == 'true') {

				preg_match('/<img src="(.*?)"/',$link_filtered_content,$matches);
				$image = (is_array($matches) && array_key_exists(1, $matches) ? $matches[1] : false);
				$upload_src = $this->upload_content_images($image);
				$content = str_replace($image, $upload_src, $link_filtered_content);
			} else {
				$content = preg_replace('/<img src="(.*?)" width="100%" \/>/', '', $link_filtered_content);
			}

			$output = compact('file_name', 'heading', 'content');

			return $output;
    }


		/**
		 * Import the content to db and images to folder
		 *
		 * @param 	$file_data 	array 	Data to import
		 * @return 	$output			array 	Data after import
		 */
		private function import($file_data) {

			$output = false;

			if ($file_data) {

				$article_no = $file_data['file_name'];
				$title = $file_data['heading'];
				$content = $file_data['content'];

				$article_info = array(
	        'post_title'  => $title,
					'post_content' => $content,
	        'post_type'   => 'post',
	        'post_status' => 'publish'
	      );

	      $insert_post_id = wp_insert_post($article_info);
				$permalink = get_permalink($insert_post_id);
				$date = current_time( 'mysql' );

				$this->image_import($article_no, $insert_post_id);

				$output = compact('article_no', 'insert_post_id', 'title', 'date', 'permalink');
			}

			return $output;
		}


		/**
		 * Upload image within content
		 *
		 * @param 	$image 							string 	Image file name
		 * @return 	$uploaded_img_url		string 	Location of image file in /uploads
		 */
		private function upload_content_images($image) {

			$img_file = AFFILIATE_ESY_PATH . 'storage/img/' . $image;
			$file_name = basename($img_file);

			$upload_file = wp_upload_bits($file_name, null, @file_get_contents( $img_file ));
			$uploaded_img_url = ($upload_file && array_key_exists('url', $upload_file) ? $upload_file['url'] : false);

			return $uploaded_img_url;
		}


		/**
		 * Upload image and set as featured image
		 *
		 * @param $img_file 				string 	Location of image file
		 * @param $insert_post_id 	int 		Post ID to insert featured image for
		 * @return void
		 */
		private function image_import($article_no, $insert_post_id) {

			$image_import_ok = get_option('aesy_image_import_ok');
			if ($image_import_ok && $image_import_ok == 'true') {

				$relationship_file = AFFILIATE_ESY_PATH . 'storage/relationship.json';

				$relationship = @file_get_contents($relationship_file);
				$relationship_arr = json_decode( $relationship, true );

				foreach ($relationship_arr as $element) {

					$txt = $element['txt'];
					if ($txt == $article_no) {

						$img = $element['img'];
						$img_file = AFFILIATE_ESY_PATH . 'storage/img/' . $img;
					}
				}

				if ($img_file) {

					$this->upload_image($img_file, $insert_post_id);
				}
			}
		}


		/**
		 * Upload image and set as featured image
		 *
		 * @param $img_file 				string 	Location of image file
		 * @param $insert_post_id 	int 		Post ID to insert featured image for
		 * @return void
		 */
		private function upload_image($img_file, $insert_post_id) {

			$file_name = basename($img_file);
			$upload_file = wp_upload_bits($file_name, null, @file_get_contents( $img_file ));
			if ( ! $upload_file['error'] ) {

  			$wp_filetype = wp_check_filetype($file_name, null );

  			$attachment = array(
    			'post_mime_type' => $wp_filetype['type'],
    			'post_parent'    => $insert_post_id,
    			'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
    			'post_content'   => '',
    			'post_status'    => 'inherit'
  			);

  			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $insert_post_id );

  			if ( ! is_wp_error( $attachment_id ) ) {
     			// if attachment post was successfully created, insert it as a thumbnail to the post.
     			require_once(ABSPATH . 'wp-admin' . '/includes/image.php');

     			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );

     			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
     			set_post_thumbnail( $insert_post_id, $attachment_id );
   			}
			}
		}


		/**
		 * Store import data upon successfully inserting post
		 *
		 * @param $import_data 	array 	Data after import
		 * @return void
		 */
    private function store($import_data) {

			if($import_data) {

				global $wpdb;

				$format = array('%d', '%d', '%s', '%s', '%s');
				$wpdb->insert($wpdb->prefix . $this->table_name, $import_data, $format);

				$insert_id = $wpdb->insert_id;

				update_option('aesy_last_insert_id', $insert_id);
			}
    }
  }
}
