<?php

/**
 * Xiumi Framework
 * @author roop <roop@hakz.co>
 * @copyright 2013, Hakz Project, http://hakz.co/
 * @package General Template Class
 */

class Template {

	/**
	 * String for the file?
	 * @param string
	 * @access private
	 */
	static private $file;

	/**
	 * Caches the template file for later use
	 * @param array
	 */
	static private $savedtemlate = array();

	/**
	 * Values array container
	 * @param array
	 * @access private
	 */
	static private $values;

	/**
	 * Fetch a specific template from the filesystem
	 * @param string Template name
	 * @param boolean Prints out the template or return as string [def=true]
	 * @return string 
	 */
	static public function fetch($templateName, $data = array()) {

		if(!file_exists($templateName)) {
			// return something if not found
		}

		self::$file = file_get_contents(APP_TPL . $templateName . ".tpl");

		// Parses the data array
		foreach($data as $key => $val) {
			self::set($key, $val);
		}

		self::parse(); // Parse necessary variables from the template file


		echo self::$file;
	}

	/**
	 * Merge the tempaltes and print them out
	 * @param array Template array
	 * @return string
	 */
	static public function merge($templates, $data = array()) {
		
		if(!is_array($templates)) {
			return $templates;
		}

		foreach($templates as $tplBit) {
			self::fetch($tplBit);
		}

	}

	/**
	 * Parse the values set to be printed out in the template
	 * @param void
	 * @return boolean
	 */
	static private function parse() {

		// Clean Whitespaces
		self::$file = trim(self::$file);

		// Parse variables
		if(is_array(self::$values)) {
			foreach(self::$values as $key => $val) {
				if(!is_array($val)) {
					self::$file = preg_replace("/\{{$key}\}/", $val, self::$file);
				} else {
					foreach($val as $subKey => $subVal) {
						self::$file = preg_replace("/\{$key\.$subKey\}/", $subVal, self::$file);
					}
				}
			}
		}

		// Parse commands
	}

	/**
	 * Set values to be parsed in the template
	 * @param string|array
	 * @return 
	 */
	static public function set($inputKey, $val = '') {

		// If we detect the input to be an array
		if(is_array($inputKey)) {
			foreach($inputKey as $key => $val) {
				self::$values[$key] = $val;
			}

		} else {
			self::$values[$inputKey] = $val;
		}

		return self::$values;
	}

}