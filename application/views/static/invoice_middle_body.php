<?php
	$seller = json_decode(base64_decode($seller), true);
	$buyer = json_decode(base64_decode($buyer), true);
	$order_details = json_decode(base64_decode($order_details), true);
?>
<div id="zig-wrapper" js-element="to-print" js-id="<?php echo $id;?>" class="hide">
	<div class="zig-zag-bottom zig-zag-top">
		<div class="zig-body">
			<div class="zig-top">
				<div class="text-center" style="margin-bottom:15px;">
					<img src="assets/images/icons/deliver.png" width="70" style="margin-bottom:15px;">
					<h4>Your order is on its way!</h4>
				</div>
				<div class="invoice-deliver-info-container">
					<p><small class="elem-block text-gray">DELIVERS TO</small></p>
					<div class="zig-top-grid">
						<div class="text-center">
							<i class="fa fa-id-badge"></i>
						</div>
						<p class="zero-gaps"><?php echo $buyer['fullname'];?></p>
					</div>
					<div class="zig-top-grid">
						<div class="text-center">
							<i class="fa fa-map-marker"></i>
						</div>
						<?php if ($buyer['shippings']): ?>
							<?php foreach ($buyer['shippings'] as $key => $shipping): ?>
								<?php if ($shipping['active'] == 1): ?>
									<div>
										<p class="zero-gaps"><?php echo ucwords($shipping['address_1']);?></p>
										<small class="text-gray elem-block"><?php echo $shipping['address_2'];?></small>
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
							<?php if ($current_profile['id'] == $buyer['id']) : ?>
							<p class="zero-gaps"><?php echo $buyer['profile']['phone'];?></p>
							<?php else : ?>
							<p class="zero-gaps"><?php echo substr($buyer['profile']['phone'], 0, 4);?>-***-****</p>
							<?php endif; ?>
							<small class="elem-block text-gray">Not visible to seller</small>
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
							<?php if ($details['status'] == 5) continue; ?>
							<div class="invoice-summary-grid">
								<div>
									<p class="text-ellipsis"><?php echo $details['product']['name'];?></p>
									<small class="elem-block text-gray">QTY <?php echo $details['quantity'];?> - <?php echo $details['measurement'];?></small>
								</div>
								<div class="text-right">&#x20b1; <?php echo number_format($details['price'] * $details['quantity']);?></div>
							</div>
							<?php $total += $details['price'] * $details['quantity'];?>
						<?php endforeach ?>
					<?php endif ?>
					<div class="invoice-summary-grid">
						<div>
							<p class="text-ellipsis"><?php echo $seller['city'];?> to <?php echo $buyer['firstname'];?>'s address</p>
							<small class="elem-block text-gray">Delivery Fee</small>
						</div>
						<div class="text-right">&#x20b1; <?php echo number_format($fee);?></div>
					</div>
					<hr>
					<div class="invoice-summary-grid">
						<div>
							<p class="text-ellipsis"><b>TOTAL</b></p>
							<small class="elem-block text-gray">Pay upon delivery</small>
						</div>
						<div class="text-right">&#x20b1; <b class="text-contrast"><?php echo number_format($total + $fee);?></b></div>
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
					<li class="text-right">
						<button class="btn btn-sm" js-element="print-action" data-html2canvas-ignore>Print<i class="fa fa-print icon-right"></i></button>
					</li>
				</ul>
				<div style="margin-top:20px;text-align: center;">
					<p class="zero-gaps"><small><span class="text-gray">BY:</span> <a href="gulaymart.com" class="text-theme"><i class="fa fa-leaf"></i> GULAYMART</a></small></p>
				</div>
			</div>
		</div>
	</div>
</div>