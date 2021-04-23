<?php

function debug()
{
	$args = func_get_args();
	echo "<pre>";
	foreach ($args as $index => $data) {
		if ($data !== 'stop' AND ($index >= 0 AND $index < count($args)-1)) {
			$trace = debug_backtrace();
			try {
				if (!empty($trace)) {
					foreach ($trace as $key => $row) {
						$separator = '------';
						for ($i=0; $i < strlen($row['file']); $i++) $separator .= '-';
						
						if ($key == 0) {
							echo ($key==0?'<h1 style="margin:0;">DEBUGGER</h1>':'').$separator."<br /><b>PATH:</b> ".$row['file'];
							echo "<br /><b>LINE:</b> ".$row['line']."<br />";
						
							if (is_bool($data)) {
								echo "<b>DATA TYPE:</b> BOOLEAN<br />";
								if ($data) {
									echo "<b>DATA:</b> TRUE<br />";
								} else {
									echo "<b>DATA:</b> FALSE<br />";
								}
							} else {
								if (is_object($data) OR is_null($data)) echo "<b>DATA TYPE:</b> OBJECT<br />";
								if (is_array($data)) echo "<b>DATA TYPE:</b> ARRAY<br />";
								if (is_numeric($data)) {
									echo "<b>DATA TYPE:</b> NUMBER<br />";
								} elseif (is_string($data)) {
									echo "<b>DATA TYPE:</b> STRING<br />";
								}
								if (empty($data) AND $data != 0) {
									if (is_object($data) OR is_null($data)) echo "<b>DATA:</b> NULL<br />";
									if (is_array($data)) echo "<b>DATA:</b> EMPTY<br />";
									if (is_string($data)) echo "<b>DATA:</b> BLANK<br />";
								} else {
									echo "<b>DATA:</b> ";
									if (is_null($data)) {
										var_dump($data);
									} else if (is_string($data)) {
										echo '<code>';
										print_r($data);
										echo '</code>';
									} else {
										print_r($data);
									}
									echo "<br />";
								}
							}
						}
					}
				} else {
					echo "<b>DATA:</b> NULL<br />";
				}
			} catch (Exception $e) {
				foreach ($trace as $key => $row) {
					$separator = '------';
					for ($i=0; $i < strlen($row['file']); $i++) $separator .= '-';
						echo '<h1 style="margin:0;">DEBUGGER</h1>'.$separator."<br /><b>PATH:</b> ".$row['file'];
					echo "<br /><b>LINE:</b> ".$row['line']."<br />";
					echo "<b>DATA:</b> ".$e->getMessage()."<br />";
				}
			}
		}
	}
	echo "</pre>";
	if (end($args) == 'stop' OR end($args) === true) exit();
}

function check_instance($obj=FALSE, $class=NULL)
{
	if (is_array($class)) {
		foreach ($class as $key => $value) {
			if ($obj instanceof $value) {
				return TRUE;
			}
		}
	} else {
		if ($obj instanceof $class) {
			return TRUE;
		}
	}
	return FALSE;
}

function device_id()
{
	$DEVICE = get_mac_address();
	if ($DEVICE == false) {
		$IP = trim($_SERVER['REMOTE_ADDR']);
		$DEVICE = trim(GACELABS_KEY.'|'.$IP.'|'.GACELABS_SUPER_KEY);
	}
	// debug(strtoupper(substr(md5($DEVICE), 0, 12)), true);
	return strtoupper(substr(md5($DEVICE), 0, 12));
}

function get_mac_address()
{
	// debug(PHP_OS, 'stop');
	$macaddress = false;
	ob_start();
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		@system('ipconfig /all');
		$mycomsys = ob_get_contents();  
		ob_clean();  
		$find_mac = "Physical";  
		$pmac = strpos($mycomsys, $find_mac);  
		$macaddress = substr($mycomsys, ($pmac+36), 17);  
	} elseif (strtoupper(PHP_OS) == 'LINUX') {
		@system('ifconfig');
		$mycomsys = ob_get_contents();
		ob_clean();
		$find_mac = "ether";
		$pmac = strpos($mycomsys, $find_mac);
		$macaddress = substr($mycomsys, ($pmac+6), 17);
	}
	// Display Mac Address 
	return $macaddress;
}

function time_diff($past=FALSE, $future=FALSE, $diff='minutes', $result=false)
{
	if ($past AND $future) {
		$lapse = (strtotime($future) - strtotime($past));
		switch ($diff) {
			case 'seconds':
				$diff = $lapse;
			break;
			case 'minutes':
				$diff = ($lapse / 60);
			break;
			case 'hours':
				$diff = ($lapse / 3600);
			break;
			case 'days':
				$diff = ($lapse / 86400);
			break;
		}
	}
	if ($result) {
		return $diff;
	} else {
		if ($diff <= 0) return TRUE;
	}
	return FALSE;
}

function get_root_path($path='', $is_doc_path=TRUE)
{
	$domain = '/';
	if((bool)strstr($_SERVER['HTTP_HOST'], 'local') == TRUE) {
		$domain = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}
	if($is_doc_path) {
		return $_SERVER['DOCUMENT_ROOT'] . $domain . $path;
	} else {
		return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $domain . $path;
	}
}

function save_image($base64_string=FALSE, $dir='', $file=FALSE) {
	if ($base64_string) {
		if ($file == FALSE) {
			$output_file = get_root_path('assets/data/photos/'.random_string().'.jpg');
		} else {
			$output_file = create_dirs($dir).$file;
		}
		// debug($output_file, 1);
		/*open the output file for writing*/
		$ifp = fopen($output_file, 'wb'); 
		/*split the string on commas*/
		/*$data[0] == "data:image/png;base64"*/
		/*$data[1] == <actual base64 string>*/
		$data = explode(',', $base64_string);
		/*we could add validation here with ensuring count($data) > 1*/
		fwrite($ifp, base64_decode($data[1]));
		/*clean up the file resource*/
		fclose($ifp); 
		// return get_root_path($file, FALSE); 
		return 'assets/data/files/'.$dir.'/'.$file; 
	}
	return FALSE;
}

function files_upload($_files=FALSE, $dir='', $return_path=FALSE, $this_name=FALSE) {
	if ($_files) {
		// debug($_files, 1);
		$uploaddir = create_dirs($dir);
		// debug($uploaddir, 1);
		$ci =& get_instance();
		$profile = $ci->accounts->has_session ? $ci->accounts->profile : false;

		$array_index = array_keys($_files);
		$result = FALSE;

		if (isset($array_index[0])) {
			$input = $array_index[0];
			if (is_array($_files[$input]['name'])) {
				$result = [];
				foreach ($_files[$input]['name'] as $key => $name) {
					if ($_files[$input]['error'][$key] == 0) {
						$ext = strtolower(pathinfo(basename($name), PATHINFO_EXTENSION));
						if ($this_name) {
							$pathname = clean_string_name($this_name).($key == 0 ? '' : '-'.($key+1)).'.'.$ext;
						} else {
							$pathname = basename($name);
						}
						$pathname = preg_replace('/\s+/', '-', $pathname);
						$uploadfile = $uploaddir . $pathname;
						if (@move_uploaded_file($_files[$input]['tmp_name'][$key], $uploadfile)) {
							// "File is valid, and was successfully uploaded.\n";
							$status = TRUE;
						} else {
							// "Possible file upload attack!\n";
							$status = FALSE;
						}
						$file_n_extension = explode('.'.$ext, $pathname);
						$result[] = [
							'name' => ucwords(str_replace('-', ' ', $file_n_extension[0])),
							// 'path' => $uploadfile,
							'user_id' => $profile ? $profile['id'] : 0,
							'url_path' => str_replace('//', '/', 'assets/data/files/'.$dir.'/'.$pathname),
							'status' => $status
						];
					}
				}
			} else {
				if ($_files[$input]['error'] == 0) {
					$ext = strtolower(pathinfo(basename($_files[$input]['name']), PATHINFO_EXTENSION));
					if ($this_name) {
						$pathname = clean_string_name($this_name).'.'.$ext;
					} else {
						$pathname = basename($_files[$input]['name']);
					}
					$pathname = preg_replace('/\s+/', '-', $pathname);
					$uploadfile = $uploaddir . $pathname;
					// debug($ext);
					// debug($uploadfile, 1);
					if (@move_uploaded_file($_files[$input]['tmp_name'], $uploadfile)) {
						// "File is valid, and was successfully uploaded.\n";
						$status = TRUE;
					} else {
						// "Possible file upload attack!\n";
						$status = FALSE;
					}
					$file_n_extension = explode('.'.$ext, $pathname);
					$result = [
						'name' => ucwords(str_replace('-', ' ', $file_n_extension[0])),
						// 'path' => $uploadfile,
						'user_id' => $profile ? $profile['id'] : 0,
						'url_path' => str_replace('//', '/', 'assets/data/files/'.$dir.'/'.$pathname),
						'status' => $status
					];
				}
			}
		}
		// debug($uploaddir, 1);
		// debug(array_keys($result), 1);
		// debug($_files, 1);
		if ($return_path AND isset($input)) {
			$data = '';
			$set = array_keys($result);
			if (isset($set[0]) AND !is_string($set[0])) {
				$data = [];
				foreach ($result as $key => $row) {
					if ($row['status']) {
						$data[] = $row['url_path'];
					}
				}
			} else {
				$data = $result['url_path'];
			}
			return $data;
		} else {
			return $result;
		}
	}
}

function create_dirs($dir='')
{
	/*create the dirs*/
	$folder_chunks = explode('/', 'assets/data/files/');
	if (count($folder_chunks)) {
		$uploaddir = get_root_path();
		foreach ($folder_chunks as $key => $folder) {
			$uploaddir .= $folder.'/';
			// debug($uploaddir);
			@mkdir($uploaddir);
		}
	}
	@mkdir(get_root_path('assets/data/files/'));
	$uploaddir = get_root_path('assets/data/files/'.$dir);
	
	if ($dir != '') {
		/*create the dirs*/
		$folder_chunks = explode('/', str_replace(' ', '_', $dir));
		// debug($folder_chunks);
		if (count($folder_chunks)) {
			$uploaddir = get_root_path('assets/data/files/');
			foreach ($folder_chunks as $key => $folder) {
				$uploaddir .= $folder.'/';
				// debug($uploaddir);
				@mkdir($uploaddir);
			}
		}
	}
	@chmod($uploaddir, 0755);

	return $uploaddir;
}

function construct_where($id_post_id=FALSE, $table_or_alias='') {
	// debug($id_post_id);
	if ($id_post_id) {
		$data = explode('-', $id_post_id);
		// debug($data, 1);
		if (count($data) == 2) {
			return $table_or_alias.'id = '.$data[0].' AND '.$table_or_alias.'user_id = '.$data[1];
		} elseif (count($data) == 1) {
			return $table_or_alias.'id = '.$data[0];
		}
	}
	return FALSE;
}

function fix_title($title=FALSE, $replace=' ') {
	if ($title) {
		return ucwords(preg_replace('/[_]/', $replace, $title));
	}
	return '';
}

function nice_url($title=FALSE, $return=false) {
	if ($title) {
		$echo = preg_replace('/[^A-Za-z0-9\-]/', ' ', strtolower($title));
		$echo = strtolower(preg_replace('/\s+/', '-', trim($echo)));
	} else {
		$echo = '';
	}
	if ($return == false) {
		echo $echo;
	} else {
		return $echo;
	}
}

function remove_multi_space($value=FALSE, $return=false) {
	if ($value) {
		$echo = preg_replace('/\s+/', ' ', $value);
	} else {
		$echo = $value;
	}
	if ($return == false) {
		echo $echo;
	} else {
		return $echo;
	}
}

function clean_string_name($string=FALSE, $replaced=FALSE, $delimiter='-')
{
	if ($string) {
		/*now replace space and underscores with the delimiter*/
		$string = preg_replace('/\s/', $delimiter, $string);
		$string = preg_replace('/_/', $delimiter, $string);
		/*clean all unnecessary symbols and characters*/
		$string = preg_replace('/[^a-z0-9\.-]/', '', strtolower($string));
		$string = preg_replace('/[()]/', '', strtolower($string));
		$string = preg_replace('/[+]/', '', strtolower($string));
		if ($replaced) {
			$string = preg_replace('/'.$delimiter.'/', $replaced, $string);
		}
	}
	return $string;
}

function curl_get_shares($url, $socialmedia='facebook')
{
	switch ($socialmedia) {
		case 'facebook':
			$access_token = FBTOKEN;
			$api_url = 'https://graph.facebook.com/v6.0/?id=' . urlencode($url) . '&fields=engagement&access_token=' . $access_token;
			break;
		case 'pinterest':
			$api_url = 'http://api.pinterest.com/v1/urls/count.json?url=' . urlencode($url);
			// $json_return = file_get_contents($api_url);
			// $json_return = str_replace(['receiveCount', '(', ')'], '', $json_return);
			break;
	}

	$ch = curl_init(); // initializing
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print
    // curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$json_return = curl_exec($ch); // connect and get json data
	curl_close($ch); // close connection

	// debug($json_return, 1);
	return json_decode($json_return, TRUE);
}

function in_str($string='', $search='')
{
	return (bool)strstr($string, $search);
}

function is_url_parsable()
{
	return (in_str(current_full_url(), '_') OR in_str(current_full_url(), '~')) AND in_str(current_full_url(), ':');
}

function current_full_url($uri='')
{
	$CI =& get_instance();
	$url = $CI->config->site_url($CI->uri->uri_string()) . $uri;
	// debug($url); debug($_SERVER); exit();
	if ($_SERVER['QUERY_STRING']) {
		$url .= $url.'?'.$_SERVER['QUERY_STRING'];
	}
	return rtrim(str_replace('/index.php', '', $url), '/').'/';
}

function parse_mtb_query($uri=FALSE)
{
	$parsed = [];
	if ($uri) {
		$separate = explode(':', $uri);
		foreach ($separate as $key => $string) {
			$col_val = explode('_', $string);
			$num = $col_val[0][strlen($col_val[0])-1];
			$column = is_numeric($num) ? remove_in_str($col_val[0], $num).'_'.$num : $col_val[0];
			$value = $col_val[1];
			if (!isset($parsed[$column])) {
				$parsed[$column] = get_col_val($value);
			} else {
				if (is_array($parsed[$column])) {
					$parsed[$column][] = get_col_val($value);
				} else {
					$parsed[$column] = [$parsed[$column]];
					$parsed[$column][] = get_col_val($value);
				}
			}
		}
		// debug($parsed, 1);
	}
	return $parsed;
}

function remove_in_str($string=FALSE, $remove='')
{
	if ($string) {
		return preg_replace('/'.$remove.'/', '', $string);
	}
}

function get_col_val($string=FALSE, $delimiter='~')
{
	$parsed = $string;
	if ($string AND in_str($string, $delimiter)) {
		$parsed = [];
		$col_val = explode($delimiter, $string);
		// debug($col_val, 1);
		$column = $col_val[0];
		$value = $col_val[1];
		if (!isset($parsed[$column])) {
			$parsed[$column] = $value;
		} else {
			if (is_array($parsed[$column])) {
				$parsed[$column][] = $value;
			} else {
				$parsed[$column] = [$parsed[$column]];
				$parsed[$column][] = $value;
			}
		}
	}
	return $parsed;
}

function tinymce_upload($dir='', $filename='upload')
{
	/***************************************************
	* Only these origins are allowed to upload images *
	***************************************************/
	$accepted_origins = ["http://localhost", "http://local.mtbarena", "https://mtbarena.com"];
	/*********************************************
	* Change this line to set the upload folder *
	*********************************************/
	$imageFolderMain = "assets/data/files";
	$imageFolder = create_dirs($dir);
	// debug($imageFolder, 1);

	reset($_FILES);
	$temp = current($_FILES);
	if (is_uploaded_file($temp['tmp_name'])){
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			/*same-origin requests won't set an origin. If the origin is set, it must be valid.*/
			if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			} else {
				header("HTTP/1.1 403 Origin Denied");
				return;
			}
		}

		/*
		If your script needs to receive cookies, set images_upload_credentials : true in
		the configuration and enable the following two headers.
		*/
		/*header('Access-Control-Allow-Credentials: true');*/
		/*header('P3P: CP="There is no P3P policy."');*/

		/*Sanitize input*/
		if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
			header("HTTP/1.1 400 Invalid file name.");
			return;
		}

		/*Verify extension*/
		if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), ["gif", "jpg", "png"])) {
			header("HTTP/1.1 400 Invalid extension.");
			return;
		}
		// $ext = strtolower(pathinfo(basename($temp['name']), PATHINFO_EXTENSION));

		/*Accept upload if there was no origin, or if it is an accepted origin*/
		$filetowrite = $imageFolder . $temp['name'];
		// $filetowrite = $imageFolder . $filename.'.'.$ext;
		@move_uploaded_file($temp['tmp_name'], $filetowrite);

		/*Respond to the successful upload with JSON.*/
		/*Use a location key to specify the path to the saved image resource.*/
		/*{ location : '/your/uploaded/image/file'}*/
		return json_encode(['location' => base_url($imageFolderMain.'/'.$dir.'/'.$temp['name'])]);
		// return json_encode(['location' => base_url($imageFolderMain.'/'.$dir.'/'.$filename.'.'.$ext)]);
	} else {
		/*Notify editor that the upload failed*/
		header("HTTP/1.1 500 Server Error");
	}
}

function calculate($data=FALSE, $mode=FALSE)
{
	$result = FALSE;
	if ($data AND $mode) {
		$data = (object)$data;
		switch (strtolower($mode)) {
			case 'frequency':
				$now = date('Y-m-d H:i:s');
				$added = $data->added;
				$version = $data->version;
				$timediff = strtotime($now) - strtotime($added);
				// Check how many revisions have been made over the lifetime of the Page for a rough estimate of it's changing frequency.
				$period = $timediff / ($version + 1);
				if ($period > 60 * 60 * 24 * 365) {
					$result = 'yearly';
				} elseif ($period > 60 * 60 * 24 * 30) {
					$result = 'monthly';
				} elseif ($period > 60 * 60 * 24 * 7) {
					$result = 'weekly';
				} elseif ($period > 60 * 60 * 24) {
					$result = 'daily';
				} elseif ($period > 60 * 60) {
					$result = 'hourly';
				} else {
					$result = 'always';
				}
				break;
			
			default: /**/
				# code...
				break;
		}
	}
	return $result;
}

function csv_to_array($filename='', $delimiter=',')
{
	/*debug(get_root_path($filename), 1);
	if(!file_exists(get_root_path($filename)) OR !is_readable(get_root_path($filename))) {
		return FALSE;
	}*/
	// $header = NULL;
	// $data = array();
	// if (($handle = fopen(get_root_path($filename), 'r')) !== FALSE) {
	// 	while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
	// 		if(!$header) {
	// 			$header = $row;
	// 		} else {
	// 			$data[] = array_combine($header, $row);
	// 		}
	// 	}
	// 	fclose($handle);
	// }
	$data = Array();
	$cnt = 0;
	$file = fopen(get_root_path($filename), 'r');
	if($file){
		// debug(fgetcsv($file), 1);
		while (($line = fgetcsv($file)) !== FALSE) {
			// $line is an array of the csv elements
			if ($cnt > 0) {
				array_push($data, $line);
			}
			$cnt++;
		}
		fclose($file);
	}
	// debug($data, 1);
	return count($data) ? $data : FALSE;
}

function array_to_csv($data=false, $filename='default.csv', $dir='csv')
{
	// Open a file in write mode ('w')
	$uploaddir = create_dirs($dir);
	$fp = fopen($uploaddir.$filename, 'w'); 

	// Loop through file pointer and a line 
	foreach ($data as $fields) { 
		fputcsv($fp, $fields); 
	}
	fclose($fp);
	return get_root_path('assets/data/files/'.$dir.'/'.$filename, FALSE);
}

function whats_the_day($in='tomorrow')
{
	$datetime = new DateTime($in);
	return $datetime->format('Y-m-d');
}

function forceDownLoad($filename)
{
	header("Pragma: public");
	header("Expires: 0"); // set expiration time
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// header("Content-Type: application/force-download");
	// header("Content-Type: application/octet-stream");
	header('Content-Type: application/csv');
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=".basename($filename).";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($filename));
	
	@readfile($filename);
	exit(0);
}

function do_curl($request=false, $object=false, $url='')
{
	$data = ['response' => [],'status_code' => 404];

	if ($object != false) {
		$object = (array) $object;
		$ch = curl_init($url);
		if (!$ch) {
			$data['response'] = ['message' => 'unable-to-connect'];
			$data['status_code'] = 403;
			return $data;
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if (strtolower(trim($request)) != 'refresh') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $object['headers']);
		}

		switch (strtolower(trim($request))) {
			case 'get': /*GET*/
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				break;
			case 'add': case 'refresh': /*POST*/
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				if (strtolower(trim($request)) == 'refresh') {
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($object['parameters']));
				} else {
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object['parameters']));
				}
				break;
			case 'update': /*PUT*/
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object['parameters']));
				break;
			case 'modify': /*PATCH*/
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object['parameters']));
				break;
			case 'remove': /*DELETE*/
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object['parameters']));
				break;
		}

		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$data = [
			'response' => json_decode($response, true),
			'status_code' => $status_code,
		];
	}

	return $data;
}

function linking_mode($link=null)
{
	$ci =& get_instance();
	$uri_string = uri_string();
	if (empty($uri_string) AND empty($link)) {
		return true;
	} else {
		if (is_null($link)) return false;;
		if ((bool)strstr($uri_string, $link)) {
			return true;
		}
	}
	return false;
}

function get_data_in($table=false, $where=false, $order=2)
{
	if ($table) {
		$ci =& get_instance();
		if ($where != false) $ci->db->where($where);
		if ($order) {
			$data = $ci->db->order_by($order)->get($table);
		} else {
			$data = $ci->db->get($table);
		}
		if ($data->num_rows()) return $data->result_array();
	}
	return [];
}

function do_jsonp_callback($js_function=false, $payload=['type'=>'info', 'message'=>false, 'data'=>false])
{
	$ci =& get_instance();
	$callback = $ci->input->get('callback');
	if ($callback) {
		if ($js_function) {
			echo $callback . '('.$js_function.'('.json_encode($payload).'))'; exit();
		} else {
			echo $callback . '('.json_encode($payload).')'; exit();
		}
	}
	echo json_encode($payload); exit();
}

function camel_to_dashed($className)
{
	return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));
}

function dash_to_camelcase($string, $capitalizeFirstCharacter = false)
{
	$str = str_replace('-', '', ucwords($string, '-'));
	if (!$capitalizeFirstCharacter) {
		$str = lcfirst($str);
	}
	return $str;
}

function proper_redirect($query='', $class='')
{
	$ci =& get_instance();
	if ($ci->agent->referrer())
	{
		redirect($ci->agent->referrer().$query);
	}
	redirect(base_url($class.$query));
}

function get_machine($field='geoplugin_request')
{
	$data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp"), true);
	if ($data) {
		if (isset($data[$field])) {
			return $data[$field];
		} else {
			return $data;
		}
	}
	return false;
}

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	$output = NULL;
	$ci =& get_instance();
	$IP_ADDRESS = $ci->accounts->has_session ? $ci->accounts->profile['ip_address'] : $_SERVER['REMOTE_ADDR'];
	if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		$ip = $_SERVER["REMOTE_ADDR"];
		if ($deep_detect) {
			if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		$ip = in_array($ip, ['::1', '[::1]', '127.0.0.1', 'localhost']) ? $IP_ADDRESS : $ip;
	}
	$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	$continents = array(
		"AF" => "Africa",
		"AN" => "Antarctica",
		"AS" => "Asia",
		"EU" => "Europe",
		"OC" => "Australia (Oceania)",
		"NA" => "North America",
		"SA" => "South America"
	);
	if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		// debug($ipdat);
		if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			switch ($purpose) {
				case "location":
				$output = array(
					"city"           => @$ipdat->geoplugin_city,
					"state"          => @$ipdat->geoplugin_regionName,
					"country"        => @$ipdat->geoplugin_countryName,
					"country_code"   => @$ipdat->geoplugin_countryCode,
					"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
					"continent_code" => @$ipdat->geoplugin_continentCode
				);
				break;
				case "address":
				$address = array($ipdat->geoplugin_countryName);
				if (@strlen($ipdat->geoplugin_regionName) >= 1)
					$address[] = $ipdat->geoplugin_regionName;
				if (@strlen($ipdat->geoplugin_city) >= 1)
					$address[] = $ipdat->geoplugin_city;
				$output = implode(", ", array_reverse($address));
				break;
				case "city":
				$output = @$ipdat->geoplugin_city;
				break;
				case "state":
				$output = @$ipdat->geoplugin_regionName;
				break;
				case "region":
				$output = @$ipdat->geoplugin_regionName;
				break;
				case "country":
				$output = @$ipdat->geoplugin_countryName;
				break;
				case "countrycode":
				$output = @$ipdat->geoplugin_countryCode;
				break;
			}
		}
	}
	return $output;
}

function random_color() {
	$letters = explode('-', '0-1-2-3-4-5-6-7-8-9-A-B-C-D-E-F');
	$color = '#';
	/*for ($i = 0; $i < 6; $i++) {
		$index = floor(rand(0,15));
		$color .= $letters[$index];
	}*/
	$numbers = unique_random_numbers(0,15,6);
	foreach ($numbers as $value) {
		$color .= $letters[$value];
	}
	return $color;
}

function unique_random_numbers($min, $max, $quantity) {
	$numbers = range($min, $max);
	shuffle($numbers);
	return array_slice($numbers, 0, $quantity);
}

function check_data_values($data=false, $is_equal_to='', &$values=[], &$length=0) {
	/*method on checking blank values in a post or get*/
	$has_values = false;
	if ($data) {
		/*check if $data has values*/
		if (is_array($data)) {
			foreach ($data as $key => $row) {
				if (!is_array($row)) {
					if (!is_bool($row) AND strlen(trim($row)) > 0) {
						if ($is_equal_to != '') {
							$values[] = $row == $is_equal_to;
						} else {
							$values[] = true;
						}
					} elseif (is_bool($row)) {
						$values[] = $row;
					}
				} else {
					return check_data_values($row, $is_equal_to, $values, $length);
				}
				$length++;
			}
			// debug(!empty($values) AND count($values) == $length);
			/*if false do not exist then it's true*/
			if (!empty($values) AND count($values) == $length) {
				$has_values = (in_array(false, $values) == false) ? true : false;
			}
		} else {
			if ($is_equal_to != '') {
				$has_values = $data == $is_equal_to;
			} else {
				if (!is_bool($value) AND strlen(trim($value)) > 0) {
					$has_values = true;
				} elseif (is_bool($value)) {
					$has_values = $value;
				}
			}
		}
	}
	// debug((string)$has_values, $length, $values, 'stop');
	return $has_values;
}

function find_value($value='', $data=[], $column='', $results=false)
{
	$data = (array) $data;
	$key = array_search($value, array_column($data, $column));
	// debug($key, 1);
	if (is_numeric($key)) {
		return $results ? $data[$key] : true;
	}
	return false;
}

function count_rows($table=false, $where=false) {
	if ($table) {
		$ci =& get_instance();
		if ($where) $ci->db->where($where);
		$ci->db->select('COUNT(1) AS cnt');
		$data = $ci->db->get($table);
		if ($data->num_rows()) {
			return (int)$data->row_array()['cnt'];
		}
	}
	return 0;
}

function in_array_echo($key=false, $array=false, $echo='') {
	if ($key AND is_array($array)) {
		if (in_array($key, $array)) {
			echo $echo;
		}
	}
}

function isset_echo($array=false, $key=false, $else='') {
	if ($key AND is_array($array)) {
		if (isset($array[$key])) {
			echo $array[$key];
		} else {
			echo $else;
		}
	} else {
		echo $else;
	}
}

function in_array_echo_key($key=false, $array=false) {
	if ($key AND is_array($array)) {
		if (in_array($key, $array) AND isset($array[$key])) {
			echo $array[$key];
		}
	}
}

function not_in_array_echo($key=false, $array=false, $echo='') {
	if ($key AND is_array($array)) {
		if (!in_array($key, $array)) {
			echo $echo;
		}
	}
}

function check_if($to_check=false, $index=false, $expected=false)
{
	if ($expected !== false AND is_string($expected)) {
		switch (strtolower($expected)) {
			case 'array': case 'object':
				if (isset($to_check[$index]) AND is_array($to_check[$index]) AND count($to_check[$index])) {
					return true;
				}
				break;
			case 'boolean': case 'bool':
				if (isset($to_check[$index]) AND is_array($to_check[$index]) AND count($to_check[$index])) {
					return true;
				}
				break;
		}
	}
	return false;
}

function echo_message($msg_prefix='', $field=false)
{
	if ($field) {
		$ci =& get_instance();
		if (isset($ci->accounts) AND $ci->accounts->has_session) {
			$msg_prefix .= isset($ci->accounts->profile[$field]) ? ' '.$ci->accounts->profile[$field] : '';
		}
	}
	echo trim($msg_prefix);
}

function echo_profile($field=false, $else='')
{
	$msg = '';
	if ($field) {
		$ci =& get_instance();
		if (isset($ci->accounts) AND $ci->accounts->has_session) {
			$msg .= isset($ci->accounts->profile[$field]) ? ' '.$ci->accounts->profile[$field] : '';
			echo trim($msg);
		} else {
			echo trim($else);
		}
	}
}

function get_global_values($request=[])
{
	$ci =& get_instance();
	$profile = $ci->accounts->has_session ? $ci->accounts->profile : false;
	/*farms*/
	$request['farms'] = [];
	$request['farm_locations'] = [];
	if ($ci->db->table_exists('user_farms')) {
		$where = " WHERE 1=1 ";
		if ($profile) $where .= " AND uf.user_id = '".$profile['id']."'";
		$farms = $ci->db->query("SELECT uf.* FROM user_farms uf INNER JOIN users u ON u.id = uf.user_id $where");
		if ($farms->num_rows() > 0) {
			$request['farms'] = $farms->row_array();
			$farm_locations = $ci->db->query("SELECT ufl.* FROM user_farm_locations ufl INNER JOIN user_farms uf ON uf.id = ufl.farm_id WHERE ufl.farm_id = '".$request['farms']['id']."'");
			if ($farm_locations->num_rows() > 0) {
				$request['farm_locations'] = $farm_locations->result_array();
			}
		}
	}

	/*categories*/
	$request['categories'] = [];
	if ($ci->db->table_exists('products_category')) {
		$categories = $ci->gm_db->get('products_category');
		if ($categories) {
			$request['categories'] = $categories;
		}
	}

	/*subcategories*/
	$request['subcategories'] = [];
	if ($ci->db->table_exists('products_subcategory')) {
		$subcategories = $ci->gm_db->get('products_subcategory');
		if ($subcategories) {
			foreach ($subcategories as $key => $row) {
				$request['subcategories'][$row['cat_id']][] = $row;
			}
		}
	}

	/*measurements*/
	$request['measurements'] = [];
	if ($ci->db->table_exists('products_measurement')) {
		$measurements = $ci->gm_db->get('products_measurement');
		if ($measurements) {
			$request['measurements'] = $measurements;
		}
	}

	/*galleries*/
	$request['galleries'] = [];
	if ($ci->db->table_exists('galleries')) {
		$where = " WHERE 1=1 ";
		if ($profile) $where .= " AND g.user_id = '".$profile['id']."'";
		$galleries = $ci->db->query("SELECT g.* FROM galleries g INNER JOIN users u ON u.id = g.user_id $where");
		if ($galleries->num_rows() > 0) {
			$request['galleries'] = $galleries->result_array();
		}
	}

	/*attributes*/
	$request['attributes'] = [];
	if ($ci->db->table_exists('attributes')) {
		$attributes = $ci->gm_db->get('attributes', ['enable' => 1]);
		$attrs = [];
		if ($attributes) {
			foreach ($attributes as $key => $attr) {
				$values = $ci->gm_db->get('attribute_values', ['attribute_id' => $attr['id'], 'active' => 1]);
				if ($values) {
					$attrs[$key] = [
						'placeholder' => $attr['name'],
						'data' => $values,
					];
				}
			}
			$request['attributes'] = $attrs;
		}
	}
	// debug($request, 'stop');
	
	return $request;
}

function document_title($append='', $replace=' ') {
	$ci =& get_instance();
	$title = [];
	foreach ($ci->uri->rsegments as $key => $value) {
		if ($value != 'index' AND !is_numeric($value)) {
			$title[] = ucwords(preg_replace('/[_]/', $replace, trim($value)));
		}
	}
	return trim(str_replace('  ', ' ', implode(' » ', $title)).' '.$append);
}

function str_has_value_echo($search='', $in='', $echo='') {
	if ($in == $search) {
		echo $echo;
	}
}

function str_not_value_echo($search='', $in='', $echo='') {
	if ($in != $search) {
		echo $echo;
	}
}

function get_coordinates($data=false, $is_address=true, $sensor=0, $region='PH') {
	if ($data) {
		if ($is_address) {
			/*$data['city'],$data['street'],$data['province']*/
			$address = urlencode(implode(',', $data));
			$url = "https://maps.google.com/maps/api/geocode/json?key=".GOOGLEMAP_KEY."&address=".$address."&sensor=".($sensor ? 'true' : 'false')."&region=".$region.'&alternatives=true';
		} else {
			/*$data['lat'],$data['lng']*/
			$latlng = implode(',', $data);
			$url = "https://maps.google.com/maps/api/geocode/json?key=".GOOGLEMAP_KEY."&latlng=".$latlng."&sensor=".($sensor ? 'true' : 'false')."&region=".$region.'&alternatives=true';
		}
		// debug($url, 'stop');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$curl_response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($curl_response);
		// debug($response, 'stop');
		if (!empty($response) AND $response->status == 'OK') {
			$googlemap = $response->results[0];
			if ($is_address) {
				$coordinates = $googlemap->geometry->location;
				return $coordinates;
			} else {
				return isset($response->results[1]) ? $response->results[1] : $response->results[0];
			}
		}
	}
	return false;
}

function get_driving_distance($coordinates=false, $mode='driving', $language='ph') {
	if ($coordinates) {
		$origins = $destinations = false;
		foreach ($coordinates as $key => $coordinate) {
			if ($key == 0) {
				$origins = 'origins='.implode(',', $coordinate);
			} elseif ($key == 1) {
				$destinations = 'destinations='.implode(',', $coordinate);
			}
		}
		// debug($origins, $destinations);
		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLEMAP_KEY."&".$origins."&".$destinations."&mode=".$mode."&language=".$language.'&alternatives=true';
		// debug($url, 'stop');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$results = json_decode($response, true);

		// debug($results, 'stop');
		if ($results['status'] == 'OK') {
			$rows = $results['rows'][0];
			$elements = $rows['elements'][0];
			if ($elements['status'] != 'ZERO_RESULTS') {
				$distance = $elements['distance']['text'];
				$distanceval = $elements['distance']['value'];
				$duration = str_replace('hours 0 mins', 'hours', $elements['duration']['text']);
				$duration = str_replace('hour 0 mins', 'hour', $duration);
				$durationval = $elements['duration']['value'];
				return [
					'distance' => (float)$distanceval > 0 ? $distance : 'right away',
					'distanceval' => (float)$distanceval,
					'duration' => (float)$durationval > 0 ? $duration : 'right away',
					'durationval' => (float)$durationval,
				];
			}
		}
	}
	return ['distance' => 0, 'distanceval' => 0, 'distance' => 0, 'durationval' => 0];
}

function float2rat($n, $tolerance = 1.e-6) {
	$h1=1; $h2=0;
	$k1=0; $k2=1;
	$b = 1/$n;
	do {
		$b = 1/$b;
		$a = floor($b);
		$aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
		$aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
		$b = $b-$a;
	} while (abs($n-$h1/$k1) > $n*$tolerance);

	return "$h1/$k1";
}

function nearby_farms($data=false, $user_id=false)
{
	$farms = false;
	if ($data) {
		$ci =& get_instance();
		$profile = $ci->accounts->has_session ? $ci->accounts->profile : false;
		/*$SQL = "SELECT ufl.farm_id, ufl.lat, ufl.lng, 
		(
			6371 * #kilometers
			#3959 * #miles
			acos(cos(radians(".$data['lat'].")) * 
			cos(radians(ufl.lat)) * 
			cos(radians(ufl.lng) - 
			radians(".$data['lng'].")) + 
			sin(radians(".$data['lat'].")) * 
			sin(radians(ufl.lat)))
		) AS distance 
		FROM user_farm_locations ufl
		HAVING distance < ".METERS_DISTANCE_TO_USER." 
		ORDER BY distance
		;";
		$results = $ci->gm_db->query($SQL);*/
		$results = $ci->gm_db->query('SELECT ufl.id, ufl.farm_id, ufl.address_2, ufl.lat, ufl.lng FROM user_farm_locations ufl');
		// debug($results, 'stop');
		if ($results) {
			$farms = [];
			foreach ($results as $key => $row) {
				$driving_distance = get_driving_distance([
					['lat' => $data['lat'], 'lng' => $data['lng']],
					['lat' => $row['lat'], 'lng' => $row['lng']],
				]);
				// debug($driving_distance, 'stop');
				if ($driving_distance['distance'] AND $driving_distance['duration']) {
					$distance = (int)$driving_distance['distanceval'];
					$farms = get_farms_by_distance($farms, $row, $driving_distance, $user_id, $distance, METERS_DISTANCE_TO_USER);
					// $duration = (int)$driving_distance['durationval'];
					// $farms = get_farms_by_distance($farms, $row, $driving_distance, $user_id, $duration, SECONDS_DISTANCE_TO_USER);
				}
			}
		}
	}
	// debug($farms, 'stop');
	return $farms;
}

function get_farms_by_distance($farms, $row, $driving_distance, $user_id, $compare_1, $compare_2)
{
	$ci =& get_instance();
	if ($compare_1 <= $compare_2) {
		$where = ['id' => $row['farm_id']];
		if ($user_id) {
			$where['user_id'] = $user_id;
		}
		/*if ($profile) { // this is for the storefront ranking, abang lang
			$where['user_id'] = [$profile['id']];
			$farm = $ci->gm_db->get_not_in('user_farms', $where, 'row');
		} else {*/
			$farm = $ci->gm_db->get('user_farms', $where, 'row');
		/*}*/
		if ($farm) {
			$farm['farm_location_id'] = $row['id'];
			$user = $ci->gm_db->get('user_profiles', ['user_id' => $farm['user_id']], 'row');
			$farm['address'] = $row['address_2'];
			$farm['username'] = '';
			if ($user) {
				$farm['username'] = remove_multi_space($user['firstname'].' '.$user['lastname'], true);
			}
			$farm['distance'] = $driving_distance['distance'];
			$farm['duration'] = $driving_distance['duration'];
			$farm['distanceval'] = $driving_distance['distanceval'];
			$farm['durationval'] = $driving_distance['durationval'];
			$farm['storefront'] = storefront_url($farm);

			$address = explode(',', $row['address_2']);
			$farm['city'] = isset($address[0]) ? $address[0] : '';
			$farm['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';

			$farms[] = $farm;
		}
	}
	return $farms;
}

function nearby_veggies($data=false, $user_id=false)
{
	$veggies = false;
	if ($data) {
		$ci =& get_instance();
		$profile = $ci->accounts->has_session ? $ci->accounts->profile : false;
		$results = $ci->gm_db->query('SELECT ufl.id, ufl.farm_id, ufl.address_2, ufl.lat, ufl.lng FROM user_farm_locations ufl');
		// debug($results, 'stop');
		if ($results) {
			$veggies = [];
			foreach ($results as $key => $row) {
				$driving_distance = get_driving_distance([
					['lat' => $data['lat'], 'lng' => $data['lng']],
					['lat' => $row['lat'], 'lng' => $row['lng']],
				]);
				// var_dump($driving_distance);
				if ($driving_distance['distance'] AND $driving_distance['duration']) {
					$duration = (int)$driving_distance['durationval'];
					$veggies = get_items_by_distance($veggies, $row, $driving_distance, $user_id, $duration, SECONDS_DISTANCE_TO_USER, 5);
				}
			}
		}
	}
	// debug($veggies, 'stop');
	return $veggies;
}

function nearby_products($data=false, $user_id=false, $farm_location_id=false)
{
	$products = false;
	if ($data) {
		$ci =& get_instance();
		$profile = $ci->accounts->has_session ? $ci->accounts->profile : false;
		if (is_numeric($farm_location_id) AND $farm_location_id > 0) {
			$results = $ci->gm_db->query('SELECT ufl.id, ufl.farm_id, ufl.address_2, ufl.lat, ufl.lng FROM user_farm_locations ufl WHERE ufl.id = "'.$farm_location_id.'"');
		} else {
			$results = $ci->gm_db->query('SELECT ufl.id, ufl.farm_id, ufl.address_2, ufl.lat, ufl.lng FROM user_farm_locations ufl');
		}
		// debug($results, 'stop');
		if ($results) {
			$products = [];
			foreach ($results as $key => $row) {
				$driving_distance = get_driving_distance([
					['lat' => $data['lat'], 'lng' => $data['lng']],
					['lat' => $row['lat'], 'lng' => $row['lng']],
				]);
				// debug($driving_distance['distance'], 'stop');
				if ($driving_distance['distance'] AND $driving_distance['duration']) {
					$distance = (int)$driving_distance['distanceval'];
					$products = get_items_by_distance($products, $row, $driving_distance, $user_id, $distance, METERS_DISTANCE_TO_USER);
					// $duration = (int)$driving_distance['durationval'];
					// $products = get_items_by_distance($products, $row, $driving_distance, $user_id, $duration, SECONDS_DISTANCE_TO_USER);
				}
			}
		}
	}
	// debug($products, 'stop');
	return $products;
}

function get_items_by_distance($items, $row, $driving_distance, $user_id, $compare_1, $compare_2, $limit=false)
{
	$ci =& get_instance();
	if ($compare_1 <= $compare_2) {
		$items_locations = $ci->gm_db->get('products_location', ['farm_location_id' => $row['id']]);
		if ($items_locations) {
			$farm = $ci->gm_db->get('user_farms', ['id' => $row['farm_id']], 'row');
			foreach ($items_locations as $index => $location) {
				$where = ['id' => $location['product_id']];
				if ($user_id) {
					$where['user_id'] = $user_id;
				}
				/*if ($profile) { // this for the item ranking, abang lang
					$where['user_id'] = [$profile['id']];
					$item = $ci->gm_db->get_not_in('products', $where, 'row');
				} else {*/
					$item = $ci->gm_db->get('products', $where, 'row');
				/*}*/

				if ($item) {
					$item['farm_location_id'] = $row['id'];
					$item['category'] = false;
					$category = $ci->gm_db->get('products_category', ['id' => $item['category_id']], 'row');
					if ($category) $item['category'] = $category['label'];

					$item['subcategory'] = false;
					$subcategory = $ci->gm_db->get('products_subcategory', ['id' => $item['subcategory_id']], 'row');
					if ($subcategory) {
						$item['subcategory'] = $subcategory['label'];
					}

					$item['photos'] = false;
					$photos = $ci->gm_db->get('products_photo', ['product_id' => $location['product_id'], 'status' => 1]);
					if ($photos) {
						$item['photos'] = [];
						foreach ($photos as $key => $photo) {
							if ($photo['is_main']) {
								$item['photos']['main'] = $photo;
								break;
							}
						}
						foreach ($photos as $key => $photo) {
							if (!$photo['is_main']) {
								$item['photos']['other'][] = $photo;
							}
						}
					}

					$item['distance'] = $driving_distance['distance'];
					$item['duration'] = $driving_distance['duration'];
					$item['distanceval'] = $driving_distance['distanceval'];
					$item['durationval'] = $driving_distance['durationval'];
					$item['price'] = $location['price'];
					$item['measurement'] = $location['measurement'];
					$item['stocks'] = $location['stocks'];
					$item['storefront'] = storefront_url($item);
					$item['product_url'] = product_url($item);
					$items[] = $item;
					if (!is_bool($limit) AND is_numeric($limit)) {
						if (count($items) == $limit) {
							break;
						}
					}
				}
			}
		}
	}
	return $items;
}

function curl_add_booking($data=false)
{
	if ($data) {
		$data = json_decode('{
			"referral_code": "'.REFERRAL_CODE.'",
			"f_id": "",
			"pickupLocation": "Orchids St, San Jose del Monte City, Bulacan, Philippines",
			"pickupLocationDropoff": "Santa Maria, Bulacan, Philippines",
			"f_driver_id": "",
			"f_sender_name": "Eddie Garcia",
			"f_sender_mobile": "09172022385",
			"f_sender_landmark": "Test",
			"f_sender_address": "Orchids St, San Jose del Monte City, Bulacan, Philippines",
			"f_sender_address_lat": "14.8072588",
			"f_sender_address_lng": "121.0366074",
			"f_order_type_send": "1",
			"f_sender_date": "",
			"f_sender_datetime_from": "",
			"f_sender_datetime_to": "",
			"f_sen_add_in_city": "",
			"f_sen_add_in_pro": "",
			"f_sen_add_in_reg": "",
			"f_sen_add_in_coun": "",
			"f_recepient_name": "Eddie Garcia 1",
			"f_recepient_mobile": "09172022385",
			"f_recepient_landmark": "Test 1",
			"f_recepient_address": "Santa Maria, Bulacan, Philippines",
			"f_recepient_address_lat": "14.847608",
			"f_recepient_address_lng": "120.9808582",
			"f_order_type_rec": "1",
			"f_recepient_date": "",
			"f_recepient_datetime_from": "",
			"f_recepient_datetime_to": "",
			"f_rec_add_in_city": "",
			"f_rec_add_in_pro": "",
			"f_rec_add_in_reg": "",
			"f_rec_add_in_coun": "",
			"f_collectFrom": "R",
			"f_recepient_notes": "",
			"f_cargo": "Food",
			"f_cargo_others": "Food",
			"f_is_cod": "false",
			"f_express_fee": "false",
			"f_post": "{\"hash\":\"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcmljaW5nIjp7InByaWNlIjoxMTUsImRpc2NvdW50IjowLCJleHByZXNzRmVlIjowLCJjYXNoT25EZWxpdmVyeUZlZSI6MCwiZGlzdGFuY2UiOjExLjg2LCJkdXJhdGlvbiI6MzEsInByb21vQ29kZSI6bnVsbH0sImRpcmVjdGlvbnMiOnsiZGlzdGFuY2UiOjExLjg2LCJkdXJhdGlvbiI6MzEsIm9yaWdpbiI6eyJsYXRpdHVkZSI6MTQuODA3MjU4OCwibG9uZ2l0dWRlIjoxMjEuMDM2NjA3NH0sImRlc3RpbmF0aW9ucyI6W3sibGF0aXR1ZGUiOjE0Ljg0NzYwOCwibG9uZ2l0dWRlIjoxMjAuOTgwODU4Mn1dfSwiaWF0IjoxNjE3MzM1NDI0LCJleHAiOjE2MTczNzg2MjR9.007pn2CetO7bcDp-vxb3SQMPd5qdfPtnaul2f07oWaU\"}",
			"f_price": 115,
			"f_distance": "11.86 km",
			"f_duration": 31
		}', true);
		debug($data, 'stop');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://toktok.ph/app/websiteBooking/validate_website_inputs/');
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		// receive server response...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$output = curl_exec($ch);
		// echo $output; exit();
		curl_close($ch);
		$results = json_decode($output, true);

		debug($results, 'stop');
		return $results;
	}
	return false;
}

function is_last($data=false, $key=false)
{
	if ($data AND $key) {
		return count((array)$data) == $key +1;
	}
	return false;
}

function format_duration($duration=0)
{
	if ($duration) {
		$duration = (float)$duration;
		if ($duration <= 60) {
			if ($duration == 60) {
				$duration = '1 hour';
			} else {
				$duration .= ' minutes';
			}
		} else {
			$hours = (float)($duration / 60);
			$minutes = (float)($duration % 60);
			if ($hours == 1) {
				$duration = '1 hour ';
			} else {
				$duration .= $hours . ' hours ';
			}
			if ($minutes == 1) {
				$duration .= $minutes . ' minute';
			} elseif ($minutes > 1) {
				$duration .= $minutes . ' minutes';
			}
		}
	}
	return preg_replace('/\s+/', ' ', $duration);
}

function get_fullname($data=false, $other='', $return=false)
{
	if ($other != '') {
		$other .= ' #'.rand();
	}
	$fullname = $other;
	$ci =& get_instance();
	if ($data) {
		if ($ci->accounts->has_session) {
			if ($data['id'] == $ci->accounts->profile['id']) {
				$fullname = $ci->accounts->profile['firstname'];
			}
		} else {
			$fullname = remove_multi_space($data['firstname'].' '.$data['lastname'], true);
		}
	} elseif (isset($ci->accounts) AND $ci->accounts->has_session AND $other == '') {
		$fullname = remove_multi_space($ci->accounts->profile['firstname'].' '.$ci->accounts->profile['lastname'], true);
	}
	if ($return == false) {
		echo $fullname;
	} else {
		return $fullname;
	}
}

function identify_main_photo($product=false, $return=false, &$no_main=true)
{
	$main_photo = 'https://place-hold.it/100x100.png?text=No+Image&fontsize=14';
	if ($product) {
		if (!isset($product['photos']['main']) AND isset($product['photos']['other'])) {
			$main_photo = $product['photos']['other'][0]['url_path'];
		} elseif (isset($product['photos']['main'])) {
			$main_photo = $product['photos']['main']['url_path'];
			$no_main = false;
		}
	}
	// debug($no_main, 'stop');
	if ($return == false) {
		echo $main_photo;
	} else {
		return $main_photo;
	}
}