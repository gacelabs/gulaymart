<?php /*debug($data, 'stop');*/ ?>
<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12">
			<div class="dashboard-panel theme">
				<div class="dashboard-panel-top">
					<ul class="spaced-list between">
						<li><h3 class="zero-gaps">New Veggie</h3></li>
					</ul>
				</div>
				<div class="dashboard-panel-middle">
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