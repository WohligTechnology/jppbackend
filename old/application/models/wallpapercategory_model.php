<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class wallpapercategory_model extends CI_Model
{
public function create($order,$name,$image,$hname)
{
$data=array("order" => $order,"name" => $name,"image" => $image,"hname" => $hname);
$query=$this->db->insert( "jpp_wallpapercategory", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("jpp_wallpapercategory")->row();
return $query;
}
function getsinglewallpapercategory($id){
$this->db->where("id",$id);
$query=$this->db->get("jpp_wallpapercategory")->row();
return $query;
}
public function edit($id,$order,$name,$image,$hname)
{
if($image=="")
{
$image=$this->wallpapercategory_model->getimagebyid($id);
$image=$image->image;
}
$data=array("order" => $order,"name" => $name,"image" => $image,"hname" => $hname);
$this->db->where( "id", $id );
$query=$this->db->update( "jpp_wallpapercategory", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `jpp_wallpapercategory` WHERE `id`='$id'");
return $query;
}
public function getimagebyid($id)
{
$query=$this->db->query("SELECT `image` FROM `jpp_wallpapercategory` WHERE `id`='$id'")->row();
return $query;
}
public function getdropdown()
{
$query=$this->db->query("SELECT * FROM `jpp_wallpapercategory` ORDER BY `id` ASC")->result();
$return=array(
"" => "Select Option"
);
foreach($query as $row)
{
$return[$row->id]=$row->name;
}
return $return;
}
}
?>
