<div class="row">
<div class="col s12">
<h4 class="pad-left-15 capitalize">Edit team</h4>
</div>
</div>
<div class="row">
<form class='col s12' method='post' action='<?php echo site_url("site/editteamsubmit");?>' enctype= 'multipart/form-data'>
<input type="hidden" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$before->id);?>" style="display:none;">
<div class=" row" style="display:none;">
<div class=" input-field col s12 m6">
<?php echo form_dropdown("type",$type,set_value('type',$before->type));?>
<label for="Type">Type</label>
</div>
</div>
<div class="row">
<div class="input-field col s6">
<label for="Name">Name</label>
<input type="text" id="Name" name="name" value='<?php echo set_value('name',$before->name);?>'>
</div>
</div>
<div class="row">
<div class="input-field col s6">
<label for="Name">Name in Hindi</label>
<input type="text" id="hname" name="hname" value='<?php echo set_value('hname',$before->hname);?>'>
</div>
</div>

<div class="row">
			<div class="file-field input-field col m6 s12">
				<span class="img-center big">
								                    	<?php if($before->image == "") { } else {
									                    ?><img src="<?php echo base_url('uploads')."/".$before->image; ?>">
															<?php } ?>
															</span>
				<div class="btn blue darken-4">
					<span>Image</span>
					<input name="image" type="file" multiple>
				</div>
				<div class="file-path-wrapper">
					<input class="file-path validate" type="text" placeholder="Upload one or more files" value="<?php echo set_value('image',$before->image);?>">
				</div>
			</div>
    
              <span style=" display: block;
           ">800px X 800px </span>
		</div>
		<div class="row">
			<div class="file-field input-field col m6 s12">
				<span class="img-center big">
								                    	<?php if($before->appimage == "") { } else {
									                    ?><img src="<?php echo base_url('uploads')."/".$before->appimage; ?>">
															<?php } ?>
															</span>
				<div class="btn blue darken-4">
					<span>App Image</span>
					<input name="appimage" type="file" >
				</div>
				<div class="file-path-wrapper">
					<input class="file-path validate" type="text" placeholder="Upload one or more files" value="<?php echo set_value('image',$before->appimage);?>">
				</div>
			</div>
    
              <span style=" display: block;
           ">400 x 400px </span>
		</div>

		<div class="row">
			<div class="input-field col m6 s12">
				<label>Zone</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col m6 s12">
				<?php echo form_dropdown( 'zone',$zone,set_value( 'zone',$before->zone),'class="browser-default" '); ?>
					
			</div>
		</div>
<div class="row">
<div class="col s12 m6">
<label>Content</label>
<textarea name="content" placeholder="Enter text ..."><?php echo set_value( 'content',$before->content);?></textarea>
</div>
</div>
<div class="row">
<div class="input-field col s12">
<textarea name="hcontent" class="materialize-textarea" length="400"><?php echo set_value( 'hcontent',$before->hcontent);?></textarea>
<label>Content in Hindi</label>
</div>
</div>
<div class="row">
<div class="col s6">
<button type="submit" class="btn btn-primary waves-effect waves-light  blue darken-4">Save</button>
<a href='<?php echo site_url("site/viewteam"); ?>' class='btn btn-secondary waves-effect waves-light red'>Cancel</a>
</div>
</div>
</form>
</div>
