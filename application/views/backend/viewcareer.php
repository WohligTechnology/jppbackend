<div class="row">
    <div class="col s12">
        <div class="row">
            <div class="col s12 drawchintantable">
                <?php $this->chintantable->createsearch(" Career");?>
                <table class="highlight responsive-table">
                <thead>
                    <tr>
                        <th data-field="id">ID</th>
                        <th data-field="matchplayed">matchplayed</th>
                        <th data-field="totalpoints">totalpoints</th>
                        <th data-field="action">action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>
            </div>
        </div>
        <?php $this->chintantable->createpagination();?>
<!--        <div class="createbuttonplacement"><a class="btn-floating btn-large waves-effect waves-light blue darken-4 tooltipped" href="<?php echo site_url("site/createcareer?id=").$this->input->get("id"); ?>"data-position="top" data-delay="50" data-tooltip="Create"><i class="material-icons">add</i></a></div>-->
    </div>
</div>
<script>
function drawtable(resultrow) {
     
return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.matchplayed + "</td><td>" + resultrow.totalpoints + "</td><td><a class='btn btn-primary btn-xs waves-effect waves-light blue darken-4 z-depth-0 less-pad tooltipped' href='<?php echo site_url('site/editcareer?id=');?>"+resultrow.id+"&playerid="+resultrow.playerid+"' data-position='top' data-delay='50' data-tooltip='Edit'><i class='fa fa-pencil propericon'></i></a></td></tr>";
}
generatejquery("<?php echo $base_url;?>");
</script>
