<?php if ($baskets): ?>
	<?php foreach ($baskets as $location_by_order_type => $basket_items): ?>
		<?php
			$key_data = explode('|', $location_by_order_type);
			$location_id = $key_data[0];
			$order_type = $key_data[1];
			$schedule = (!is_null($key_data[2]) AND $key_data[2] == '0000-00-00') ? '' : $key_data[2];
			$this->view('templates/basket/basket_items', [
				'baskets' => $basket_items,
				'location_id' => $location_id,
				'order_type' => $order_type,
				'schedule' => $schedule,
			]);
		?>
	<?php endforeach ?>
<?php endif ?>