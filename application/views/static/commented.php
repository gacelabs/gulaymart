<div class="media">
	<div class="media-left">
		<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
	</div>
	<div class="media-body">
		<ul class="spaced-list between">
			<li><p class="media-heading"><?php get_fullname($profile, 'Guest');?></p></li>
			<li><small class="text-gray"><?php echo date('F j, Y | g:ia', $added);?></small></li>
		</ul>
		<p><?php echo $content;?></p>
		<?php 
			if ($under == 0) {
				$this->view('looping/comment_item', ['placeholder'=>'Reply a feedback ...', 'under'=>$id, 'page'=>$product]);
			}
		?>
	</div>
	<?php 
		if ($under > 0) {
			$this->view('looping/comment_item', ['placeholder'=>'Reply a feedback ...', 'under'=>$id, 'page'=>$product]);
		}
	?>
</div>