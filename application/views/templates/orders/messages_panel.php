
<div class="hideshow-container">
	<?php if ($data['messages']): ?>
		<?php
			$notifications_data = false;
			if (isset($data['messages']['Notifications'])) {
				$notifications_data = $data['messages']['Notifications'];
			}
			$this->view('templates/orders/notifications', ['notifications' => $notifications_data]);
		?>
		<?php
			$feedbacks_data = false;
			if (isset($data['messages']['Feedbacks'])) {
				$feedbacks_data = $data['messages']['Feedbacks'];
			}
			$this->view('templates/orders/feedbacks', ['feedbacks' => $feedbacks_data]);
		?>
	<?php else: ?>
		<div id="msg_notifications" element-name="notifications">
			<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<h1>Empty Notifications</h1>
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
			</div>
		</div>
		<div class="notif-list-container hide" id="msg_feedbacks" element-name="notifications">
			<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<h1>Empty Feedbacks</h1>
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
			</div>
		</div>
	<?php endif ?>
</div>
