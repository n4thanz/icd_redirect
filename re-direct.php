<?php

/**
* Plugin Name: ICD Holding Page
* Plugin URI: http://innercitydigital.co.uk/
* Description: Re-direct users that are not logged in back to the holding page.
* Version: 1.0 
* Author: Nathan Zagrovic
* Author URI: http://innercitydigital.co.uk/
*/





add_action( 'template_redirect', 'icd_redirect' );

function icd_redirect() {

	global $wpdb;
	$currentURL = $wpdb->get_var("SELECT URL FROM wp_icd_redirect WHERE id = 1");
	$siteURL = 'https://www.google.co.uk';

	// REQUIRED FOR IS_USER_LOGGED_IN()
	include(ABSPATH . "wp-includes/pluggable.php"); 

	if ( !is_user_logged_in() && !is_front_page()  ) {
	echo "NOT logged in and not the front page!";
	//wp_redirect($currentURL, 301);
	//exit;
	} 

}



function myplugin_menu() {
add_menu_page('ICD Redirect', 'ICD Redirect', 'administrator', 'myplugin_settings', 'my_plugin_options'); 
}

add_action('admin_menu', 'myplugin_menu'); 


function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	global $wpdb;
	$currentURL = $wpdb->get_var("SELECT URL FROM wp_icd_redirect WHERE id = 1");
	echo '<div class="wrap">';
	echo '<h1>Inner City Digital - WP Redirect </h1>';			
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';

	echo ' <form action="" method="post">
                  URL <input type="text" name="url" value="'.$currentURL.'" /><br/>
                  <input name="submit" type="submit" value="Submit">
              </form> ';


              echo $currentURL;
}



function install_tables()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table1 = "CREATE TABLE ".$wpdb->prefix."icd_redirect (
         `id` bigint(100) NOT NULL AUTO_INCREMENT,
         `URL` varchar(255) NOT NULL,
         PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    dbDelta($table1); 
} 

register_activation_hook(__FILE__,'install_tables'); 




     global $wpdb;


    if ( isset( $_POST['submit'] ) ){

	    $url = $_POST['url'];
	    $wpdb->update( 'wp_icd_redirect', array( 'URL' => $url ), array( '%s', '%s' ) );


	    $table = 'wp_icd_redirect';
	    $fields = array( 'URL' => $url );
	    $where = array( 'id' => 1 );

   		$wpdb->update( $table, $fields, $where );
   	}


