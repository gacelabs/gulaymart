<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baskets {

	protected $class = false; 
	public $has_session = false; 
	public $profile = false;
	public $device_id = false;

	public function __construct()
	{
		$this->class =& get_instance();
		$this->has_session = $this->class->session->userdata('profile') ? true : false;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function get($where=true, $row=false, $limit=false, $order_by='location_id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			$data = $this->class->db->order_by($order_by)->get_where('baskets', $where);
			if (isset($data) AND $data->num_rows()) {
				$baskets = $data->result_array();
				// debug($baskets, 'stop');
				foreach ($baskets as $key => $basket) {
					if (is_string($basket['rawdata'])) {
						$basket['rawdata'] = $baskets[$key]['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
						$driving_distance = get_driving_distance([
							['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
							['lat' => $this->class->latlng['lat'], 'lng' => $this->class->latlng['lng']],
						]);
						$baskets[$key]['distance'] = $driving_distance['distanceval'];
						$baskets[$key]['duration'] = $driving_distance['durationval'];
						$baskets[$key]['distance_text'] = $driving_distance['distance'];
						$baskets[$key]['duration_text'] = $driving_distance['duration'];
					}
				}
				if ($row) {
					return $baskets[0];
				} else {
					return $baskets;
				}
			}
		}
		return false;
	}

	public function get_in($where=true, $row=false, $limit=false, $order_by='location_id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->where_in($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('baskets');
			if (isset($data) AND $data->num_rows()) {
				$baskets = $data->result_array();
				// debug($baskets, 'stop');
				foreach ($baskets as $key => $basket) {
					if (is_string($basket['rawdata'])) {
						$basket['rawdata'] = $baskets[$key]['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
						$driving_distance = get_driving_distance([
							['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
							['lat' => $this->class->latlng['lat'], 'lng' => $this->class->latlng['lng']],
						]);
						$baskets[$key]['distance'] = $driving_distance['distanceval'];
						$baskets[$key]['duration'] = $driving_distance['durationval'];
						$baskets[$key]['distance_text'] = $driving_distance['distance'];
						$baskets[$key]['duration_text'] = $driving_distance['duration'];
					}
				}
				if ($row) {
					return $baskets[0];
				} else {
					return $baskets;
				}
			}
		}
		return false;
	}

	public function count($where=false)
	{
		if ($where == false) {
			return $this->class->db->from('baskets')->count_all_results();
		} else {
			return $this->class->db->from('baskets')->where($where)->count_all_results();
		}
	}

	public function new($new=false)
	{
		if ($new) {
			$this->class->db->insert('baskets', $new);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}


	public function save($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('baskets', $set, $where);
			return true;
		}
		return false;
	}

}