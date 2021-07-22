<?php header("Content-type: text/xml");?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
	<?php foreach($items as $row) { ?>
		<url>
			<loc><?php echo $row['loc'];?></loc>
			<lastmod><?php echo $row['lastmod'];?></lastmod>
			<changefreq><?php echo $row['changefreq'];?></changefreq>
			<priority>1.0</priority>
			<?php if (isset($row['images']) AND count($row['images'])): ?>
				<?php foreach ($row['images'] as $image): ?>
					<image:image>
						<image:loc><?php echo $image['url'];?></image:loc>
					</image:image>
				<?php endforeach ?>
			<?php endif ?>
		</url>
	<?php } ?>
</urlset>