<div class="messages-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="messages-nav-grid">
				<div data-menu="messages" data-nav="notifications" class="messages-navbar-pill hideshow-btn active" hideshow-target="#msg_notifications">Notifications
					<?php 
					$msg_count = $this->gm_db->count('messages', ['unread' => 1, 'tab' => 'Notifications', 'to_id' => $current_profile['profile']['user_id']]);
					?>
					<kbd id='msg_notifications-count'<?php if (!$msg_count): ?> class="hide"<?php endif ?>><?php echo $msg_count;?></kbd>
				</div>
				<div data-menu="messages" data-nav="feedbacks" class="messages-navbar-pill hideshow-btn" hideshow-target="#msg_feedbacks">Feedbacks
					<?php 
					$msg_count = $this->gm_db->count('messages', ['unread' => 1, 'tab' => 'Feedbacks', 'to_id' => $current_profile['profile']['user_id']]);
					?>
					<kbd id='msg_feedbacks-count'<?php if (!$msg_count): ?> class="hide"<?php endif ?>><?php echo $msg_count;?></kbd>
				</div>
			</div>

			<div id="link_panel_top">
				<div class="hideshow-container">
					<div id="msg_notifications" element-name="notifications">
						<?php
						$notifications = false;
						if ($data['messages'] AND isset($data['messages']['Notifications'])) {
							$notifications = $data['messages']['Notifications'];
							foreach ($notifications as $key => $message) {
								$this->view('templates/orders/notifications', ['notifications' => $message]);
							}
						}
						?>
						<div class="no-records-ui<?php if ($notifications): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
							<h1>Empty Notifications</h1>
							<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
						</div>
					</div>
					<div class="notif-list-container hide" id="msg_feedbacks" element-name="notifications">
						<?php 
						$feedbacks = false;
						if ($data['messages'] AND isset($data['messages']['Feedbacks'])) {
							$feedbacks = $data['messages']['Feedbacks'];
							foreach ($feedbacks as $key => $feedback) {
								$this->view('templates/orders/feedbacks', ['feedbacks' => $feedback]);
							}
						}
						?>
						<div class="no-records-ui<?php if ($feedbacks): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
							<h1>Empty Feedbacks</h1>
							<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>