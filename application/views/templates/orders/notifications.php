
<div class="notif-item" data-msg-id="<?php echo $notifications['id'];?>">
	<div class="notif-item-top">
		<p class="zero-gaps"><i class="fa fa-leaf color-theme icon-left"></i><b><?php echo $notifications['type'];?></b> - <span class="color-grey"><?php echo date('F j, Y | ', $notifications['datestamp']) . date('g:i:s a', strtotime($notifications['added']));?></span> </p>
	</div>
	<div class="notif-item-middle">
		<?php if ($notifications['unread'] == GM_MESSAGE_UNREAD): ?><strong><?php endif ?>
		<?php echo $notifications['content'];?>
		<?php if ($notifications['unread'] == GM_MESSAGE_UNREAD): ?></strong><?php endif ?>
	</div>
	<div class="notif-item-footer">
		<ul class="inline-list">
			<?php if ($notifications['unread']): ?>
				<li><a href="api/update/messages/<?php echo $notifications['id'];?>" data-toread="<?php echo $notifications['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $notifications['id'], 'unread' => GM_MESSAGE_READ, 'fn' => false], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Mark as read</a></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
			<?php else: ?>
				<li><a href="javascript:;" class="text-link normal-radius">Marked as read</a></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<?php endif ?>
			<li><a href="api/update/messages/<?php echo $notifications['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $notifications['id'], 'unread' => GM_MESSAGE_DELETE, 'fn' => false], JSON_NUMERIC_CHECK);?>' class="text-link normal-radius">Delete</a></li>
		</ul>
	</div>
</div>