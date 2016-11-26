<!DOCTYPE html>  
<html lang="en">  
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width" />

<!--[if lte IE 6]>
<meta http-equiv="refresh" content="0;url=<?= base_url('old') ?>" />
<![endif]-->

<link rel="icon" type="image/png" href="<?= base_url('favicon.png').'?'.date('l jS \of F Y h:i:s A') ?>">
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'>

<!--[if lt IE 9]>  
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>  
<![endif]-->  

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<link rel="stylesheet" href="<?= base_url('assets/css/tutorical-base.css') ?>">

<!--[if lte IE 9]>
  <script src="<?= base_url('assets/js/selectivizr-min.js') ?>"></script>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie9-and-down.css') ?>" />
<![endif]-->

<!--[if lte IE 8]>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie8-and-down.css') ?>" />
<![endif]-->

<!--[if lte IE 7]>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie7-and-down.css') ?>" />
<![endif]-->

<meta property="og:image" content="<?= base_url('assets/images/social/new-blog-post-image.png') ?>" />

<title>
<?
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( 'Page %s', max( $paged, $page ) );

?>
</title>

<link rel="pingback" href="<?= bloginfo( 'pingback_url' ); ?>" />
<link rel="alternate" type="application/rss xml" title="Subscribe to <?= bloginfo('name'); ?>" href="<?= bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/rss xml" title="Subscribe to <?= bloginfo('name'); ?>" href="<?= bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/rss xml" title="Subscribe to <?= bloginfo('name'); ?>" href=" <?= bloginfo('atom_url'); ?>" />

<?= wp_head(); ?>
</head>

<body>
<div class="wrapper blog-wrapper">

<div id="global-overlay"></div>

<!--[if IE 7]>
<div class="old-browser-messages" style="text-align: center; padding: 10px 0; margin: 0 auto;">
	<h1>Hey! Looks like you're using an older Internet Explorer.</h1>
	<p style="margin-bottom: 0px; padding-bottom: 0;">Tutorical makes use of some new features that will look funky / not work on your browser. For a faster, prettier, and more secure internet experience, please<br> <a rel="nofollow" href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home"><b>Upgrade to the newest Internet Explorer</b></a> or <a rel="nofollow" href="http://www.telegraph.co.uk/technology/3794213/Web-browsers-five-alternatives-to-Internet-Explorer.html"><b>see some alternative browsers</b></a>.</p>
</div>
<![endif]-->

<header id="blog-header" class="site-headers">
	<div class="containers cf">
		<div class="go-to-tutorical-cont">
<!--
			<a href="<?= home_url() ?>" id="go-back-to-blog-text">&laquo; Blog Home</a>
-->
			<a href="<?= base_url() ?>" class="buttons" tabindex="130">Go to Tutorical &raquo;</a>
		</div>

		<div id="above-search" class="cf">
			<h1 id="actual-site-title">Tutorical Blog</h1>
			<a href="<?= base_url('blog') ?>" tabindex="50" id="site-title-blog" title="Go to the Tutorical Blog"></a>
		</div>
	</div>

	<div id="search-bar" class="cf">
		<div class="containers" id="search-contents-container">
		<?php get_search_form(); ?>
		</div>
	</div>
</header>

<div id="blog-content" class="containers cf">