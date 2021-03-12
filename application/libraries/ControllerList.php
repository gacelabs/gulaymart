<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
 * File: (Codeigniterapp)/libraries/Controllerlist.php
 * 
 * A simple library to list all your controllers with their methods.
 * This library will return an array with controllers and methods
 * 
 * The library will scan the "controller" directory and (in case of) one (1) subdirectory level deep
 * for controllers
 * 
 * Usage in one of your controllers:
 * 
 * $this->load->library('controllerlist');
 * print_r($this->controllerlist->getControllers());
 * 
 * @author Peter Prins 
 */

class ControllerList {

	/**
	 * Codeigniter reference 
	 */
	private $CI;
	private $EXT;

	/**
	 * Array that will hold the controller names and methods
	 */
	private $aControllers;

	// Construct
	function __construct() {
		// Get Codeigniter instance 
		$this->CI = get_instance();
		$this->CI->EXT = ".php";

		// Get all controllers 
		$this->setControllers();
	}

	/**
	 * Return all controllers and their methods
	 * @return array
	 */
	public function getControllers($with=false) {
		if ($with == false) {
			return $this->aControllers;
		} else {
			$aWithControllers = [];
			if ((isset($with['methods']) AND is_array($with['methods'])) AND (isset($with['variables']) AND is_array($with['variables']))) {
				foreach ($with['methods'] as $key => $method) {
					if (isset($method['index']) AND $method['value']) {
						foreach ($this->aControllers as $pos => $data) {
							foreach ($data as $class => $row) {
								if (isset($row['methods']) AND is_array($row['methods']) AND in_array($method['index'], $row['methods'])) {
									if ($row['methods'][$method['index']] == $method['value']) {
										$aWithControllers[$pos][$class]['methods'] = $row['methods'];
										$aWithControllers[$pos][$class]['variables'] = $row['variables'];
									}
								}
							}
						}
					}
				}
				if (isset($with['variables']) AND is_array($with['variables']) AND count($aWithControllers)) {
					foreach ($with['variables'] as $key => $variable) {
						if (isset($variable['index']) AND $variable['value']) {
							foreach ($aWithControllers as $pos => $data) {
								foreach ($data as $class => $row) {
									if (isset($row['variables'])) {
										if ($row['variables'][$variable['index']] == $variable['value']) {
											$aWithControllers[$pos][$class]['methods'] = $row['methods'];
											$aWithControllers[$pos][$class]['variables'] = $row['variables'];
										}
									}
								}
							}
						}
					}
				}
			} elseif (isset($with['methods']) AND is_array($with['methods'])) {
				foreach ($with['methods'] as $key => $method) {
					if (isset($method['index']) AND $method['value']) {
						foreach ($this->aControllers as $pos => $data) {
							foreach ($data as $class => $row) {
								if (isset($row['methods']) AND is_array($row['methods']) AND in_array($method['index'], $row['methods'])) {
									if ($row['methods'][$method['index']] == $method['value']) {
										$aWithControllers[$class]['methods'] = $row['methods'];
										$aWithControllers[$class]['variables'] = $row['variables'];
									}
								}
							}
						}
					}
				}
			} elseif (isset($with['variables']) AND is_array($with['variables'])) {
				foreach ($with['variables'] as $key => $variable) {
					if (isset($variable['index']) AND $variable['value']) {
						foreach ($this->aControllers as $pos => $data) {
							foreach ($data as $class => $row) {
								if (isset($row['variables'])) {
									if ($row['variables'][$variable['index']] == $variable['value']) {
										$aWithControllers[$pos][$class]['methods'] = $row['methods'];
										$aWithControllers[$pos][$class]['variables'] = $row['variables'];
									}
								}
							}
						}
					}
				}
			}
			ksort($aWithControllers);
			return $aWithControllers;
		}
	}

	/**
	 * Set the array holding the controller name and methods
	 */
	public function setControllerMethods($p_sControllerName, $p_aControllerMethods, $p_aControllerSorts) {
		$this->aControllers[$p_aControllerSorts][$p_sControllerName]['methods'] = $p_aControllerMethods;
	}

	/**
	 * Set the array holding the controller name and variables
	 */
	public function setControllerVars($p_sControllerName, $p_aControllerVars, $p_aControllerSorts) {
		$this->aControllers[$p_aControllerSorts][$p_sControllerName]['variables'] = $p_aControllerVars;
	}

	/**
	 * Set the array holding the controller name and variables
	 */
	public function setControllerSorts($p_sControllerName, $p_aControllerSorts) {
		$this->aControllers[$p_sControllerName]['sorts'] = $p_aControllerSorts;
	}

	/**
	 * Search and set controller and methods.
	 */
	private function setControllers() {
		// Loop through the controller directory
		foreach (glob(APPPATH . 'controllers/*') as $controller) {

			// if the value in the loop is a directory loop through that directory
			if (is_dir($controller)) {
				// Get name of directory
				$dirname = basename($controller, $this->CI->EXT);

				// Loop through the subdirectory
				foreach (glob(APPPATH . 'controllers/'.$dirname.'/*') as $subdircontroller) {
					// Get the name of the subdir
					$subdircontrollername = basename($subdircontroller, $this->CI->EXT);

					// Load the controller file in memory if it's not load already
					if (!class_exists($subdircontrollername)) {
						$this->CI->load->file($subdircontroller);
					}
					// Add the controllername to the array with its methods
					$aMethods = get_class_methods($subdircontrollername);
					$aUserMethods = array();
					foreach ($aMethods as $method) {
						if (!in_array($method, ['__construct', 'get_instance', $subdircontrollername, 'index', 'set_response', 'set_form_valid_fields'])) {
							$aUserMethods[] = $method;
						}
					}
					$aVars = get_class_vars($subdircontrollername);
					$aUserVars = array();
					$aUserSorts = 0;
					if (is_array($aVars)) {
						foreach ($aVars as $index => $var) {
							if (!in_array($index, ['allowed_methods', 'menus'])) {
								$aUserVars[$index] = $var;
							}
							if ($index == 'sort') {
								$aUserSorts = $var;
							}
						}
					}

					$this->setControllerMethods($subdircontrollername, ($aUserMethods ?: false), $aUserSorts);
					$this->setControllerVars($controllername, $aUserVars, $aUserSorts);
				}
			} else if (pathinfo($controller, PATHINFO_EXTENSION) == "php") {
				// value is no directory get controller name                
				$controllername = basename($controller, $this->CI->EXT);

				// Load the class in memory (if it's not loaded already)
				if (!class_exists($controllername)) {
					$this->CI->load->file($controller);
				}

				// Add controller and methods to the array
				$aMethods = get_class_methods($controllername);
				$aUserMethods = array();
				if (is_array($aMethods)) {
					foreach ($aMethods as $method) {
						if (!in_array($method, ['__construct', 'get_instance', $controllername, 'index', 'set_response', 'set_form_valid_fields'])) {
							$aUserMethods[] = $method;
						}
					}
				}
				$aVars = get_class_vars($controllername);
				$aUserVars = array();
				$aUserSorts = 0;
				if (is_array($aVars)) {
					foreach ($aVars as $index => $var) {
						if (!in_array($index, ['allowed_methods', 'menus'])) {
							$aUserVars[$index] = $var;
						}
						if ($index == 'sort') {
							$aUserSorts = $var;
						}
					}
				}

				$this->setControllerMethods($controllername, ($aUserMethods ?: false), $aUserSorts);
				$this->setControllerVars($controllername, $aUserVars, $aUserSorts);
			}
		}   
	}
}