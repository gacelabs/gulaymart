<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="dash-panel-right-canvas">
			<div class="col-lg-7">
				<div class="dash-panel theme" id="new_veggy">
					<ul class="spaced-list around dash-panel-top">
						<li><h3 class="text-capsule">10<small>%</small></h3></li>
						<li><h3 class="text-capsule">30<small>%</small></h3></li>
						<li><h3 class="text-capsule">60<small>%</small></h3></li>
						<li><h3 class="text-capsule">80<small>%</small></h3></li>
						<li><h3 class="text-capsule"><i class="fa fa-check"></i></h3></li>
					</ul>
					<div class="timeline-border"></div>
					<div class="dash-panel-middle">
						<form action="" method="post" enctype="multipart/form-data">
							<div class="input-container">
								<label for="product_name">Product name</label>
								<div class="input-group">
									<input type="text" class="form-control" id="product_name" name="products[name]" required="required" placeholder="Example: Fresh & Organic Romaine Lettuce [Per kilo]">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="prod_name_checker"><i class="fa fa-chevron-right color-theme"></i></button>
									</span>
								</div>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Customer likes a short yet concise Product name that tells essential things about your listing.</small>
							</div>
							<div class="input-container hide" id="category_container">
								<label for="product_name">Category</label>
								<ul class="inline-list">
									<li class="input-capsule"><input type="checkbox" name="category" id="leafy" value="leafy"><label for="leafy">Leafy</label></li>
									<li class="input-capsule"><input type="checkbox" name="category" id="root" value="root"><label for="root">Root</label></li>
									<li class="input-capsule"><input type="checkbox" name="category" id="cruciferous" value="cruciferous"><label for="cruciferous">Cruciferous</label></li>
									<li class="input-capsule"><input type="checkbox" name="category" id="marrow" value="marrow"><label for="marrow">Marrow</label></li>
									<li class="input-capsule"><input type="checkbox" name="category" id="stem" value="stem"><label for="stem">Stem</label></li>
									<li class="input-capsule"><input type="checkbox" name="category" id="allium" value="allium"><label for="allium">Allium</label></li>
								</ul>
								<div class="hide arrow_box" id="sub_category">
									<p>Sub Category</p>
									<ul class="inline-list"></ul>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>