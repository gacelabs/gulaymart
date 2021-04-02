
<?php /*debug($data, 'stop');*/ ?>
<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-12">
				<div class="dash-panel theme">
					<div class="dash-panel-top">
						<ul class="spaced-list between">
							<li><h3>New Veggie</h3></li>
						</ul>
					</div>
					<div class="dash-panel-middle">
						<?php if ($data['products']): ?>
						<table class="render-datatable">
							<thead>
								<tr>
									<?php foreach ($data['products'][0] as $key => $value): ?>
										<?php if ($key == 'id'): ?>
											<th>Action</th>	
										<?php else: ?>
											<th><?php echo fix_title($key);?></th>	
										<?php endif ?>
									<?php endforeach ?>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['products'] as $key => $product): ?>
								<tr product_id="<?php echo $product['id'];?>">
									<?php foreach ($product as $index => $value): ?>
										<td width="<?php echo strlen(fix_title($value))*15;?>px"<?php if ($index == 'updated'): ?> data-sort="<?php echo strtotime($value);?>"<?php endif ?>>
											<?php if ($index == 'id'): ?>
												<a href="farm/edit/<?php echo $value;?>">Edit</a>
												<a href="farm/remove/<?php echo $value;?>" data-ajax="1">Remove</a>
											<?php elseif ($index == 'updated'): ?>
												<?php echo date('M. j, Y', strtotime($value));?>
											<?php else: ?>
												<?php echo $value;?>
											<?php endif ?>
										</td>
									<?php endforeach ?>
								</tr>
							<?php endforeach ?>
							</tbody>
						</table>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>