<?php

namespace JW\Gist_Snippets\API;

use JW\Gist_Snippets\Gist_Snippets;

class Base {


	/**
	 * @var Gist_Snippets
	 */
	private $plugin;

	/**
	 * Base constructor.
	 *
	 * @param Gist_Snippets $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

}