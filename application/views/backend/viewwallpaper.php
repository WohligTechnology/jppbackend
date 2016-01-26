<div class="row">
<div class="col s12">
<div class="row">
<div class="col s12 drawchintantable">
<?php $this->chintantable->createsearch(" List of wallpaper");?>
<table class="highlight responsive-table">
<thead>
<tr>
<th data-field="id">ID</th>
<th data-field="wallpapercategory">Wallpaper Category</th>
<th data-field="name">Name</th>
<th data-field="image1">Image1</th>
<th data-field="image2">Image2</th>
<th data-field="image3">Image3</th>
<th data-field="image4">Image4</th>
<th data-field="image5">Image5</th>
<th data-field="image6">Image6</th>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
<?php $this->chintantable->createpagination();?>
<div class="createbuttonplacement"><a class="btn-floating btn-large waves-effect waves-light blue darken-4 tooltipped" href="<?php echo site_url("site/createwallpaper"); ?>"data-position="top" data-delay="50" data-tooltip="Create"><i class="material-icons">add</i></a></div>
</div>
</div>
<script>
function drawtable(resultrow) {
return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.wallpapercategory + "</td><td>" + resultrow.name + "</td><td>" + resultrow.image1 + "</td><td>" + resultrow.image2 + "</td><td>" + resultrow.image3 + "</td><td>" + resultrow.image4 + "</td><td>" + resultrow.image5 + "</td><td>" + resultrow.image6 + "</td><td><a class='btn btn-primary btn-xs waves-effect waves-light blue darken-4 z-depth-0 less-pad' href='<?php echo site_url('site/editwallpaper?id=');?>"+resultrow.id+"' data-position='top' data-delay='50' data-tooltip='Edit'><i class='fa fa-pencil propericon'></i></a><a class='btn btn-danger btn-xs waves-effect waves-light red pad10 z-depth-0 less-pad' onclick=\"return confirm('Are you sure you want to delete?');\") href='<?php echo site_url('site/deletewallpaper?id='); ?>"+resultrow.id+"' data-position='top' data-delay='50' data-tooltip='Delete'><i class='material-icons propericon'>delete</i></a></td></tr>";
}
generatejquery("<?php echo $base_url;?>");
</script>
