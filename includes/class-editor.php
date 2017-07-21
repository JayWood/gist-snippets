<?php

namespace JW\Gist_Snippets;

class Editor {

	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		$this->hooks();
	}

	public function hooks() {
		add_action( 'admin_menu', array( $this, 'page_settings') );
	}

}