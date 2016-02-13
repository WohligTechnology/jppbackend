<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class schedule_model extends CI_Model
{
public function create($stadium,$team1,$team2,$bookticket,$timestamp,$starttime,$score1,$score2,$startdate,$ishome,$hour,$minute,$matchtime)
{
    $starttime=$hour.":".$minute;
    $startdate = new DateTime($startdate);
        $startdate = $startdate->format('Y-m-d');
$data=array("stadium" => $stadium,"team1" => $team1,"team2" => $team2,"bookticket" => $bookticket,"timestamp" => $timestamp,"starttime" => $starttime,"score1" => $score1,"score2" => $score2,"startdate" => $startdate,"ishome" => $ishome,"hour" => $hour,"minute" => $minute,"matchtime" => $matchtime);
$query=$this->db->insert( "jpp_schedule", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("jpp_schedule")->row();
return $query;
}
function getsingleschedule($id){
$this->db->where("id",$id);
$query=$this->db->get("jpp_schedule")->row();
return $query;
}
public function edit($id,$stadium,$team1,$team2,$bookticket,$timestamp,$starttime,$score1,$score2,$startdate,$ishome,$hour,$minute,$matchtime)
{
    $starttime=$hour.":".$minute;
    $startdate = new DateTime($startdate);
        $startdate = $startdate->format('Y-m-d');
$data=array("stadium" => $stadium,"team1" => $team1,"team2" => $team2,"bookticket" => $bookticket,"timestamp" =>$timestamp,"starttime"=> $starttime,"score1" => $score1,"score2" => $score2,"startdate" => $startdate,"ishome" => $ishome,"hour" => $hour,"minute" => $minute,"matchtime" => $matchtime);
$this->db->where( "id", $id );
$query=$this->db->update( "jpp_schedule", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `jpp_schedule` WHERE `id`='$id'");
return $query;
}
public function getimagebyid($id)
{
$query=$this->db->query("SELECT `image` FROM `jpp_schedule` WHERE `id`='$id'")->row();
return $query;
}
public function getdropdown()
{
$query=$this->db->query("SELECT * FROM `jpp_schedule` ORDER BY `id` 
                    ASC")->row();
$return=array(
"" => "Select Option"
);
foreach($query as $row)
{
$return[$row->id]=$row->id;
}
return $return;
}
    
    public function gethourdropdown()
{
	$gender=array(
			""=>"Hour",
			"00"=>"00",
			"01"=>"01", 
			"02"=>"02",
			"03"=>"03",
			"04"=>"04",
			"05"=>"05",
			"06"=>"06",
			"07"=>"07",
			"08"=>"08",
			"09"=>"09",
			"10"=>"10",
            "11"=>"11",
            "12"=>"12",
            "13"=>"13",
            "14"=>"14",
            "15"=>"15",
            "16"=>"16",
            "17"=>"17",
            "18"=>"18",
            "19"=>"19",
            "20"=>"20",
            "21"=>"21",
            "22"=>"22",
            "23"=>"23",
            "24"=>"24"
		);

		
		return $gender;
} 
    
    
    public function getminutedropdown()
{
	$gender=array(
			""=>"Min",
			"00"=>"00",
			"01"=>"01", 
			"02"=>"02",
			"03"=>"03",
			"04"=>"04",
			"05"=>"05",
			"06"=>"06",
			"07"=>"07",
			"08"=>"08",
			"09"=>"09",
			"10"=>"10",
            "11"=>"11",
            "12"=>"12",
            "13"=>"13",
            "14"=>"14",
            "15"=>"15",
            "16"=>"16",
            "17"=>"17",
            "18"=>"18",
            "19"=>"19",
            "20"=>"20",
            "21"=>"21",
            "22"=>"22",
            "23"=>"23",
            "24"=>"24",
            "25"=>"25",
            "26"=>"26",
            "27"=>"27",
            "28"=>"28",
            "29"=>"29",
            "30"=>"30",
            "31"=>"31",
            "32"=>"32",
            "33"=>"33",
            "34"=>"34",
            "35"=>"35",
            "36"=>"36",
            "37"=>"37",
            "38"=>"38",
            "39"=>"39",
            "40"=>"40",
            "41"=>"41",
            "42"=>"42",
            "43"=>"43",
            "44"=>"44",
            "45"=>"45",
            "46"=>"46",
            "47"=>"47",
            "48"=>"48",
            "49"=>"49",
            "50"=>"50",
            "51"=>"51",
            "52"=>"52",
            "53"=>"53",
            "54"=>"54",
            "55"=>"55",
            "56"=>"56",
            "57"=>"57",
            "58"=>"58",
            "59"=>"59"
		);

		
		return $gender;
}
}
?>
