<?php

function debug()
{
	$args = func_get_args();
	echo "<pre>";
	foreach ($args as $index => $data) {
		if ($data !== 'stop') {
			$trace = debug_backtrace();
			try {
				if (!empty($trace)) {
					foreach ($trace as $key => $row) {
						$separator = '------';
						for ($i=0; $i < strlen($row['file']); $i++) $separator .= '-';
						
						if ($key == 0) {
							echo ($key==0?'<h1 style="margin:0;">DEBUGGER</h1>':'').$separator."<br /><b>PATH:</b> ".$row['file'];
							echo "<br /><b>LINE:</b> ".$row['line']."<br />";
						
						// if ($key == 0) {
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
	if (end($args) == 'stop') {
		exit();
	}
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

function format_ip($ip='')
{
	$ci =& get_instance();
	if (isset($ci->accounts) AND $ci->accounts->has_session) {
		$ID = $ci->accounts->profile['id'].$ci->accounts->profile['email_address'].GACELABS_SUPER_KEY.$ip;
		// $DEVICE = substr(md5($ID), 0, 7);
		$DEVICE = substr(md5($ID), 0, 7);
		if (get_mac_address()) {
			$DEVICE = substr(md5(get_mac_address()), 0, 7);
		}
		// debug($DEVICE, 1);
		$ci->device_id = strtoupper(GACELABS_KEY.$DEVICE);
		return $ci->device_id;
	}
	return 0; /*not yet member*/
}

function get_mac_address()
{
	// Turn on output buffering  
	ob_start();  
	// Get the ipconfig details using system commond  
	@system('ipconfig /all');  
	// Capture the output into a variable  
	$mycomsys = ob_get_contents();  
	// Clean (erase) the output buffer  
	ob_clean();  
	$find_mac = "Physical"; // Find the "Physical" & Find the position of Physical text  
	$pmac = strpos($mycomsys, $find_mac);  
	// Get Physical Address  
	$macaddress = substr($mycomsys, ($pmac+36), 17);  
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
							'file_path' => $uploadfile,
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
						'file_path' => $uploadfile,
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

function bike_search($query='', $and_clause='')
{
	$ci =& get_instance();
	/*limit words number of characters*/
	$query = substr($query, 0, 200);

	/*Weighing scores*/
	$score_bike_model = 6;
	$score_bike_model_keyword = 5;
	$score_made_by = 5;
	$score_made_by_keyword = 4;
	$score_full_content = 4;
	$score_content_keyword = 3;
	$score_spec_keyword = 2;
	$score_url_keyword = 1;

	/*Remove unnecessary words from the search term and return them as an array*/
	$query = trim(preg_replace("/(\s+)+/", " ", $query));
	$keywords = [];
	/*expand this list with your words.*/
	$list = ["in","it","a","the","of","or","I","you","he","me","us","they","she","to","but","that","this","those","then","by"];
	$c = 0;
	$separated_spaces = explode(" ", $query);
	if (count($separated_spaces) > 0){
		foreach($separated_spaces as $key){
			if (in_array($key, $list)) continue;
			$keywords[] = $key;
			if ($c >= 15) break;
			$c++;
		}
	}
	$escQuery = $ci->db->escape_like_str($query); /*see note above to get db object*/
	$titleSQL = [];
	$sumSQL = [];
	$docSQL = [];
	$categorySQL = [];
	$urlSQL = [];

	/** Matching full occurences **/ 
	$full_content = "CONCAT(REPLACE(b.feat_photo, 'assets/data/files/bikes/images/', ''),' ',b.fields_data,' ',b.price_tag)";
	if (count($keywords) > 1){
		$titleSQL[] = "IF(b.bike_model LIKE '%".$escQuery."%',{$score_bike_model},0)";
		// $sumSQL[] = "IF(b.made_by LIKE '%".$escQuery."%',{$score_made_by},0)";
		$docSQL[] = "IF($full_content LIKE '%".$escQuery."%',{$score_full_content},0)";
	}

	/** Matching Keywords **/
	if (count($keywords) > 0){
		foreach($keywords as $key){
			$titleSQL[] = "IF(b.bike_model LIKE '%".$ci->db->escape_like_str($key)."%',{$score_bike_model_keyword},0)";
			// $sumSQL[] = "IF(b.made_by LIKE '%".$ci->db->escape_like_str($key)."%',{$score_made_by_keyword},0)";
			$docSQL[] = "IF($full_content LIKE '%".$ci->db->escape_like_str($key)."%',{$score_content_keyword},0)";
			$urlSQL[] = "IF(b.external_link LIKE '%".$ci->db->escape_like_str($key)."%',{$score_url_keyword},0)";
			// $categorySQL[] = "IF(b.spec_from LIKE '%".$ci->db->escape_like_str($key)."%',{$score_spec_keyword},0)";
		}
	}

	/*Just incase it's empty, add 0*/
	if (empty($titleSQL)) $titleSQL[] = 0;
	// if (empty($sumSQL)) $sumSQL[] = 0;
	if (empty($docSQL)) $docSQL[] = 0;
	if (empty($urlSQL)) $urlSQL[] = 0;
	if (empty($tagSQL)) $tagSQL[] = 0;
	// if (empty($categorySQL)) $categorySQL[] = 0;

	$sql = "
	SELECT 
			u.store_name, CONCAT(b.id, '-', b.user_id, '/mtb/', REPLACE(LOWER(REPLACE(b.bike_model, ' ', '-')), '\'', ''), '-full-specifications') AS bike_url,
			b.*, ((".implode(' + ', $titleSQL).") + (".implode(' + ', $docSQL).") + (".implode(' + ', $urlSQL).")) as Relevance 
		FROM bike_items b 
		INNER JOIN users u ON u.id = b.user_id
		WHERE 1=1 $and_clause
	GROUP BY b.id 
		HAVING Relevance > 0 
	ORDER BY b.updated DESC";

	/*$sql = "
	SELECT 
			u.store_name, CONCAT(b.id, '-', b.user_id, '/mtb/', REPLACE(LOWER(REPLACE(b.bike_model, ' ', '-')), '\'', ''), '-full-specifications') AS bike_url,
			b.*, ((".implode(' + ', $titleSQL).") + (".implode(' + ', $sumSQL).") + (".implode(' + ', $docSQL).") + (".implode(' + ', $categorySQL).") + (".implode(' + ', $urlSQL).")) as Relevance 
		FROM bike_items b 
		INNER JOIN users u ON u.id = b.user_id
		WHERE 1=1 $and_clause
	GROUP BY b.id 
		HAVING Relevance > 0 
	ORDER BY b.updated DESC";*/

	// debug($sql, 1);
	$data = $ci->db->query($sql);

	if ($data->num_rows() > 0){
		return $data->result_array();
	}
	return [];
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
	return $url;
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
			echo $callback . '('.$js_function.'('.json_encode($payload).'))';
		} else {
			echo $callback . '('.json_encode($payload).')';
		}
	}
	exit();
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

function check_data_values($data=false, $is_equal_to='') {
	/*method on checking blank values in a post or get*/
	$has_values = false; $length = 0;
	if ($data) {
		/*check if $data has values*/
		if (is_array($data)) {
			$values = [];
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $index => $val) {
						++$length;
						if (!is_bool($val) AND strlen(trim($val)) > 0) {
							if ($is_equal_to != '') {
								$values[] = $val === $is_equal_to;
							} else {
								$values[] = true;
							}
						} elseif (is_bool($val)) {
							$values[] = $val;
						}
					}
				} else {
					++$length;
					if (!is_bool($value) AND strlen(trim($value)) > 0) {
						if ($is_equal_to != '') {
							$values[] = $value === $is_equal_to;
						} else {
							$values[] = true;
						}
					} elseif (is_bool($value)) {
						$values[] = $value;
					}
				}
			}
			// debug($data); debug(in_array(false, $values)); debug($values, 1);
			/*if false do not exist then it's true*/
			if (!empty($values) AND count($values) === $length) {
				$has_values = (in_array(false, $values) == false) ? true : false;
			}
		} else {
			if ($is_equal_to != '') {
				$has_values = $data === $is_equal_to;
			} else {
				if (!is_bool($value) AND strlen(trim($value)) > 0) {
					$has_values = true;
				} elseif (is_bool($value)) {
					$has_values = $value;
				}
			}
		}
	}
	// debug($has_values, $length, 'stop');
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

function is_set_echo($array=false, $key=false) {
	if ($key AND is_array($array)) {
		if (isset($array[$key])) {
			echo $array[$key];
		}
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