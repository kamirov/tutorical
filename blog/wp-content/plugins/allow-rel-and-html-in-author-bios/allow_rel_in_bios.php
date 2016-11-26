<?php
/*
Plugin Name: Allow REL= and HTML in Author Bios
Plugin URI: http://www.onemansblog.com/relbios
Description: Enables the use of REL and just about anything else in author bios.  WARNING - Could be used for evil! Make sure you trust your authors!
Author: John Pozadzides
Version: .1
Author URI: http://www.onemansblog.com
*/

remove_filter('pre_user_description', 'wp_filter_kses');

?>