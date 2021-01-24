<?php

define('_THEMES', [
	'apple' => 'apple',
	'banana' => 'banana',
]);



class Config {
	public $apiUrl;
	public $theme;
	public function __construct() {
		$this->initTheme();
	}

	private function initTheme() {

		$_host = get_host_name();
		$this->theme = 'default';
		foreach( _THEMES as $_domain => $_theme ) {
			if ( stripos($_host, $_domain ) !== false ) {
				$this->theme = $_theme;
			}
		}

	}
}


$config = new Config();

