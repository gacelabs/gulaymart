<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="inline-list dashboard-panel-top">
					<li><a href="javascript:;" class="hideshow-btn active" hideshow-target="msg_notifications"><h4 class="zero-gaps">Notifications</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="msg_inquiries"><h4 class="zero-gaps">Inquiries</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="msg_feedbacks"><h4 class="zero-gaps">Feedbacks</h4></a></li>
				</ul>
				<div class="dashboard-panel-middle hideshow-container">
					<div id="msg_notifications">
						<?php if ($data['messages']): ?>
							<?php foreach ($data['messages'] as $key => $message): ?>
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
						<?php else: ?>
							<div class="notif-item">
								<div class="notif-item-middle">
									<p>Empty</p>
								</div>
							</div>
						<?php endif ?>
					</div>

					<?php
						$this->view('templates/transactions/inquiries');
						$this->view('templates/transactions/feedbacks');
					?>
				</div>
			</div>
		</div>
	</div>
</div>