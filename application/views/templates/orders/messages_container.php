<div class="messages-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="messages-nav-grid">
				<div class="messages-navbar-pill hideshow-btn active" hideshow-target="#msg_notifications">Notifications
					<?php 
					$msg_count = $this->gm_db->count('messages', ['unread' => [0,1], 'tab' => 'Notifications']);
					if ($msg_count): ?>
						<kbd id='msg_notifications-count'><?php echo $msg_count;?></kbd>
					<?php endif ?>
				</div>
				<div class="messages-navbar-pill hideshow-btn" hideshow-target="#msg_inquiries">Inquiries
					<?php 
					$msg_count = $this->gm_db->count('messages', ['unread' => [0,1], 'tab' => 'Inquiries']);
					if ($msg_count): ?>
						<kbd id='msg_inquiries-count'><?php echo $msg_count;?></kbd>
					<?php endif ?>
				</div>
				<div class="messages-navbar-pill hideshow-btn" hideshow-target="#msg_feedbacks">Feedbacks
					<?php 
					$msg_count = $this->gm_db->count('messages', ['unread' => [0,1], 'tab' => 'Feedbacks']);
					if ($msg_count): ?>
						<kbd id='msg_feedbacks-count'><?php echo $msg_count;?></kbd>
					<?php endif ?>
				</div>
			</div>

			<div id="link_panel_top">
				<div class="hideshow-container">
					<?php if ($data['messages']): ?>
						<div id="msg_notifications">
							<?php if (isset($data['messages']['Notifications'])): ?>
								<?php foreach ($data['messages']['Notifications'] as $tab => $message): ?>
									<div class="notif-item">
										<div class="notif-item-top">
											<p class="zero-gaps"><i class="fa fa-leaf color-theme icon-left"></i><b><?php echo $message['type'];?></b> - <span class="color-grey"><?php echo date('F j, Y | ', $message['datestamp']) . date('g:i a', strtotime($message['added']));?></span> </p>
										</div>
										<div class="notif-item-middle">
											<?php if ($message['unread']): ?><strong><?php endif ?>
											<?php echo $message['content'];?>
											<?php if ($message['unread']): ?></strong><?php endif ?>
										</div>
										<div class="notif-item-footer">
											<ul class="inline-list">
												<li><a href="api/update/messages/<?php echo $message['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $message['id'], 'unread' => 0, 'fn' => 'readMessage']);?>' class="text-link normal-radius">Mark as read</a></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
												<li><a href="api/update/messages/<?php echo $message['id'];?>" data-ajax data-call-jsonp="0" data-json='<?php echo json_encode(['id' => $message['id'], 'unread' => 2, 'fn' => 'deleteMessage']);?>' class="text-link normal-radius">Delete</a></li>
											</ul>
										</div>
									</div>
								<?php endforeach ?>
							<?php endif ?>
						</div>

						<?php
							if (isset($data['messages']['Inquiries'])) {
								$this->view('templates/orders/inquiries', ['inquiries' => $data['messages']['Inquiries']]);
							}
							if (isset($data['messages']['Feedbacks'])) {
								$this->view('templates/orders/feedbacks', ['feedbacks' => $data['messages']['Feedbacks']]);
							}
						?>
					<?php endif ?>

				</div>
					
				<?php if ($this->gm_db->count('messages', ['unread' => [0,1]]) == 0): ?>
					<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;">
						<h1>No Messages</h1>
						<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>