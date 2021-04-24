<div class="container" id="thankyou_container">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="thankyou-middle">
			<div class="text-center" id="thankyou_top">
				<img src="assets/images/icons/thankyou.gif">
			</div>
			<div id="thankyou_middle">
				<div style="margin-bottom:20px;text-align:center;">
					<h1 class="text-white" style="margin-bottom:15px;">THANK YOU!</h1>
					<a href="orders/placed" class="btn btn-theme">View Purchase</a>
				</div>

				<div id="thankyou_footer">
					<ul class="spaced-list between">
						<li><p class="zero-gaps"><b>Cash On Delivery</b></p></li>
						<?php if (isset($data['total'])): ?>
							<li><p class="zero-gaps">&#x20b1; <b class="text-contrast"><?php echo number_format($data['total']);?></b></p></li>
						<?php endif ?>
					</ul>
					<hr class="carved" style="margin-top:15px;">
					<div class="thankyou-grid">
						<div>
							<span>
								 <img src="assets/images/icons/ordered.png">
								<span class="text-white"><b>Placed Order</b></span>
							</span>
						</div>
						<div>
							<span>
								<img src="assets/images/icons/deliver.png">
								<span><b>On Delivery</b></span>
							</span>
						</div>
						<div>
							<span>
								<img src="assets/images/icons/received.png" style="transform: rotateY(180deg);">
								<span><b>Order Received</b></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<ul class="spaced-list between" style="margin-top:15px;">
				<li><a href="marketplace/" class="text-link"><i class="fa fa-angle-left"></i> Marketplace</a></li>
				<li class="text-right">
					<a href="javascript:;" class="text-danger">Cancel</a>
					<small class="elem-block text-gray">Allowed: 60 seconds</small>
				</li>
			</ul>
		</div>
	</div>
</div>