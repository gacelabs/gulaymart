<?php
	$is_data_set = false;
	if (isset($data)) {
		// debug($data, true);
		$id = $data['id'];
		$fee = $data['fee'];
		$status = $data['status'];
		$order_id = $data['order_id'];
		$seller = $data['seller'];
		$buyer = $data['buyer'];
		$order_details = $data['order_details'];
		$is_data_set = true;
	}
	$seller = json_decode(base64_decode($seller), true);
	$buyer = json_decode(base64_decode($buyer), true);
	$order_details = json_decode(base64_decode($order_details), true);
	// debug($status, true);
?>
<div js-element="to-print" id="zig-wrapper" js-id="<?php echo $id;?>"<?php if ($is_data_set == false): ?> class="hide"<?php endif ?>>
	<div class="zig-zag-bottom zig-zag-top">
		<div class="zig-body">
			<div class="zig-top">
				<div class="text-center" style="margin-bottom:15px;">
					<?php switch ($status) {
						case 3:
							echo '<img src="assets/images/icons/deliver.png" width="70" style="margin-bottom:15px;transform:scaleX(-1);-webkit-transform: scaleX(-1);">';
							echo "<h4>Order is now On Delivery!</h4>";
							break;
						case 4:
							echo "<h4>Order Received!</h4>";
							break;
						case 5:
							echo "<h4>Order was Cancelled!</h4>";
							break;
						default:
							echo '<img src="assets/images/icons/deliver.png" width="70" style="margin-bottom:15px;">';
							echo "<h4>Order is Ready for Pick Up!</h4>";
							break;
					}?>
				</div>

				<div class="invoice-deliver-info-container">
					<p><small class="elem-block text-gray">DELIVERS TO</small></p>
					<div class="zig-top-grid">
						<div class="text-center">
							<i class="fa fa-id-badge"></i>
						</div>
						<p class="zero-gaps"><?php echo ($buyer['id'] == $current_profile['id']) ? '<i>(You) </i>' : '';?><?php echo ucwords($buyer['fullname']);?></p>
					</div>
					<div class="zig-top-grid">
						<div class="text-center">
							<i class="fa fa-map-marker"></i>
						</div>
						<?php $buyer_address = ''; ?>
						<?php if ($buyer['shippings']): ?>
							<?php foreach ($buyer['shippings'] as $key => $shipping): ?>
								<?php if ($shipping['active'] == 1): ?>
									<div>
										<p class="zero-gaps"><?php echo ucwords($shipping['address_1']);?></p>
										<small class="text-gray elem-block"><?php echo $shipping['address_2'];?></small>
										<?php $buyer_address = $shipping['address_2']; ?>
									</div>
									<?php break; ?>
								<?php endif ?>
							<?php endforeach ?>
						<?php endif ?>
					</div>
					<div class="zig-top-grid">
						<div class="text-center">
							<i class="fa fa-phone"></i>
						</div>
						<div>
							<?php if ($current_profile AND ($current_profile['id'] == $buyer['id'])) : ?>
								<p class="zero-gaps"><?php echo $buyer['profile']['phone'];?></p>
								<small class="elem-block text-gray">Not visible to seller</small>
							<?php else : ?>
								<p class="zero-gaps"><?php echo substr($buyer['profile']['phone'], 0, 4);?>-***-****</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="zig-middle">
				<div class="invoice-summary-container">
					<div class="text-center" style="margin:15px 0;">
						<h4 class="zero-gaps">ORDER SUMMARY</h4>
					</div>
					<?php $total = 0;?>
					<?php if ($order_details): ?>
						<?php foreach ($order_details as $key => $details): ?>
							<?php /*if ($details['status'] == 5) continue;*/ ?>
							<div class="invoice-summary-grid">
								<div>
									<p class="text-ellipsis"><?php echo ucwords($details['product']['name']);?></p>
									<small class="elem-block text-gray">QTY <?php echo $details['quantity'];?> - <?php echo $details['measurement'];?></small>
								</div>
								<div class="text-right">&#x20b1; <?php echo format_number($details['price'] * $details['quantity']);?></div>
							</div>
							<?php $total += $details['price'] * $details['quantity'];?>
						<?php endforeach ?>
					<?php endif ?>
					<div class="invoice-summary-grid">
						<div>
							<p class="text-ellipsis"><?php echo $seller['city'];?> <b>TO</b> <?php echo ($buyer['id'] == $current_profile['id']) ? 'Your Address' : $buyer_address;?></p>
							<small class="elem-block text-gray">Delivery Fee</small>
						</div>
						<div class="text-right">&#x20b1; <?php echo format_number($fee);?></div>
					</div>
					<hr>
					<div class="invoice-summary-grid">
						<div>
							<p class="text-ellipsis"><b>TOTAL</b></p>
							<small class="elem-block text-gray">Pay upon delivery</small>
						</div>
						<div class="text-right">&#x20b1; <b class="text-contrast"><?php echo format_number($total + $fee);?></b></div>
					</div>
				</div>
			</div>
			<hr>
			<div class="zig-bottom">
				<ul class="spaced-list between">
					<li class="text-left">
						<small class="elem-block"><b>ORDER ID</b></small>
						<p class="text-contrast zero-gaps"><?php echo $order_id;?></p>
					</li>
					<?php if ($is_data_set == false): ?>
						<li class="text-right">
							<button class="btn btn-sm" js-element="print-action" data-html2canvas-ignore>Print<i class="fa fa-print icon-right"></i></button>
						</li>
					<?php endif ?>
				</ul>
				<div style="margin-top:20px;text-align: center;">
					<p class="zero-gaps"><small><span class="text-gray">BY:</span> <a href="<?php echo base_url();?>" class="text-theme"><i class="fa fa-leaf"></i> GULAYMART</a></small></p>
				</div>
			</div>
		</div>
	</div>
</div>

<hr class="carved">

<div class="text-step-basic" style="margin:0;">
	<p class="text-center zero-gaps"><i class="fa fa-info-circle"></i></p>
	<div>
		<?php if ($current_profile AND ($current_profile['id'] == $buyer['id'])): ?>
			<p class="zero-gaps">Invoice was sent to your email.</p>
		<?php else: ?>
			<p class="zero-gaps">Invoice wil be sent to customer's email.</p>
		<?php endif; ?>
	</div>
</div>