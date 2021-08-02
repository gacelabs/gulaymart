<div class="notif-list-container hide" id="msg_feedbacks">
	<?php if ($feedbacks): ?>
		<?php foreach ($feedbacks as $key => $feedback): ?>
		<div class="notif-item">
			<div class="notif-item-top">
				<p class="zero-gaps"><i class="fa fa-commenting-o icon-left"></i>
					<b><?php get_fullname($feedback['profile']);?></b>
					<?php if ($feedback['bought']): ?>
					 - <span class="text-gray">Verified Purchase <i class="fa fa-check-circle"></i></span> 	
					<?php endif ?>
				</p>
			</div>
			<div class="notif-item-middle">
				<div class="notif-related-product">
					<small class="elem-block">FEEDBACK ABOUT</small>
					<div class="order-item-grid">
						<div class="order-item-image" style="background-image: url('<?php identify_main_photo($feedback);?>');"></div>
						<div class="order-info-container">
							<div class="order-item-title">
								<p class="zero-gaps"><a href="<?php product_url($feedback['product'], true);?>" class="text-link"><?php echo $feedback['product']['name'];?></a></p>
								<p class="zero-gaps">&#x20b1; <b><?php echo format_number($feedback['location']['price']);?></b> / <?php echo $feedback['location']['measurement'];?></p>
							</div>
						</div>
					</div>
				</div>
				<p>
					<small class="elem-block text-gray"><?php echo date('F j, Y | g:ia', strtotime($feedback['added']));?></small>
					<?php echo $feedback['content'];?>
				</p>
			</div>
			<div class="notif-item-footer hide">
				<ul class="inline-list">
					<li><button class="btn btn-default normal-radius" data-toggle="modal" data-target="#reply_modal" data-json='<?php echo json_encode($feedback);?>'>Reply</button></li>
				</ul>
			</div>
		</div>
		<?php endforeach ?>
	<?php endif ?>
</div>