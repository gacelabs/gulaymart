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
					<table class="table table-bordered table-striped render-datatable" id="inventory_table">
						<?php if ($data['products']): ?>
							<thead>
								<tr>
									<?php if ($this->agent->is_mobile()): ?>
										<th class="for-responsive"></th>
									<?php endif ?>
									<?php foreach ($data['products'][0] as $key => $value): ?>
										<?php if ($key == 'id'): ?>
											<th>Actions</th>
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
										<td<?php if ($index == 'updated'): ?> data-sort="<?php echo strtotime($value);?>"<?php endif ?>>
											<?php if ($index == 'id'): ?>
												<?php if (in_array($product['activity'], ['Draft','Published'])): ?>
													<a href="farm/save-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>">Edit</a> | 
												<?php endif ?>
												<a href="farm/remove-veggy/<?php echo $product['id'];?>/<?php nice_url($product['name']);?>" data-ajax="1">Remove</a>
											<?php elseif ($index == 'updated'): ?>
												<?php echo date('M. j, Y | h:i a', strtotime($value));?>
											<?php elseif ($index == 'name'): ?>
												<div class="inventory-name-block">
													<?php echo ucwords($value);?>
												</div>
											<?php elseif ($index != 'version'): ?>
												<?php echo $value;?>
											<?php endif ?>
										</td>
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