<?php if (isset($data['category'])): ?>
	<div class="product-list-card" data-id="<?php echo $data['id'];?>" style="cursor: pointer;margin: 10px;padding: 0;">
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
		<div class="product-desc-body">
			<div class="product-title-container" style="height: auto;">
				<h1 class="zero-gaps"><?php echo ucwords($data['name']);?></h1>
				<br>
				<div>
					<div>
						<small class="text-gray" style="margin-bottom: 5px;">DESCRIPTION</small>
						<p class="text-ellipsis"><?php echo ucwords($data['description']);?></p>
					</div>
					<div>
						<small class="text-gray" style="margin-bottom: 5px;">PRICE</small>
						<p class="text-ellipsis"><?php echo ucwords($data['price']);?> / <?php echo $data['measurement'];?></p>
					</div>
					<?php if (isset($data['distance']) AND isset($data['duration'])): ?>
					<div>
						<small class="text-gray" style="margin-bottom: 5px;">ESTIMATED</small>
						<p class="text-ellipsis"><i class="fa fa-clock-o"></i> <?php echo ucwords($data['duration']);?></p>
					</div>
					<?php endif ?>
					<div>
						<small class="text-gray" style="margin-bottom: 5px;">STATUS</small>
						<p class="text-ellipsis"><?php echo get_activity_text($data['activity']);?></p>
					</div>
				</div>
				<p class="messages"></p>
			</div>
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
<?php endif ?>