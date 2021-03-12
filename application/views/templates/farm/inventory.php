
<?php /*debug($data, 'stop');*/ ?>
<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-12">
				<div class="dash-panel theme">
					<ul class="spaced-list between dash-panel-top">
						<li><h3>New Veggie</h3></li>
					</ul>
					<?php if ($data['products']): ?>
					<table border="1">
						<tr>
							<?php foreach ($data['products'][0] as $key => $value): ?>
								<th><?php echo fix_title($key);?></th>
							<?php endforeach ?>
						</tr>
						<?php foreach ($data['products'] as $key => $product): ?>
							<tr>
								<?php foreach ($product as $index => $value): ?>
									<th><?php echo $value;?></th>
								<?php endforeach ?>
							</tr>
						<?php endforeach ?>
					</table>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>