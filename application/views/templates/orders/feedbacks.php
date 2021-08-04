<div class="notif-list-container hide" id="msg_feedbacks">
	<?php if ($feedbacks): ?>
		<?php foreach ($feedbacks['first'] as $key => $feedback): ?>
			<div class="notif-item">
				<div class="notif-item-top">
					<p class="zero-gaps"><i class="fa fa-commenting-o icon-left"></i>
						<b><?php get_fullname($feedback['profile']);?></b>
						<?php if ($feedback['bought'] AND $feedback['profile']['user_id'] == $current_profile['id']): ?>
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
				<?php 
					$reply_data = false;
					if (isset($feedbacks['replies'])) {
						foreach ($feedbacks['replies'] as $replies) {
							if ($replies['under'] == $feedback['id']) {
								$reply_data = $replies;
								break;
							}
						}
					}
				?>
				<div class="notif-item-footer">
					<ul class="inline-list">
						<li>
							<button id="feedback-btn-id-<?php echo $feedback['id'];?>" class="btn btn-default normal-radius" data-toggle="modal" data-target="#reply_modal" data-feedback='<?php echo json_encode($feedback);?>' data-reply='<?php echo json_encode($reply_data);?>'>
								<?php if ($reply_data): ?>
									View
								<?php else: ?>
									Reply
								<?php endif ?>
							</button>
						</li>
					</ul>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>