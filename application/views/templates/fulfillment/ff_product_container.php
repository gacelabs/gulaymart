<div class="row hidden-xs ff-product-container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
		
		<div class="order-table-item">
			<div class="order-grid-column order-labels">
				<div class="text-left">
					<p><small class="elem-block"><b>PRODUCT</b></small></p>
				</div>
				<div class="text-right hidden-sm hidden-xs">
					<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
				</div>
				<div class="text-right hidden-sm hidden-xs">
					<p><small class="elem-block"><b>QUANTITY</b></small></p>
				</div>
				<div class="text-right">
					<?php if (in_array("ff-placed", $middle['body_class'])) : ?>
					<p><small class="elem-block"><b>ACTION</b> <i class="fa fa-question-circle" tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="left" title="Confirm or Cancelled" data-content="Let your customer know that you're ready to fulfill the order, otherwise, select the reason to cancel."></i></small></p>
					<?php else : ?>
					<p><small class="elem-block"><b>ACTION</b></small></p>
					<?php endif ; ?>
				</div>
			</div>

			<div class="order-item-list">
				<!-- per order -->
				<div class="order-grid-column order-item">
					<div class="media">
						<div class="media-left media-top">
							<img class="media-object" src="https://via.placeholder.com/50x50.png?text=50x50">
						</div>
						<div class="media-body">
							<p class="zero-gaps media-heading text-ellipsis"><a href="" class="text-link">Fresh Sweet White Onions</a></p>
							<div class="ellipsis-container">
								<p class="zero-gaps">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus. Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
							</div>
						</div>
					</div>
					<div class="text-right hidden-sm hidden-xs">
						<p class="zero-gaps">&#x20b1; 140 / KILO</p>
					</div>
					<div class="text-right hidden-sm hidden-xs">
						<p class="zero-gaps">3</p>
					</div>
					<div class="text-right">
						<?php if (in_array("ff-placed", $middle['body_class'])) : ?>
						<select class="form-control" js-event="actionSelect">
							<option selected disabled>Select</option>
							<option value="1">Confirm</option>
							<option value="2">Cancelled</option>
						</select>
						<select class="form-control hide" id="cancelled_reason" style="margin-bottom:0;">
							<option>Out Of Stock</option>
							<option>Removed Product</option>
						</select>
						<?php else : ?>
							<p class="zero-gaps"><span class="text-capsule bg-theme">Confirmed</span></p>
						<?php endif ; ?>
					</div>

					<div class="visible-sm visible-xs">
						<ul class="spaced-list between">
							<li><p class="zero-gaps">&#x20b1; 140 / KILO</p></li>
							<li class="icon-right"><p class="zero-gaps">x 3 QTY</p></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="order-grid-footer">
				<div class="order-footer-farm text-left hidden-xs">
					<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
					<p class="zero-gaps"><a href="" class="text-link">Ema Margaret Farm</a></p>
					<p class="zero-gaps">Bagong Nayon, Antipolo City</p>
				</div>
				<div class="order-footer-payment text-left hidden-xs">
					<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
					<p class="zero-gaps">Cash On Delivery</p>
					<p class="zero-gaps"></p>
				</div>
				<div class="text-left hidden-xs">
					<p class="zero-gaps"><small class="elem-block"><b>ORDER INVOICE</b></small></p>
					<?php if (in_array("ff-placed", $middle['body_class'])) : ?>
						Available on Pick Up (Status)
					<?php else : ?>
						<button class="btn btn-sm btn-default" data-toggle="modal" data-target="#ff_invoice_modal">INVOICE<i class="fa fa-file-text-o icon-right"></i></button>
					<?php endif ; ?>
				</div>
				<div class="text-left hidden-xs">
					<?php if (in_array("ff-placed", $middle['body_class'])) : ?>
						<p style="margin-bottom:5px;"><small class="elem-block"><b>PROCEED</b></small></p>
						<button class="btn btn-sm btn-contrast">READY FOR DELIVERY<i class="fa fa-angle-right icon-right"></i></button>
					<?php else : ?>
						<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
						<p class="zero-gaps"><span class="text-capsule status-pickup">For Pick Up</span></p>
					<?php endif ; ?>
				</div>
			</div>
		</div>

	</div>
</div>