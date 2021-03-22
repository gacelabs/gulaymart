
<?php if ($this->categories): ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="category_container">
	<h3>Categories</h3>
	<div class="panel zero-gaps" style="border-radius:12px;">
		<div id="veggy_categories">
			<div class="veggy-category-item">
				<a href="" class="veggy-category-item-inner active" data-category-group="all">
					<img src="assets/images/all.png" />
					<ul class="spaced-list between">
						<li><p>All</p></li>
						<li><i class="fa fa-chevron-down icon-right"></i></li>
					</ul>
				</a>
			</div>
			<?php foreach ($this->categories as $key => $category): ?>
				<a href="" class="veggy-category-item">
					<div class="veggy-category-item-inner" data-category-group="<?php echo $category['id'];?>">
						<img src="<?php echo $category['photo'];?>" />
						<ul class="spaced-list between">
							<li><p><?php echo $category['label'];?></p></li>
							<li><i class="fa fa-chevron-down icon-right"></i></li>
						</ul>
					</div>
				</a>
			<?php endforeach ?>
		</div>
	</div>
</div>
<?php endif ?>