<?php /*debug($data, 'stop');*/ ?>
<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12" id="inventory_col">
			<div class="dashboard-panel theme">
				<div class="dashboard-panel-top">
					<ul class="spaced-list between">
						<li><h3 class="zero-gaps">Product Inventory</h3></li>
					</ul>
				</div>
				<div class="dashboard-panel-middle">
					<table class="table table-bordered table-striped render-datatable" id="inventory_table" style="width: -webkit-fill-available;">
						<?php if ($data['products']): ?>
							<thead>
								<tr>
									<?php if ($this->agent->is_mobile()): ?>
										<th class="for-responsive" width="10"></th>
									<?php endif ?>
									<?php foreach ($data['products'][0] as $key => $value): ?>
										<?php if ($key == 'id'): ?>
											<th<?php if ($this->agent->is_mobile()): ?> width="50"<?php else: ?> width="120"<?php endif ?>>Actions</th>
										<?php elseif ($key != 'version'): ?>
											<th><?php echo fix_title($key);?></th>
										<?php endif ?>
									<?php endforeach ?>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['products'] as $key => $product): ?>
								<tr product_id="<?php echo $product['id'];?>">
									<?php if ($this->agent->is_mobile()): ?>
										<td class="for-responsive"></td>
									<?php endif ?>
									<?php foreach ($product as $index => $value): ?>
										<?php if ($index != 'version'): ?>
											<td<?php if ($index == 'updated'): ?> data-sort="<?php echo strtotime($value);?>"<?php endif ?>>
												<?php if ($index == 'id'): ?>
													<?php if ($this->agent->is_mobile()): ?>&nbsp;&nbsp;<?php endif ?>
													<?php if (in_array($product['activity'], ['Draft','Published'])): ?>
														<a class="text-link normal-radius" href="farm/save-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>/">Edit</a> | 
														<?php if ($product['activity'] == 'Draft'): ?>
															<a class="text-link normal-radius" href="farm/remove-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>/deactivate/" data-ajax="1">Deactivate</a>
														<?php else: ?>
															<a class="text-link normal-radius" href="farm/remove-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>/" data-ajax="1">Unpublish</a>
														<?php endif ?>
													<?php else: ?>
														<a class="text-link normal-radius" href="farm/remove-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>/deactivate/" data-ajax="1">Deactivate</a>
													<?php endif ?>
												<?php elseif ($index == 'updated'): ?>
													<?php echo date('M. j, Y | h:i:s a', strtotime($value));?>
												<?php elseif ($index == 'name'): ?>
													<div class="inventory-name-block">
														<?php echo ucwords($value);?>
													</div>
												<?php else: ?>
													<?php echo $value;?>
												<?php endif ?>
											</td>
										<?php endif ?>
									<?php endforeach ?>
								</tr>
							<?php endforeach ?>
							</tbody>
						<?php else: ?>
							<thead>
								<tr>
									<?php foreach ($data['field_lists'] as $value): ?>
										<?php if ($value == 'id'): ?>
											<th>Actions</th>	
										<?php else: ?>
												<th><?php echo fix_title($value);?></th>	
										<?php endif ?>
									<?php endforeach ?>
								</tr>
							</thead>
						<?php endif ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>