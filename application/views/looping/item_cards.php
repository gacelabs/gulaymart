<?php if (isset($data['category'])): ?>
	<div class="product-list-card" data-id="<?php echo $data['id'];?>" style="cursor: pointer;">
		<?php $no_main = true;?>
		<div class="product-list-photo" style="background-image: url(<?php identify_main_photo($data, false, $no_main);?>);">
			<div class="img-thumb-container">
				<ul class="inline-list" id="img_thumb_list">
					<?php if (isset($data['photos']['other']) AND $data['photos']['other']): ?>
						<?php foreach ($data['photos']['other'] as $key => $photo): ?>
							<?php if ($no_main AND $key == 0) continue;?>
							<li><div class="img-thumb-item" style="background-image: url('<?php echo $photo['url_path'];?>');"></div></li>
						<?php endforeach ?>
					<?php endif ?>
				</ul>
			</div>
		</div>
		<div class="product-desc-body" style="height: 250px;">
			<div class="product-title-container" style="height: auto;">
				<h1 class="zero-gaps"><?php echo ucwords($data['name']);?></h1>
				<br>
				<table>
					<tr>
						<th style="vertical-align: top;">Description:&nbsp;</th>
						<td><?php echo ucwords($data['description']);?></td>
					</tr>
					<tr>
						<th style="vertical-align: top;">Price:&nbsp;</th>
						<td>&#x20b1; <?php echo format_number($data['price']);?> / <?php echo $data['measurement'];?></td>
					</tr>
					<?php if (isset($data['distance']) AND isset($data['duration'])): ?>
						<tr>
							<th style="vertical-align: top;">Estimated:&nbsp;</th>
							<td><i class="fa fa-clock-o"></i> <?php echo ucwords($data['duration']);?></td>
						</tr>
					<?php endif ?>
					<tr>
						<th style="vertical-align: top;">Activity:&nbsp;</th>
						<td><?php echo $data['activity'] == 0 ? 'Drafted' : ($data['activity'] == 2 ? 'Rejected' : ($data['activity'] == 3 ? 'Deleted' : 'Drafted'));?></td>
					</tr>
				</table>
				<p class="messages"></p>
			</div>
			<div class="product-list-footer" style="position: absolute; margin: 5px auto; bottom: 0;">
				<ul class="spaced-list between">
					<li>
						<button data-url="admin/approvals/1" class="send-approval btn btn-sm btn-success">Allow</button>
					</li>
					<?php if ($data['activity'] == 2): ?>
						<li>
							<button data-url="admin/approvals/0" class="send-approval btn btn-sm btn-warning">Add to Draft</button>
						</li>
					<?php else: ?>
						<li>
							<button data-url="admin/approvals/2" class="send-approval btn btn-sm btn-danger">Reject</button>
						</li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
<?php endif ?>