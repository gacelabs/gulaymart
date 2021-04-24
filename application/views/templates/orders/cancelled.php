<div class="order-list-container hide" id="order_cancelled">
	<?php if ($cancelled): ?>
		<div class="order-item">
			<?php foreach ($cancelled as $date => $order): ?>
				<div class="order-item-top">
					<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
					<p class="zero-gaps">CANCELLED: <b><?php echo $date;?></b></p>
				</div>
				<?php $this->view('looping/order_item', ['order'=>$order, 'large_status'=>'CANCELLED', 'status_class'=>'cancelled']);?>
			<?php endforeach ?>
		</div>
	<?php else: ?>
		<h4 style="padding:15px;margin:0;">Fresh veggies at your doorstep in minutes, <a href="marketplace/" class="text-link">shop now!</a></h4>
	<?php endif ?>
</div>