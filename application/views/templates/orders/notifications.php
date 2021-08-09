
<div id="msg_notifications" element-name="notifications">
	<?php if ($notifications): ?>
		<?php foreach ($notifications as $tab => $message): ?>
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
						<li><a href="api/update/messages/<?php echo $message['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $message['id'], 'unread' => GM_MESSAGE_DELETE, 'fn' => false], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a></li>
					</ul>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
	<div class="no-records-ui<?php if ($notifications): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
		<h1>Empty Notifications</h1>
		<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
	</div>
</div>