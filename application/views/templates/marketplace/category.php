<?php if ($this->categories): ?>
<div id="category_container">
	<h3><b>Categories</b></h3>
	<div class="panel zero-gaps">
		<div id="veggy_categories">
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner active">
					<img src="assets/images/categories/all.png" />
					<p>All <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<?php foreach ($this->categories as $key => $category): ?>
				<a href="" class="veggy-category-item" data-category-group="<?php echo $category['id'];?>">
					<div class="veggy-category-item-inner">
						<img src="<?php echo $category['photo'];?>" alt="<?php echo $category['label'];?>" />
						<p><?php echo $category['label'];?> <i class="fa fa-chevron-down"></i></p>
					</div>
				</a>
			<?php endforeach ?>
		</div>
	</div>
</div>
<?php endif ?>