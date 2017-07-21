<?php

class BaseTest extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'Gist_Snippets') );
	}
	
	function test_get_instance() {
		$this->assertTrue( gist_snippets() instanceof Gist_Snippets );
	}
}
