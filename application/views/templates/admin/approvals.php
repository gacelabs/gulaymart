<div class="body-wrapper">
	<div class="container">
		<div id="products_container">
			<div class="approval-send-all" style="display: none;">
				<ul class="spaced-list">
					<li>
						<button data-url="admin/approvals/1" class="send-all-approval btn btn-sm btn-success">Allow All</button>
					</li>
					<li>
						<button data-url="admin/approvals/2" class="send-all-approval btn btn-sm btn-danger">Reject All</button>
					</li>
				</ul>
			</div>
			<div class="clearfix"></div>
			<br>
			<div id="product_list_container">
				<?php
					if ($data['result']) {
						foreach ($data['result'] as $key => $object) {
							$this->view('looping/item_cards', ['data'=>$object]);
						}
					}
				?>
				<h3 id="no-items" class="text-center<?php if ($data['result']): ?> hide<?php endif ?>">No Item(s) Found</h3>
			</div>
			<div class="clearfix"></div>
			<br>
			<div class="approval-send-all" style="display: none;">
				<ul class="spaced-list">
					<li>
						<button data-url="admin/approvals/1" class="send-all-approval btn btn-sm btn-success">Allow All</button>
					</li>
					<li>
						<button data-url="admin/approvals/2" class="send-all-approval btn btn-sm btn-danger">Reject All</button>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>