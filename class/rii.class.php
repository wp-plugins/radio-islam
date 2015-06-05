<?php
/**
 * @package WordPress
 * @subpackage RII Radio Widget
 * @version 1.0
 */

/**
 * Generate XML to JSON
 */
class RII_Data {

	private $options = array();

	// the constructor
	function __construct( $url = '', $lifetime = 60, $location = './stats.json' ) {
		$this->options['url'] = $url;
		$this->options['lifetime'] = $lifetime;
		$this->options['location'] = $location;
	}

	// get json url
	private function get_json( $url ) {
		$xml_string = @file_get_contents( $url );
		$xml_string = str_replace( array( "\n", "\r", "\t"), '', $xml_string );
		$xml_string = trim (str_replace( '"', "'", $xml_string ) );
		$xml = simplexml_load_string( $xml_string );
		$json = json_encode( $xml );
		return $json;
	}

	// print json url
	private function print_json( $json ) {
		header('Content-Type: application/json');
		echo $json;
		exit;
	}

	// generate json
	public function generate_json() {
		if ( file_exists( $this->options['location'] ) && ( filemtime( $this->options['location'] ) + $this->options['lifetime'] ) > time() ) {
			$json = file_get_contents( $this->options['location'] );
			$this->print_json( $json );
		} else {
			if ( ! empty( $this->options['url'] ) ) {
				$json = $this->get_json( $this->options['url'] );
				file_put_contents( $this->options['location'], $json );
				$this->print_json( $json );
			}
		}
	}
}