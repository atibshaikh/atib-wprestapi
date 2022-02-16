<?php


class Public_cpt {

	public function __construct() {
		$this->define_public_hooks();
	}

	private function define_public_hooks() {
		add_action('wp_enqueue_scripts', array( $this, 'public_enqueue_style_script' ) );
	}


	public function public_enqueue_style_script(){

		wp_enqueue_style('atib-public-cpt-css', MYPLUGIN_URL .'public/css/public-style.css', array(), '1.0.0', 'all');
		wp_enqueue_script('atib-public-cpt-js',  MYPLUGIN_URL .'public/js/public-script.js', array('jquery'), '1.0.0', false);

	}
}