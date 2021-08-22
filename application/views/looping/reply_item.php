
<div class="media">
	<div class="media-left">
		<?php if (isset($is_buyer) AND $is_buyer == 0): ?>
			<img class="media-object" src="<?php echo $farm['profile_pic'];?>" data-holder-rendered="true">
		<?php else: ?>
			<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
		<?php endif ?>
	</div>
	<div class="media-body">
		<ul class="spaced-list between">
			<li>
				<p class="media-heading">
					<b><?php get_fullname($farm, 'Guest');?></b>
					<?php if (isset($is_buyer) AND $is_buyer == 1): ?>
						<small class="text-gray">(Verified Customer)</small>
					<?php endif ?>
				</p>
			</li>
			<li><small class="text-gray"><?php time_elapsed_string($added, true);?></small></li>
		</ul>
		<p><?php echo $content;?></p>
	</div>
	<?php if ($totalcnt == $key): ?>
		<?php // $this->view('looping/comment_item', ['placeholder'=>'Reply to this comment ...', 'under'=>$id, 'page'=>$product]); ?>
	<?php endif ?>
</div>