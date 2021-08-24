<?php if ($baskets): ?>
	<?php foreach ($baskets as $location_by_order_type => $basket_items): ?>
		<?php
			$key_data = explode('|', $location_by_order_type);
			$location_id = $key_data[0];
			$order_type = $key_data[1];
			$basket_ids = $basket_items['basket_ids'];
			asort($basket_ids);
			$combi = " id-".implode("-item id-", $basket_ids)."-item";
			$schedule = (!is_null($key_data[2]) AND $key_data[2] == '0000-00-00') ? '' : $key_data[2];
			$row_data = [
				'id_combi' => $combi,
				'baskets' => $basket_items,
				'location_id' => $location_id,
				'order_type' => $order_type,
				'schedule' => $schedule,
			];
			$this->view('templates/basket/basket_items', $row_data);
		?>
	<?php endforeach ?>
<?php endif ?>