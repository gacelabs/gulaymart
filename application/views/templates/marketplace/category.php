<?php if ($this->categories): ?>
<div id="category_container">
	<h3><b>Categories</b></h3>
	<div class="panel zero-gaps">
		<div id="veggy_categories">
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("marketplace", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/all.png" />
					<p>All <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("leafy", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/leafy.png" />
					<p>Leafy <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("root", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/root.png" />
					<p>Root <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("cruciferous", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/cruciferous.png" />
					<p>Cruciferous <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("marrow", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/marrow.png" />
					<p>Marrow <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("stem", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/stem.png" />
					<p>Stem <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
			<a href="" class="veggy-category-item">
				<div class="veggy-category-item-inner <?php if (in_array("allium", $middle['body_class'])) {echo "active";} ?>">
					<img src="assets/images/categories/allium.png" />
					<p>Allium <i class="fa fa-chevron-down"></i></p>
				</div>
			</a>
		</div>
	</div>
</div>
<?php endif ?>