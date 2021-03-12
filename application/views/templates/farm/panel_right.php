<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-6">
				<div class="dash-panel theme">
					<ul class="spaced-list between dash-panel-top">
						<li><h3>New Veggie</h3></li>
					</ul>
					<form action="farm/new-veggy" method="post" enctype="multipart/form-data" class="form-validate" data-ajax="1">
						<div class="dash-panel-middle">
								<input type="text" name="product[name]" placeholder="Name" required="required">
								<input type="text" name="product[price]" placeholder="Price" required="required">
								<select name="product[measurement]" required="required">
									<option value="">Measurement</option>
									<option value="kg">Kilo</option>
								</select>
								<select name="product[category_id]" required="required">
									<option value="">Category</option>
									<option value="hydrophonic">Hydrophonic</option>
								</select>
						</div>
						<div class="dash-panel-footer">
							<button>Add</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>