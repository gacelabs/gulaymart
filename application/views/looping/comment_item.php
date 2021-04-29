<div style="padding: 0 10px;">
	<div style="margin-top:0;" class="media" js-element="comment-panel-<?php echo $under;?>">
		<div class="media-left">
			<?php if ($current_profile): ?>
				<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
			<?php else: ?>
				<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
			<?php endif ?>
		</div>
		<div class="media-body" style="position: relative;">
			<ul class="spaced-list between">
				<li><p class="media-heading"><b><?php get_fullname($current_profile, 'Guest');?></b> <small class="text-gray">(Verified Customer)</small></p></li>
				<!-- <li><small class="text-gray"><?php // echo date('F j, Y | g:ia');?></small></li> -->
			</ul>
			<form action="orders/comment" method="post" class="form-validate" data-ajax="1" data-disable="enter">
				<input type="hidden" name="under" value="<?php echo $under;?>">
				<input type="hidden" name="user_id" value="<?php isset_echo($current_profile, 'id', 0);?>">
				<input type="hidden" name="page_id" value="<?php echo $page['id'];?>">
				<input type="hidden" name="entity_id" value="<?php echo $page['entity_id'];?>">
				<input type="hidden" name="tab" value="Feedbacks">
				<input type="hidden" name="type" value="Comments">
				<div class="input-group">
					<input type="text" class="form-control" required="required" style="width:100%;" placeholder="<?php echo $placeholder;?>" name="content">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-md btn-contrast" data-loading-text>
							<small>POST</small>
						</button>
					</span>
				</div>
			</form>
		</div>
	</div>
</div>