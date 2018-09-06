<?php
/*
Plugin Name: Reach Us Contact Form
Plugin URI: https://wordpress.org/plugins/reach-us-contact-form/
Description: Simple Plugin to Implement Contact Form
Version: 1.0
Author: Cybrosys Technologies
Author URI: http://cybrosys.com
Domain Path: /languages
License:     GPL2
 
Reach Us Contact Form is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Reach Us Contact Form is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
*/


// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'REACH US CONTACT FORM', '1.0.0' );
// enqueues plugin scripts
function rscf_scripts() {	
	if(!is_admin())	{
		wp_enqueue_style('rscf_style', plugins_url('/assets/css/style.css',__FILE__));
		wp_enqueue_style('rscf_style',plugins_url( '/assets/icon-128x128.svg', __FILE__ ));
		plugin_dir_url( __FILE__ ) . '/assets/icon-128x128.png';
	}
}
add_action('wp_enqueue_scripts', 'rscf_scripts');

// add admin options page

function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" class="pre">';
	echo '<label>';
	echo 'Your Name (required) <br/>';
	echo '<input type="text" name="yourname" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["yourname"] ) ? esc_attr( $_POST["yourname"] ) : '' ) . '" size="40" />';
	echo '</label>';
	echo '<label>';
	echo 'Your Email (required) <br/>';
	echo '<input type="email" name="mail" value="' . ( isset( $_POST["mail"] ) ? esc_attr( $_POST["mail"] ) : '' ) . '" size="40" />';
	echo '</label>';
	echo '<label>';
	echo 'Subject (required) <br/>';
	echo '<input type="text" name="subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : '' ) . '" size="40" />';
	echo '</label>';
	echo '<label>';
	echo 'Your Message (required) <br/>';
	echo '<textarea rows="10" cols="35" name="message">' . ( isset( $_POST["message"] ) ? esc_attr( $_POST["message"] ) : '' ) . '</textarea>';
	echo '</label>';
    echo '<label><button type="submit" name="submit" value="Send"/>Send Your Query</label>';
	echo '</form>';
}
function rscf_mail() {
	// if the submit button is clicked, send the email
	if ( isset( $_POST['submit'] ) ) {
		// sanitize form values
		$name    = sanitize_text_field( $_POST["yourname"] );
		$email   = sanitize_email( $_POST["mail"] );
		$subject = sanitize_text_field( $_POST["subject"] );
		$message = esc_textarea( $_POST["message"] );
		// get the blog administrator's email address
		$to = get_option( 'admin_email' );
		$headers = "From: $name <$email>" . "\r\n";
		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting me, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}
function rscf_shortcode() {
	ob_start();
	rscf_mail();
	html_form_code();
	return ob_get_clean();
}
add_shortcode( 'reach_us', 'rscf_shortcode' );



