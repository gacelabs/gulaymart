<div class="modal fade" id="search_popup" tabindex="-1" role="dialog" aria-labelledby="search_popupLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="search_popupLabel">What veggies you're craving today?</h4>
			</div>
			<div class="modal-body">
				<?php $this->view('forms/search_form'); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>