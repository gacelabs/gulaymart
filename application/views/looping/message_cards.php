
<?php if ($tab): ?>
	<?php if ($tab == 'notifications'): ?>
		<?php foreach ($messages as $tab => $message): ?>
			<div class="notif-item" data-msg-id="<?php echo $message['id'];?>">
				<div class="notif-item-top">
					<p class="zero-gaps"><i class="fa fa-leaf color-theme icon-left"></i><b><?php echo $message['type'];?></b> - <span class="color-grey"><?php echo date('F j, Y | ', $message['datestamp']) . date('g:i:s a', strtotime($message['added']));?></span> </p>
				</div>
				<div class="notif-item-middle">
					<?php if ($message['unread']): ?><strong><?php endif ?>
					<?php echo $message['content'];?>
					<?php if ($message['unread']): ?></strong><?php endif ?>
				</div>
				<div class="notif-item-footer">
					<ul class="inline-list">
						<?php if ($message['unread']): ?>
							<li><a href="api/update/messages/<?php echo $message['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $message['id'], 'unread' => GM_MESSAGE_READ, 'fn' => false], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Mark as read</a></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
						<?php else: ?>
							<li><a href="javascript:;" class="text-link normal-radius">Marked as read</a></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
						<?php endif ?>
						<li><a href="api/update/messages/<?php echo $message['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $message['id'], 'unread' => GM_MESSAGE_DELETE, 'fn' => 'deleteMessage'], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a></li>
					</ul>
				</div>
			</div>
		<?php endforeach ?>
	<?php elseif ($tab == 'feedbacks'):
		foreach ($messages as $key => $feedback): ?>
			<div class="notif-item" data-msg-id="<?php echo $feedback['id'];?>">
				<div class="notif-item-top">
					<p class="zero-gaps"><i class="fa fa-commenting-o icon-left"></i>
						<?php if ($feedback['is_buyer'] == 0): ?>
							<b><?php get_fullname($feedback['farm']);?></b>
							<?php if ($feedback['farm']['user_id'] == $current_profile['id']): ?>
							 - <span class="text-gray">You <i class="fa fa-farm"></i></span>
							 <img src="assets/images/icons/farms.png" class="mini-img-icon" align="left" style="width: 18px; margin-right: 10px;">	
							<?php endif ?>
						<?php else: ?>
							<b><?php get_fullname($feedback['profile']);?></b>
							<?php if (isset($feedback['is_buyer']) AND $feedback['is_buyer']): ?>
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
				<div class="notif-item-footer">
					<ul class="inline-list">
						<?php if ($feedback['is_buyer'] == 1): ?>
							<li>
								<a id="feedback-btn-id-<?php echo $feedback['id'];?>" class="text-link normal-radius" data-toggle="modal" data-target="#reply_modal" data-feedback='<?php echo json_encode($feedback, JSON_NUMERIC_CHECK);?>' data-reply='<?php echo json_encode($feedback['reply'], JSON_NUMERIC_CHECK);?>'>
									<?php if ($feedback['reply']): ?>View<?php else: ?>Reply<?php endif ?>
								</a>
							</li>
							&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
						<?php endif ?>
						<li>
							<a href="api/update/messages/<?php echo $feedback['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $feedback['id'], 'unread' => GM_MESSAGE_DELETE, 'fn' => 'deleteMessage'], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a>
						</li>
					</ul>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
<?php endif ?>