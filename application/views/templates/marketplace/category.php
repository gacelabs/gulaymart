
<?php if ($current_profile['categories']): ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="category_container">
	<h3>Categories</h3>
	<div class="panel zero-gaps" style="border-radius:12px;">
		<div id="veggy_categories">
			<div class="veggy-category-item">
				<a href="javascript:;" class="veggy-category-item-inner active" data-category-group="all">
					<img src="assets/images/all.png" />
					<ul class="spaced-list between">
						<li><p>All</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<?php foreach ($current_profile['categories'] as $key => $category): ?>
				<div class="veggy-category-item">
					<a href="javascript:;" class="veggy-category-item-inner" data-category-group="category-<?php echo $category['label'];?>">
						<img src="<?php echo $category['photo'];?>" />
						<ul class="spaced-list between">
							<li><p><?php echo $category['label'];?></p></li>
							<li><i class="fa fa-chevron-down icon-right"></i></li>
						</ul>
					</a>
				</div>
			<?php endforeach ?>
			<!-- <div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner">
					<img src="assets/images/root.png" />
					<ul class="spaced-list between">
						<li><p>Root</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner">
					<img src="assets/images/cruciferous.png" />
					<ul class="spaced-list between">
						<li><p>Cruciferous</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner">
					<img src="assets/images/marrow.png" />
					<ul class="spaced-list between">
						<li><p>Marrow</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner">
					<img src="assets/images/plant-stem.png" />
					<ul class="spaced-list between">
						<li><p>Stem</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner">
					<img src="assets/images/allium.png" />
					<ul class="spaced-list between">
						<li><p>Allium</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div> -->
		</div>
	</div>
</div>
<?php endif ?>