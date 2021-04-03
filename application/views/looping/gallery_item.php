<?php if (isset($data)): ?>
<div class="container">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide" id="galleries_item_container">
		<h3><b><?php echo isset($title) ? $title : 'Support local farmers';?></b></h3>
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<?php foreach ($data as $key => $row): ?>
					<li data-target="#carousel-example-generic" data-slide-to="<?php echo $key;?>"<?php str_has_value_echo(0, $key, ' class="active"');?>></li>
				<?php endforeach ?>
			</ol>

			<div class="carousel-inner" role="listbox" style="border-radius:0;">
				<?php foreach ($data as $key => $row): ?>
					<div class="item<?php str_has_value_echo(0, $key, ' active');?>">
						<img src="<?php echo base_url($row['url_path']);?>" alt="<?php echo $row['name'];?>.">
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
<?php endif ?>