<div class="messages-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="messages-nav-grid">
				<div class="messages-navbar-pill hideshow-btn active" hideshow-target="#msg_notifications">Notifications</div>
				<div class="messages-navbar-pill hideshow-btn" hideshow-target="#msg_inquiries">Inquiries</div>
				<div class="messages-navbar-pill hideshow-btn" hideshow-target="#msg_feedbacks">Feedbacks</div>
			</div>

			<div id="link_panel_top">
				<div class="hideshow-container">
					<?php if ($data['messages']): ?>
						<div id="msg_notifications">
							<?php if (isset($data['messages']['Notifications'])): ?>
								<?php foreach ($data['messages']['Notifications'] as $tab => $message): ?>
									<div class="notif-item">
										<div class="notif-item-top">
											<p class="zero-gaps"><i class="fa fa-leaf color-theme icon-left"></i><b><?php echo $message['type'];?></b> - <span class="color-grey"><?php echo date('F j, Y', $message['datestamp']);?></span> </p>
										</div>
										<div class="notif-item-middle">
											<p>
												<?php if ($message['unread']): ?><strong><?php endif ?>
												<?php echo $message['content'];?>
												<?php if ($message['unread']): ?></strong><?php endif ?>
											</p>
										</div>
										<div class="notif-item-footer<?php if ($message['unread'] == 0): ?> hide<?php endif ?>">
											<ul class="inline-list">
												<li><button class="btn btn-default normal-radius">Delete</button></li>
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
					<?php else: ?>
						<div class="notif-item">
							<div class="notif-item-middle">
								<p>Empty</p>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>