<?php
/**
 * Plugin Name: ILC LogBox
 * Plugin URI: http://ilovecolors.com.ar/
 * Description: Creates a log in and log out form with a shortcode.
 * Author: Elio Rivero
 * Author URI: http://ilovecolors.com.ar
 * Version: 1.0.0
 */

class TR_LogBox {
	
	function __construct(){
		// Load localization file
		add_action('plugins_loaded', array(&$this, 'localization'));
		// Create shortcode [logbox]
		add_shortcode('logbox', array(&$this, 'shortcode'));
	}
	
	function shortcode($atts) {
	    global $user_login;
		extract( shortcode_atts( array(
			'login_redirect' => 'admin', // admin | same_page
			'logout_redirect' => 'same_page' // same_page
		), $atts ) );
		
		switch ($login_redirect) {
			case 'admin':
				$login_redirect = admin_url();
				break;
			
			case 'same_page':
				$login_redirect = $_SERVER['REQUEST_URI'];
				break;
			
			default:
				$login_redirect = admin_url();
				break;
		}
		switch ($logout_redirect) {
			case 'same_page':
				$logout_redirect = $_SERVER['REQUEST_URI'];
				break;
			
			default:
				$logout_redirect = $_SERVER['REQUEST_URI'];
				break;
		}
		
	    if ( !is_user_logged_in() ){
	        $html = '
	        <form class="logbox" action="' . wp_login_url() . '" method="post">
	            <input type="text" placeholder="' . __('Username', 'ilc') . '" name="log" id="log" value="" size="20" />
	            <input type="password" placeholder="' . __('Password', 'ilc') . '" name="pwd" id="pwd" size="20" />
	            <input type="submit" name="submit" value="' . __('Log In', 'ilc') . '" class="button" />
	            <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> ' . __('Remember Me', 'ilc') . '</label>
	            <input type="hidden" name="redirect_to" value="' . $login_redirect . '" />
			</form>
			<p class="recover-pass">
				<a href="' . site_url('/wp-login.php?action=lostpassword') . '">' . __('Lost your password?', 'ilc') . '</a>
			</p>';
	    } else {
	        $html = '<p class="is_logged_in">' . __('You are already logged in.', 'ilc') . ' <a href="' . wp_logout_url($logout_redirect) . '">' . __('Log Out?', 'ilc') . '</a></p>';
	    }
	    return $html;
	}

	function localization() {
		// Translation files must be in a folder named "languages" next to this file
		load_plugin_textdomain( 'ilc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}

}

new TR_LogBox();
?>