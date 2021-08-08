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
				<?php $this->view('templates/orders/messages_panel', ['data' => $data]); ?>
			</div>
		</div>
	</div>
</div>