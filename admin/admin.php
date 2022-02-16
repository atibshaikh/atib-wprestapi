<?php

class Admin_setup {

	public function __construct() {
		
		$this->define_admin_hooks();

	}

	private function define_admin_hooks() {

		add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_style_script' ) );
		add_action('admin_menu', array($this, 'my_admin_menu'));
		add_action('admin_init', array($this, 'register_my_plugin_general_setting') );

	}

	

	//ENQUE STYLES
	public function admin_enqueue_style_script(){

		wp_enqueue_style('atib-rest-css', MYPLUGIN_URL .'admin/css/admin-style.css', array(), '1.0.0', 'all');
		
		if(isset($_REQUEST['page'])){
			
			if($_REQUEST['page'] == 'sub-importer' || $_REQUEST['page'] == "youtube-api-setting"){
				wp_enqueue_style('bootstrap-css', MYPLUGIN_URL . 'admin/css/bootstrap-min.css', array(), '1.0.0', 'all' );
			}
		}

		wp_enqueue_script('atib-rest-js',  MYPLUGIN_URL .'admin/js/admin-script.js', array('jquery'), '1.0.0', false);

	}

	//add admin menu and submenu
	public function my_admin_menu(){

		add_menu_page( 'New Plugin Settings', 'Youtube API Setting', 'manage_options', 'youtube-api-setting', array($this,'my_plugin_admin_page'), 'dashicons-tickets', 250 );

		add_submenu_page( 'youtube-api-setting', 'Youtube API Importer', 'Youtube API Importer', 'manage_options', 'sub-importer', array($this,'my_plugin_admin_subpage') );

	}

	//register youtube api settings layout
	public function my_plugin_admin_page(){
		require 'youtube-api-settings.php';
	}


	public function register_my_plugin_general_setting(){

		//register all setting for general setting

		register_setting( 'youtubeapicustomsettings', 'youtubeAPIKey' );
		register_setting( 'youtubeapicustomsettings', 'youtubeChannelID' );
		
	}

	//register admin subpage
	public function my_plugin_admin_subpage(){
		
		require 'sub-menu-page.php';

	}

	

	
}


