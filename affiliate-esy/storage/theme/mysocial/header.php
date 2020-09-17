<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mysocial
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="true">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if (get_theme_mod('favicon', '') != null) { ?>
<link rel="icon" type="image/png" href="<?php echo esc_url( get_theme_mod('favicon', '') ); ?>" />
<?php } ?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700" rel="stylesheet">
<?php wp_head(); ?>

<?php
  	$primary_font = 'Open Sans';
  	$secondary_font = 'Open Sans';
	$primary_color = '#26b';
	$secondary_color = '#48d';	

?>
<style type="text/css" media="all">
	body,
	.breadcrumbs h1,
	.breadcrumbs h2,
	.breadcrumbs h3,
	.section-header h1,
	.section-header h2,
	.section-header h3,
	label,
	input,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="search"],
	input[type="password"],
	textarea,
	button,
	.btn,
	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	table,
	.sidebar .widget_ad .widget-title,
	.site-footer .widget_ad .widget-title {
		font-family: "<?php echo $primary_font; ?>", "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
	h1,h2,h3,h4,h5,h6 {
		font-family: "<?php echo $secondary_font; ?>", "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
	a,
	a:hover,
	a:visited,
	.site-title a:hover,
	.sf-menu ul li li a:hover,
	.sf-menu li.sfHover li a:hover,
	#primary-menu li a:hover,
	#primary-menu li.current-menu-item a,
	#primary-menu li li a:hover,
	#primary-menu li li.current-menu-item a:hover,
	#secondary-menu li li a:hover,
	.entry-related h3 span,
	.entry-meta a,
	.pagination .page-numbers:hover,
	.edit-link a,
	.author-box .author-name span a,
	.comment-reply-title small a:hover,
	.sidebar .widget ul li a:hover,
	.site-footer .widget ul li a:hover,
	.mobile-menu ul li a:hover,
	.entry-tags .tag-links a:hover:before,
	.widget_tag_cloud .tagcloud a:hover:before,
	.entry-content a,
	.entry-content a:visited,
	.page-content a,
	.page-content a:visited,
	.widget a:visited,
	.entry-related .hentry .entry-title a:hover {
		color: <?php echo $primary_color; ?>;
	}
	.pagination {
		border-bottom-color: <?php echo $secondary_color; ?>;
	}
	.mobile-menu-icon .menu-icon-close,
	.mobile-menu-icon .menu-icon-open {
		background-color: <?php echo $primary_color; ?>;
	}
	#secondary-bar,
	button,
	.btn,
	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	button:hover,
	.btn:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover,
	input[type="button"]:hover,
	.pagination .page-numbers.current {
		background-color: <?php echo $secondary_color; ?>;
	}
</style>
</head>

<body <?php body_class(); ?>>

<div id="page" class="site">

	<header id="masthead" class="site-header clear">

		<div class="site-start clear">

			<div class="container">

			<div class="site-branding">

				<?php if (get_theme_mod('logo', get_template_directory_uri().'/assets/img/logo.png') != null) { ?>
				
				<div id="logo">
					<span class="helper"></span>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php echo get_theme_mod('logo', get_template_directory_uri().'/assets/img/logo.png'); ?>" alt=""/>
					</a>
				</div><!-- #logo -->

				<?php } else { ?>

				<div class="site-title">
					<h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
				</div><!-- .site-title -->

				<?php } ?>

			</div><!-- .site-branding -->

			<?php dynamic_sidebar( 'header-ad' ); ?>

			<span class="mobile-menu-icon">
				<span class="menu-icon-open"><?php echo __('Menu', 'mysocial'); ?></span>
				<span class="menu-icon-close"><span class="genericon genericon-close"></span></span>		
			</span>	
			
			</div><!-- .container -->

		</div><!-- .site-start -->

		<div id="primary-bar" class="container">

			<nav id="primary-nav" class="main-navigation">

				<?php 
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu' ) );
					} else {
				?>

					<ul id="primary-menu" class="sf-menu">
						<li><a href="<?php echo home_url(); ?>/wp-admin/nav-menus.php"><?php echo __('Add menu for: Primary Menu', 'mysocial'); ?></a></li>
					</ul><!-- .sf-menu -->

				<?php } ?>

			</nav><!-- #primary-nav -->	

			<?php if ( get_theme_mod('header-search-on', true) ) : ?>

				<div class="header-search">
					<form id="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" name="s" class="search-input" placeholder="<?php esc_html_e('Search...', 'mysocial'); ?>" autocomplete="off">
						<button type="submit" class="search-submit"><span class="genericon genericon-search"></span></button>		
					</form>
				</div><!-- .header-search -->

			<?php endif; ?>

		</div><!-- #primary-bar -->	

		<div id="secondary-bar" class="container clear">

			<div class="container">

			<nav id="secondary-nav" class="secondary-navigation">

				<?php 
					if ( has_nav_menu( 'secondary' ) ) {
						wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'secondary-menu', 'menu_class' => 'sf-menu' ) );
					} else {
				?>

					<ul id="secondary-menu" class="sf-menu">
						<li><a href="<?php echo home_url(); ?>/wp-admin/nav-menus.php"><?php echo __('Add menu for: Secondary Menu', 'mysocial'); ?></a></li>
					</ul><!-- .sf-menu -->

				<?php } ?>

			</nav><!-- #secondary-nav -->

			</div><!-- .container -->				

		</div><!-- .secondary-bar -->

		<div class="mobile-menu clear">

			<div class="container">

			<?php 

				if ( has_nav_menu( 'primary' ) ) {

					echo '<div class="menu-left">';
					echo '<h3>' . get_theme_mod('primary-nav-heading', __('Pages', 'mysocial')) . '</h3>';

					wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-mobile-menu', 'menu_class' => '', 'depth' => 1 ) );

					echo "</div>";

				}

				if ( has_nav_menu( 'secondary' ) ) {

					echo '<div class="menu-right">';
					echo '<h3>' . get_theme_mod('secondary-nav-heading', __('Categories', 'mysocial')) . '</h3>';

					wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'secondary-mobile-menu', 'menu_class' => '', 'depth' => 1 ) );

					echo "</div>";

				}

			?>

			</div><!-- .container -->

		</div><!-- .mobile-menu -->	

		<?php if ( get_theme_mod('header-search-on', true) ) : ?>
			
			<span class="search-icon">
				<span class="genericon genericon-search"></span>
				<span class="genericon genericon-close"></span>			
			</span>

		<?php endif; ?>						

	</header><!-- #masthead -->

	<div id="content" class="site-content container clear">
