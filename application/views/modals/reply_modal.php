<div class="modal fade" id="reply_modal" tabindex="-1" role="dialog" aria-labelledby="reply_modalLabel">
	<?php // debug($current_profile, 'stop') ?>
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="reply_modalLabel">Replying to:</h4>
			</div>
			<div class="modal-body">
				<div class="media">
					<div class="media-left">
						<img id="buyer_photo" class="media-object" src="assets/images/noavatar.png" style="width: 35px;" data-holder-rendered="true">
					</div>
					<div class="media-body">
						<p class="media-heading"><b id="buyer_fullname">&nbsp;</b> <span class="color-grey"><small id="buyer_date">&nbsp;</small></span></p>
						<p id="buyer_comments">&nbsp;</p>

						<div class="media hide" id="seller_content">
							<div class="media-left">
								<?php if ($current_profile): ?>
									<img class="media-object" src="assets/images/noavatar.png" style="width: 35px;" data-holder-rendered="true">
								<?php else: ?>
									<img class="media-object" src="assets/images/noavatar.png" style="width: 35px;" data-holder-rendered="true">
								<?php endif ?>
							</div>
							<div class="media-body">
								<p class="media-heading"><b id="seller_farm_name"></b>&nbsp;<i id="is_seller">&nbsp;</i> <span class="color-grey"><small id="seller_buyer_date">&nbsp;</small></span></p>
								<p id="seller_comments">&nbsp;</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer text-left" id="reply_box">
				<form action="orders/comment" method="post" class="form-validate" data-ajax="1" data-disable="enter">
					<input type="hidden" id="under" name="under" value="1">
					<input type="hidden" id="to_id" name="to_id" value="">
					<input type="hidden" name="from_id" value="<?php isset_echo($current_profile, 'id', 0);?>">
					<input type="hidden" id="page_id" name="page_id" value="">
					<input type="hidden" id="entity_id" name="entity_id" value="">
					<input type="hidden" name="tab" value="Feedbacks">
					<input type="hidden" name="type" value="Comments">
					<div class="form-group zero-gaps">
						<textarea class="form-control" required="required" id="seller_reply" name="content" rows="2" placeholder="Reply to this comment ..."></textarea>
					</div>
					<div class="form-group zero-gaps">
						<button type="submit" class="btn btn-info normal-radius" loading-text><i class="fa fa-send"></i> Reply</button></li>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>