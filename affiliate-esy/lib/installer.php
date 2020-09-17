<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Backend settings page class, can have settings fields or data table
 */
if ( ! class_exists( 'AFFILIATE_ESY_THEME_INSTALLER' ) ) {

	final class AFFILIATE_ESY_THEME_INSTALLER {


    public function __construct() {

      $exists = $this->theme_exists();
      if ($exists != false) {

        $active = $this->theme_active();
        if (!$active) {

          $this->activate_theme();
        }
      } else {

        $copy = $this->import_theme();
        if ($copy) {

          $this->activate_theme();
        }
      }
    }


    public function activate_theme() {

      switch_theme('mysocial');
    }


    public function import_theme() {

      $source = ABSPATH . 'wp-content/plugins/affiliate-esy/storage/theme/mysocial/';
      $dest = ABSPATH . 'wp-content/themes/mysocial/';
      $copy = $this->recurse_copy($source, $dest);

      return $copy;
    }


    public function theme_active() {

      $active = false;
      $stylesheet = get_option('stylesheet');
      if ($stylesheet == 'mysocial') {

        $active = true;
      }

      return $active;
    }


    public function theme_exists() {

      $theme_exists = false;

      $all_themes = wp_get_themes();
      foreach ($all_themes as $theme) {

        $name = $theme->get('Name');
        $author = $theme->get('Author');
        $text_domain = $theme->get('TextDomain');
        if ($name == 'MySocial' && $author == 'HappyThemes' && $text_domain == 'mysocial') {

          $theme_exists = true;
        }
      }

      return $theme_exists;
    }


    public function recurse_copy($src,$dst) {

      $dir = opendir($src);
      @mkdir($dst);
      while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
          if ( is_dir($src . '/' . $file) ) {
              $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
          }
          else {
              copy($src . '/' . $file,$dst . '/' . $file);
          }
        }
      }
      closedir($dir);

      return true;
    }
  }
}
