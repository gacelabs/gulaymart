<div class="media">
	<div class="media-left">
		<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
	</div>
	<div class="media-body">
		<ul class="spaced-list between">
			<li>
				<p class="media-heading">
					<b><?php get_fullname($profile, 'Guest');?></b>
					<?php if ($to_id == $profile['id']): ?>
						<small class="text-gray">(Verified Customer)</small>
					<?php endif ?>
				</p>
			</li>
			<li><small class="text-gray"><?php echo date('F j, Y | g:ia', strtotime($added));?></small></li>
		</ul>
		<p><?php echo $content;?></p>
		<?php 
			if ($under == 0) {
				// $this->view('looping/comment_item', ['placeholder'=>'Reply to this comment ...', 'under'=>$id, 'page'=>$product]);
			}
		?>
	</div>
	<?php 
		if ($under > 0) {
			// $this->view('looping/comment_item', ['placeholder'=>'Reply to this comment ...', 'under'=>$id, 'page'=>$product]);
		}
	?>
</div>