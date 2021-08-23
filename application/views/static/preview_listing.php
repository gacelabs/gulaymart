
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" id="preview_container">
	<div id="swiped" class="dashboard-panel<?php if ($this->agent->is_mobile()): ?> swipe-thing"<?php endif ?>" data-urls='<?php echo json_encode($data['pagination']);?>'>
		<div class="dashboard-panel-top">
			<ul class="spaced-list between">
				<li><label>Preview listing</label></li>
			</ul>
			<div class="product-list-card">
				<div class="product-list-photo order-photo" style="background-image:url('<?php identify_main_photo($data['product']);?>'); position: relative;">
					<?php if ($data['pagination'] AND $this->agent->is_mobile()): ?>
						<a href="<?php echo $data['pagination']['prev'];?>#swiped"><i class="fa fa-chevron-left icon-left" style="position: absolute; top: 85%; left: -30px;"></i></a>
						<a href="<?php echo $data['pagination']['next'];?>#swiped"><i class="fa fa-chevron-right icon-right" style="position: absolute; top: 85%; right: -30px;"></i></a>
					<?php endif ?>
				</div>
				<div class="product-desc-body">
					<div class="product-title-container ellipsis-container">
						<h1 class="zero-gaps order-title"><?php isset_echo($data['product'], 'name', 'No name');?></h1>
						<p><?php isset_echo($data['product'], 'description', '');?></p>
					</div>
				</div>
				<div class="product-list-footer">
				<?php if (isset($data['product']) AND (isset($data['product']['latlng']) AND $data['product']['latlng'])): ?>
					<?php foreach ($data['product']['latlng'] as $key => $prod): ?>
						<ul class="spaced-list between">
							<li style="display: flex; justify-content: space-between; flex-direction: row; flex: auto;">
								<p class="product-price">₱ <span class="order-price"><?php echo format_number($prod['price']);?></span> / <span class="order-unit"><?php echo ucwords($prod['measurement']);?></span></p>
								<p class="product-price"><i class="fa fa-clock-o"></i> <span class="order-duration"><?php echo ucwords($prod['duration']);?></span></p>
							</li>
						</ul>
					<?php endforeach ?>
				<?php else: ?>
					<ul class="spaced-list between">
						<li style="display: flex; justify-content: space-between; flex-direction: row; flex: auto;">
							<p class="product-price">₱ <span class="order-price">--</span> / <span class="order-unit">--</span></p>
							<p class="product-price"><i class="fa fa-clock-o"></i> <span class="order-duration">--</span></p>
						</li>
					</ul>
				<?php endif ?>
				</div>
			</div>
			<div class="new-veggy-status-container">
				<?php
				$status = 'None';
				if (isset($data['product']) AND isset($data['product']['activity'])) {
					$status = ($data['product']['activity'] == GM_ITEM_DRAFT ? 'For review' : ($data['product']['activity'] == GM_ITEM_REJECTED ? 'Unpublished' : ($data['product']['activity'] == GM_ITEM_DELETED ? 'Deleted' : 'Published')));
				}
				?>
				<h5 class="zero-gaps text-center"><b>Status:</b> <span class="order-status"><?php echo $status;?></span></h5>
			</div>

			<?php if ($data['pagination'] AND !$this->agent->is_mobile()): ?>
				<div style="margin-top:15px;text-align:right;">
					<a href="<?php echo $data['pagination']['prev'];?>#swiped" class="btn btn-theme pull-left" type="submit" loading-text=""><i class="fa fa-chevron-left icon-left"></i>Previous</a>
					<a href="<?php echo $data['pagination']['next'];?>#swiped" class="btn btn-theme" type="submit" loading-text="">Next<i class="fa fa-chevron-right icon-right"></i></a>
				</div>
			<?php endif ?>
		</div>
		<!-- <div class="dashboard-panel-footer visible-xs">
			<a href="farm/my-veggies/#score_detail_container" class="btn btn-default btn-block" style="width:220px;margin:0 auto;">
				<i class="fa fa-chevron-down icon-down"></i> New Veggy <i class="fa fa-chevron-down icon-down"></i>
			</a>
		</div> -->
	</div>
</div>