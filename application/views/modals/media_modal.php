<div class="modal fade" id="media_modal" tabindex="-1" role="dialog" aria-labelledby="media_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label>Media</label>
				<br>
				<small class="color-grey"><i class="fa fa-exclamation-circle"></i> You can upload multiple images at once. Then/or select the image of your choice.</small>
			</div>
			<form action="api/media_uploader" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="galleries">
				<div class="modal-body">
					<ul class="inline-list preview_images_list"></ul>
					<div class="input-group">
						<input type="file" class="form-control input_upload_images" name="galleries[]" required="required" multiple>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Select<i class="fa fa-picture-o icon-right"></i></button>
						</span>
					</div>
					<ul class="inline-list preview_images_selected">
						<?php foreach ($this->galleries as $key => $gallery): ?>
							<li data-toggle="tooltip" data-placement="top" title="Select Image"><div class="preview-image-item" style="background-image: url('<?php echo $gallery['url_path'];?>')"></div><input type="radio" name="selected" value="<?php echo $gallery['url_path'];?>" required data-url-path="<?php echo $gallery['url_path'];?>" /></li>
						<?php endforeach ?>
					</ul>
				</div>
				<div class="modal-footer">
					<button value="upload" type="submit" class="btn btn-default normal-radius hide">Upload<i class="fa fa-upload icon-right"></i></button>
					<button value="select" type="submit" class="btn btn-default normal-radius hide">Select<i class="fa fa-check-circle icon-right"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>