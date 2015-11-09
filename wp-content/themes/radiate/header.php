<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package ThemeGrill
 * @subpackage Radiate
 * @since Radiate 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="shortcut icon" href="http://travelpal.s3-eu-west-1.amazonaws.com/wp-content/uploads/2015/10/12085810/favicon.png" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/foundation.min.css">
<?php wp_head(); ?>
<title>Travelpal</title>

<meta name='description' content='Discover London through the locals, master London transport, shopping, and attractions.'>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
</head>

<body <?php body_class(); ?>>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68295567-1', 'auto');
  ga('send', 'pageview');

</script>
<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"5061973"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script><noscript><img src="//bat.bing.com/action/0?ti=5061973&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
<div id="parallax-bg">
	
</div>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<div class="header-wrap clearfix">
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img height="50px" class="header-logo" src="http://travelpal.s3-eu-west-1.amazonaws.com/wp-content/uploads/2015/10/08101743/travelpal-logo.png" alt="travelpal logo"> <span class="header-text">Your Friendly Travel Guide</span></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<?php if ( get_theme_mod( 'radiate_header_search_hide', 0 ) == 0 ) { ?>
				<div class="header-search-icon"></div>
				<?php get_search_form();
			} ?>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h4 class="menu-toggle"></h4>
				<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'radiate' ); ?></a>

				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- .inner-wrap header-wrap -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div class="inner-wrap">
