
<div class="notif-item" data-msg-id="<?php echo $feedbacks['id'];?>">
	<div class="notif-item-top">
		<p class="zero-gaps"><i class="fa fa-commenting-o icon-left"></i>
			<?php if ($feedbacks['is_buyer'] == 0): ?>
				<b><?php get_fullname($feedbacks['farm']);?></b>
				<?php if ($feedbacks['farm']['user_id'] == $current_profile['id']): ?>
				 - <span class="text-gray">You <i class="fa fa-farm"></i></span>
				 <img src="assets/images/icons/farms.png" class="mini-img-icon" align="left" style="width: 18px; margin-right: 10px;">	
				<?php endif ?>
			<?php else: ?>
				<b><?php get_fullname($feedbacks['profile']);?></b>
				<?php if (isset($feedbacks['is_buyer']) AND $feedbacks['is_buyer']): ?>
				 - <span class="text-gray">Verified Purchase <i class="fa fa-check-circle"></i></span> 	
				<?php endif ?>
			<?php endif ?>
		</p>
	</div>
	<div class="notif-item-middle">
		<div class="notif-related-product">
			<small class="elem-block">FEEDBACK ABOUT</small>
			<div class="order-item-grid">
				<div class="order-item-image" style="background-image: url('<?php identify_main_photo($feedbacks['product']);?>');"></div>
				<div class="order-info-container">
					<div class="order-item-title">
						<p class="zero-gaps"><a href="<?php product_url($feedbacks['product'], true);?>" class="text-link"><?php echo $feedbacks['product']['name'];?></a></p>
						<p class="zero-gaps">&#x20b1; <b><?php echo format_number($feedbacks['location']['price']);?></b> / <?php echo $feedbacks['location']['measurement'];?></p>
					</div>
				</div>
			</div>
		</div>
		<p>
			<small class="elem-block text-gray"><?php echo date('F j, Y | g:ia', strtotime($feedbacks['added']));?></small>
			<?php echo $feedbacks['content'];?>
		</p>
	</div>
	<div class="notif-item-footer">
		<ul class="inline-list">
			<?php if ($feedbacks['is_buyer'] == 1): ?>
				<li>
					<a id="feedback-btn-id-<?php echo $feedbacks['id'];?>" class="text-link normal-radius" data-toggle="modal" data-target="#reply_modal" data-feedback='<?php echo json_encode($feedbacks, JSON_NUMERIC_CHECK);?>' data-reply='<?php echo json_encode($feedbacks['reply'], JSON_NUMERIC_CHECK);?>'>
						<?php if ($feedbacks['reply']): ?>View<?php else: ?>Reply<?php endif ?>
					</a>
				</li>
				&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<?php endif ?>
			<li>
				<a href="api/update/messages/<?php echo $feedbacks['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $feedbacks['id'], 'unread' => GM_MESSAGE_DELETE, 'fn' => false], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a>
			</li>
		</ul>
	</div>
</div>