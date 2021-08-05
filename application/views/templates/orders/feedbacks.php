<div class="notif-list-container hide" id="msg_feedbacks">
	<?php if ($feedbacks):
		$data_feedbacks = $is_replies = false;
		if (isset($feedbacks['first'])) {
			$data_feedbacks = $feedbacks['first'];
		} elseif (isset($feedbacks['replies'])) {
			$data_feedbacks = $feedbacks['replies'];
			$is_replies = true;
		}
		?>
		<?php foreach ($data_feedbacks as $key => $feedback): ?>
			<div class="notif-item">
				<div class="notif-item-top">
					<p class="zero-gaps"><i class="fa fa-commenting-o icon-left"></i>
						<?php if ($is_replies): ?>
							<b><?php get_fullname($feedback['farm']);?></b>
						<?php else: ?>
							<b><?php get_fullname($feedback['profile']);?></b>
							<?php if ($feedback['bought'] AND $feedback['to_id'] == $current_profile['id']): ?>
							 - <span class="text-gray">Verified Purchase <i class="fa fa-check-circle"></i></span> 	
							<?php endif ?>
						<?php endif ?>
					</p>
				</div>
				<div class="notif-item-middle">
					<div class="notif-related-product">
						<small class="elem-block">FEEDBACK ABOUT</small>
						<div class="order-item-grid">
							<div class="order-item-image" style="background-image: url('<?php identify_main_photo($feedback['product']);?>');"></div>
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
					if (isset($feedbacks['replies']) AND $is_replies == false) {
						foreach ($feedbacks['replies'] as $replies) {
							if ($replies['under'] == $feedback['id']) {
								$reply_data = $replies;
								break;
							}
						}
					} elseif ($is_replies == true) {
						$reply_data = true;
					}
				?>
				<div class="notif-item-footer">
						<ul class="inline-list">
							<?php if ($is_replies == false): ?>
							<li>
								<a id="feedback-btn-id-<?php echo $feedback['id'];?>" class="text-link normal-radius" data-toggle="modal" data-target="#reply_modal" data-feedback='<?php echo json_encode($feedback, JSON_NUMERIC_CHECK);?>' data-reply='<?php echo json_encode($reply_data, JSON_NUMERIC_CHECK);?>'>
									<?php if ($reply_data): ?>
										View
									<?php else: ?>
										Reply
									<?php endif ?>
								</a>
							</li>
							&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
							<?php endif ?>
							<li>
								<a href="api/update/messages/<?php echo $feedback['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $feedback['id'], 'unread' => 2, 'fn' => 'deleteMessage'], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a>
							</li>
						</ul>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;">
			<h1>Empty Feedbacks</h1>
			<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
		</div>
	<?php endif ?>
</div>