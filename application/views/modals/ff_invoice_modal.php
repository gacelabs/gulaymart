
<div class="modal fade" id="ff_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="ff_invoice_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius:0;">
			<div class="modal-body" style="padding:0;">
				<div id="more_info_container">
					<div class="more-info-top">
						<ul class="spaced-list between">
							<li><b>INVOICE<i class="fa fa-file-text-o icon-right"></i></b></li>
							<li><button class="close" data-dismiss="modal" aria-label="Close">&times;</button></li>
						</ul>
					</div>
					<p class="zero-gaps text-center hide" js-data="loader"><span class="spinner-border spinner-border-sm"></span> LOADING ...</p>
					<div class="more-info-middle" js-element="invoice-body">
						<div id="zig-wrapper" class="hide" js-element="to-print">
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
												<p class="zero-gaps">Poi Garcia</p>
											</div>
											<div class="zig-top-grid">
												<div class="text-center">
													<i class="fa fa-map-marker"></i>
												</div>
												<div>
													<p class="zero-gaps">49 Cluster J, Bagong Nayon, Antipolo City 1870</p>
													<small class="elem-block">Antipolo, Rizal, Calabarzon, Philippines</small>
												</div>
											</div>
											<div class="zig-top-grid">
												<div class="text-center">
													<i class="fa fa-phone"></i>
												</div>
												<div>
													<?php if ($current_profile) : ?>
													<p class="zero-gaps">0999-992-1745</p>
													<?php else : ?>
													<p class="zero-gaps">0999-***-****</p>
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
											<div class="invoice-summary-grid">
												<div>
													<p class="text-ellipsis">Freshly Picked Organic Sweet White Onions Freshly Picked Organic Sweet White Onions</p>
													<small class="elem-block text-gray">QTY 3 - KILO</small>
												</div>
												<div class="text-right">&#x20b1; 300</div>
											</div>
											<div class="invoice-summary-grid">
												<div>
													<p class="text-ellipsis">Hydrophonic Romaine Lettuce</p>
													<small class="elem-block text-gray">QTY 3 - KILO</small>
												</div>
												<div class="text-right">&#x20b1; 300</div>
											</div>
											<div class="invoice-summary-grid">
												<div>
													<p class="text-ellipsis">Antipolo City to Poi's address</p>
													<small class="elem-block text-gray">Delivery Fee</small>
												</div>
												<div class="text-right">&#x20b1; 70</div>
											</div>
											<hr>
											<div class="invoice-summary-grid">
												<div>
													<p class="text-ellipsis"><b>TOTAL</b></p>
													<small class="elem-block text-gray">Pay upon delivery</small>
												</div>
												<div class="text-right">&#x20b1; <b class="text-contrast">670</b></div>
											</div>
										</div>
									</div>
									<hr>
									<div class="zig-bottom">
										<ul class="spaced-list between">
											<li class="text-left">
												<small class="elem-block"><b>ORDER ID</b></small>
												<p class="text-contrast zero-gaps">7GH6JK5R4L</p>
											</li>
											<li class="text-right">
												<button class="btn btn-sm">Print<i class="fa fa-print icon-right"></i></button>
											</li>
										</ul>
										<div style="margin-top:20px;text-align: center;">
											<p class="zero-gaps"><small><span class="text-gray">BY:</span> <a href="gulaymart.com" class="text-theme"><i class="fa fa-leaf"></i> GULAYMART</a></small></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>