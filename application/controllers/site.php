<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller 
{
	public function __construct( )
	{
		parent::__construct();
		
		$this->is_logged_in();
	}
	function is_logged_in( )
	{
		$is_logged_in = $this->session->userdata( 'logged_in' );
		if ( $is_logged_in !== 'true' || !isset( $is_logged_in ) ) {
			redirect( base_url() . 'index.php/login', 'refresh' );
		} //$is_logged_in !== 'true' || !isset( $is_logged_in )
	}
	function checkaccess($access)
	{
		$accesslevel=$this->session->userdata('accesslevel');
		if(!in_array($accesslevel,$access))
			redirect( base_url() . 'index.php/site?alerterror=You do not have access to this page. ', 'refresh' );
	}
    public function getOrderingDone()
    {
        $orderby=$this->input->get("orderby");
        $ids=$this->input->get("ids");
        $ids=explode(",",$ids);
        $tablename=$this->input->get("tablename");
        $where=$this->input->get("where");
        if($where == "" || $where=="undefined")
        {
            $where=1;
        }
        $access = array(
            '1',
        );
        $this->checkAccess($access);
        $i=1;
        foreach($ids as $id)
        {
            //echo "UPDATE `$tablename` SET `$orderby` = '$i' WHERE `id` = `$id` AND $where";
            $this->db->query("UPDATE `$tablename` SET `$orderby` = '$i' WHERE `id` = '$id' AND $where");
            $i++;
            //echo "/n";
        }
        $data["message"]=true;
        $this->load->view("json",$data);
        
    }
	public function index()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$data['page']='viewusers';
        $data['base_url'] = site_url("site/viewusersjson");
		$data[ 'title' ] = 'Welcome';
		$this->load->view( 'template', $data );	
	}
	public function createuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
        $data['gender']=$this->user_model->getgenderdropdown();
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'createuser';
		$data[ 'title' ] = 'Create User';
		$this->load->view( 'template', $data );	
	}
	function createusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
            $data['gender']=$this->user_model->getgenderdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
            $data[ 'page' ] = 'createuser';
            $data[ 'title' ] = 'Create User';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $name=$this->input->post('name');
            $email=$this->input->post('email');
            $password=$this->input->post('password');
            $accesslevel=$this->input->post('accesslevel');
            $status=$this->input->post('status');
            $socialid=$this->input->post('socialid');
            $logintype=$this->input->post('logintype');
            $json=$this->input->post('json');
            $firstname=$this->input->post('firstname');
            $lastname=$this->input->post('lastname');
            $phone=$this->input->post('phone');
            $billingaddress=$this->input->post('billingaddress');
            $billingcity=$this->input->post('billingcity');
            $billingstate=$this->input->post('billingstate');
            $billingcountry=$this->input->post('billingcountry');
            $billingpincode=$this->input->post('billingpincode');
            $billingcontact=$this->input->post('billingcontact');
            
            $shippingaddress=$this->input->post('shippingaddress');
            $shippingcity=$this->input->post('shippingcity');
            $shippingstate=$this->input->post('shippingstate');
            $shippingcountry=$this->input->post('shippingcountry');
            $shippingpincode=$this->input->post('shippingpincode');
            $shippingcontact=$this->input->post('shippingcontact');
            $shippingname=$this->input->post('shippingname');
            $currency=$this->input->post('currency');
            $credit=$this->input->post('credit');
            $companyname=$this->input->post('companyname');
            $registrationno=$this->input->post('registrationno');
            $vatnumber=$this->input->post('vatnumber');
            $country=$this->input->post('country');
            $fax=$this->input->post('fax');
            $gender=$this->input->post('gender');
            	
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
			if($this->user_model->create($name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json,$firstname,$lastname,$phone,$billingaddress,$billingcity,$billingstate,$billingcountry,$billingpincode,$billingcontact,$shippingaddress,$shippingcity,$shippingstate,$shippingcountry,$shippingpincode,$shippingcontact,$shippingname,$currency,$credit,$companyname,$registrationno,$vatnumber,$country,$fax,$gender)==0)
			$data['alerterror']="New user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			$data['redirect']="site/viewusers";
			$this->load->view("redirect",$data);
		}
	}
    function viewusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewusers';
        $data['base_url'] = site_url("site/viewusersjson");
        
		$data['title']='View Users';
		$this->load->view('template',$data);
	} 
    function viewusersjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        
        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`user`.`socialid`";
        $elements[3]->sort="1";
        $elements[3]->header="SocialId";
        $elements[3]->alias="socialid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`user`.`logintype`";
        $elements[4]->sort="1";
        $elements[4]->header="Logintype";
        $elements[4]->alias="logintype";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`json`";
        $elements[5]->sort="1";
        $elements[5]->header="Json";
        $elements[5]->alias="json";
       
        $elements[6]=new stdClass();
        $elements[6]->field="`accesslevel`.`name`";
        $elements[6]->sort="1";
        $elements[6]->header="Accesslevel";
        $elements[6]->alias="accesslevelname";
       
        $elements[7]=new stdClass();
        $elements[7]->field="`statuses`.`name`";
        $elements[7]->sort="1";
        $elements[7]->header="Status";
        $elements[7]->alias="status";
       
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` LEFT OUTER JOIN `logintype` ON `logintype`.`id`=`user`.`logintype` LEFT OUTER JOIN `accesslevel` ON `accesslevel`.`id`=`user`.`accesslevel` LEFT OUTER JOIN `statuses` ON `statuses`.`id`=`user`.`status`");
        
		$this->load->view("json",$data);
	} 
    
    
	function edituser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
        $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data['gender']=$this->user_model->getgenderdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='edituser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('templatewith2',$data);
	}
	function editusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data['gender']=$this->user_model->getgenderdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
			$data['before']=$this->user_model->beforeedit($this->input->post('id'));
			$data['page']='edituser';
//			$data['page2']='block/userblock';
			$data['title']='Edit User';
			$this->load->view('template',$data);
		}
		else
		{
            
            $id=$this->input->get_post('id');
            $name=$this->input->get_post('name');
            $email=$this->input->get_post('email');
            $password=$this->input->get_post('password');
            $accesslevel=$this->input->get_post('accesslevel');
            $status=$this->input->get_post('status');
            $socialid=$this->input->get_post('socialid');
            $logintype=$this->input->get_post('logintype');
            $json=$this->input->get_post('json');
//            $category=$this->input->get_post('category');
            $firstname=$this->input->post('firstname');
            $lastname=$this->input->post('lastname');
            $phone=$this->input->post('phone');
            $billingaddress=$this->input->post('billingaddress');
            $billingcity=$this->input->post('billingcity');
            $billingstate=$this->input->post('billingstate');
            $billingcountry=$this->input->post('billingcountry');
            $billingpincode=$this->input->post('billingpincode');
            $billingcontact=$this->input->post('billingcontact');
            
            $shippingaddress=$this->input->post('shippingaddress');
            $shippingcity=$this->input->post('shippingcity');
            $shippingstate=$this->input->post('shippingstate');
            $shippingcountry=$this->input->post('shippingcountry');
            $shippingpincode=$this->input->post('shippingpincode');
            $shippingcontact=$this->input->post('shippingcontact');
            $shippingname=$this->input->post('shippingname');
            $currency=$this->input->post('currency');
            $credit=$this->input->post('credit');
            $companyname=$this->input->post('companyname');
            $registrationno=$this->input->post('registrationno');
            $vatnumber=$this->input->post('vatnumber');
            $country=$this->input->post('country');
            $fax=$this->input->post('fax');
            $gender=$this->input->post('gender');
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
            if($image=="")
            {
            $image=$this->user_model->getuserimagebyid($id);
               // print_r($image);
                $image=$image->image;
            }
            
			if($this->user_model->edit($id,$name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json,$firstname,$lastname,$phone,$billingaddress,$billingcity,$billingstate,$billingcountry,$billingpincode,$billingcontact,$shippingaddress,$shippingcity,$shippingstate,$shippingcountry,$shippingpincode,$shippingcontact,$shippingname,$currency,$credit,$companyname,$registrationno,$vatnumber,$country,$fax,$gender)==0)
			$data['alerterror']="User Editing was unsuccesful";
			else
			$data['alertsuccess']="User edited Successfully.";
			
			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
	function deleteuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
//		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="User Deleted Successfully";
		$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
		$this->load->view("redirect",$data);
	}
	function changeuserstatus()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->changestatus($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="Status Changed Successfully";
		$data['redirect']="site/viewusers";
        $data['other']="template=$template";
        $this->load->view("redirect",$data);
	}
    public function viewcart()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewcart";
    $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
$data['page2']='block/userblock';
$data["base_url"]=site_url("site/viewcartjson?id=").$this->input->get('id');
$data["title"]="View cart";
$this->load->view("templatewith2",$data);
}
function viewcartjson()
{
    $id=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`fynx_cart`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`fynx_cart`.`user`";
$elements[1]->sort="1";
$elements[1]->header="User";
$elements[1]->alias="user";
$elements[2]=new stdClass();
$elements[2]->field="`fynx_cart`.`quantity`";
$elements[2]->sort="1";
$elements[2]->header="Quantity";
$elements[2]->alias="quantity";
$elements[3]=new stdClass();
$elements[3]->field="`fynx_cart`.`product`";
$elements[3]->sort="1";
$elements[3]->header="Product";
$elements[3]->alias="product";
$elements[4]=new stdClass();
$elements[4]->field="`fynx_cart`.`timestamp`";
$elements[4]->sort="1";
$elements[4]->header="Timestamp";
$elements[4]->alias="timestamp";
    
$elements[5]=new stdClass();
$elements[5]->field="`fynx_cart`.`size`";
$elements[5]->sort="1";
$elements[5]->header="Size";
$elements[5]->alias="size";

$elements[6]=new stdClass();
$elements[6]->field="`fynx_cart`.`color`";
$elements[6]->sort="1";
$elements[6]->header="Color";
$elements[6]->alias="color";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `fynx_cart`","WHERE `fynx_cart`.`user`='$id'");
$this->load->view("json",$data);
}
    public function viewwishlist()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewwishlist";
    $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
$data['page2']='block/userblock';
$data["base_url"]=site_url("site/viewwishlistjson?id=".$this->input->get('id'));
$data["title"]="View wishlist";
$this->load->view("templatewith2",$data);
}
function viewwishlistjson()
{
    $user=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`fynx_wishlist`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`fynx_wishlist`.`user`";
$elements[1]->sort="1";
$elements[1]->header="User";
$elements[1]->alias="user";
$elements[2]=new stdClass();
$elements[2]->field="`fynx_wishlist`.`product`";
$elements[2]->sort="1";
$elements[2]->header="Product";
$elements[2]->alias="product";
$elements[3]=new stdClass();
$elements[3]->field="`fynx_wishlist`.`timestamp`";
$elements[3]->sort="1";
$elements[3]->header="Timestamp";
$elements[3]->alias="timestamp";
    
$elements[4]=new stdClass();
$elements[4]->field="`fynx_product`.`name`";
$elements[4]->sort="1";
$elements[4]->header="Product Name";
$elements[4]->alias="productname";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `fynx_wishlist` LEFT OUTER JOIN `fynx_product` ON `fynx_product`.`id`=`fynx_wishlist`.`product`","WHERE `fynx_wishlist`.`user`='$user'");
$this->load->view("json",$data);
}
    
    
    
    
public function viewstadium()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewstadium";
$data["base_url"]=site_url("site/viewstadiumjson");
$data["title"]="View stadium";
$this->load->view("template",$data);
}
function viewstadiumjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_stadium`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_stadium`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_stadium`");
$this->load->view("json",$data);
}

public function createstadium()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createstadium";
$data["title"]="Create stadium";
$this->load->view("template",$data);
}
public function createstadiumsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","Name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createstadium";
$data["title"]="Create stadium";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->stadium_model->create($name)==0)
$data["alerterror"]="New stadium could not be created.";
else
$data["alertsuccess"]="stadium created Successfully.";
$data["redirect"]="site/viewstadium";
$this->load->view("redirect",$data);
}
}
public function editstadium()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editstadium";
$data["title"]="Edit stadium";
$data["before"]=$this->stadium_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editstadiumsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("name","Name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editstadium";
$data["title"]="Edit stadium";
$data["before"]=$this->stadium_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->stadium_model->edit($id,$name)==0)
$data["alerterror"]="New stadium could not be Updated.";
else
$data["alertsuccess"]="stadium Updated Successfully.";
$data["redirect"]="site/viewstadium";
$this->load->view("redirect",$data);
}
}
public function deletestadium()
{
$access=array("1");
$this->checkaccess($access);
$this->stadium_model->delete($this->input->get("id"));
$data["redirect"]="site/viewstadium";
$this->load->view("redirect",$data);
}
public function viewpoint()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewpoint";
$data["base_url"]=site_url("site/viewpointjson");
$data["title"]="View point";
$this->load->view("template",$data);
}
function viewpointjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_point`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_point`.`played`";
$elements[1]->sort="1";
$elements[1]->header="played";
$elements[1]->alias="played";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_point`.`wins`";
$elements[2]->sort="1";
$elements[2]->header="Wins";
$elements[2]->alias="wins";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_point`.`lost`";
$elements[3]->sort="1";
$elements[3]->header="Lost";
$elements[3]->alias="lost";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_point`.`point`";
$elements[4]->sort="1";
$elements[4]->header="Point";
$elements[4]->alias="point";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_point`");
$this->load->view("json",$data);
}

public function createpoint()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createpoint";
$data["team"]=$this->team_model->getdropdown();
$data["title"]="Create point";
$this->load->view("template",$data);
}
public function createpointsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("played","played","trim");
$this->form_validation->set_rules("wins","Wins","trim");
$this->form_validation->set_rules("lost","Lost","trim");
$this->form_validation->set_rules("point","Point","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createpoint";
$data["team"]=$this->team_model->getdropdown();
$data["title"]="Create point";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$played=$this->input->get_post("played");
$wins=$this->input->get_post("wins");
$lost=$this->input->get_post("lost");
$point=$this->input->get_post("point");
$team=$this->input->get_post("team");
if($this->point_model->create($played,$wins,$lost,$point,$team)==0)
$data["alerterror"]="New point could not be created.";
else
$data["alertsuccess"]="point created Successfully.";
$data["redirect"]="site/viewpoint";
$this->load->view("redirect",$data);
}
}
public function editpoint()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editpoint";
$data["team"]=$this->team_model->getdropdown();
$data["title"]="Edit point";
$data["before"]=$this->point_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editpointsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("played","played","trim");
$this->form_validation->set_rules("wins","Wins","trim");
$this->form_validation->set_rules("lost","Lost","trim");
$this->form_validation->set_rules("point","Point","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["team"]=$this->team_model->getdropdown();
$data["page"]="editpoint";
$data["title"]="Edit point";
$data["before"]=$this->point_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$played=$this->input->get_post("played");
$wins=$this->input->get_post("wins");
$lost=$this->input->get_post("lost");
$point=$this->input->get_post("point");
$team=$this->input->get_post("team");
if($this->point_model->edit($id,$played,$wins,$lost,$point,$team)==0)
$data["alerterror"]="New point could not be Updated.";
else
$data["alertsuccess"]="point Updated Successfully.";
$data["redirect"]="site/viewpoint";
$this->load->view("redirect",$data);
}
}
public function deletepoint()
{
$access=array("1");
$this->checkaccess($access);
$this->point_model->delete($this->input->get("id"));
$data["redirect"]="site/viewpoint";
$this->load->view("redirect",$data);
}
public function viewschedule()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewschedule";
$data["base_url"]=site_url("site/viewschedulejson");
$data["title"]="View schedule";
$this->load->view("template",$data);
}
function viewschedulejson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_schedule`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_stadium`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Stadium";
$elements[1]->alias="stadium";
$elements[2]=new stdClass();
$elements[2]->field="`jppteam1`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Team1";
$elements[2]->alias="team1";
$elements[3]=new stdClass();
$elements[3]->field="`jppteam2`.`name`";
$elements[3]->sort="1";
$elements[3]->header="Team2";
$elements[3]->alias="team2";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_schedule`.`bookticket`";
$elements[4]->sort="1";
$elements[4]->header="Book Ticket";
$elements[4]->alias="bookticket";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_schedule`.`timestamp`";
$elements[5]->sort="1";
$elements[5]->header="Timestamp";
$elements[5]->alias="timestamp";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_schedule` 
LEFT OUTER JOIN `jpp_team` as `jppteam1` ON `jppteam1`.`id`=`jpp_schedule`.`team1` LEFT OUTER JOIN `jpp_team` as `jppteam2` ON `jppteam2`.`id`=`jpp_schedule`.`team2` LEFT OUTER JOIN `jpp_stadium` ON `jpp_stadium`.`id`=`jpp_schedule`.`stadium`");
$this->load->view("json",$data);
}

public function createschedule()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createschedule";
$data["stadium"]=$this->stadium_model->getdropdown();
$data["team1"]=$this->team_model->getdropdown();
$data["team2"]=$this->team_model->getdropdown();
$data["title"]="Create schedule";
$this->load->view("template",$data);
}
public function createschedulesubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("stadium","stadium","trim");
$this->form_validation->set_rules("team1","Team1","trim");
$this->form_validation->set_rules("team2","Team2","trim");
$this->form_validation->set_rules("bookticket","Book Ticket","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["stadium"]=$this->stadium_model->getdropdown();
$data["team1"]=$this->team_model->getdropdown();
$data["team2"]=$this->team_model->getdropdown();
$data["page"]="createschedule";
$data["title"]="Create schedule";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$stadium=$this->input->get_post("stadium");
$team1=$this->input->get_post("team1");
$team2=$this->input->get_post("team2");
$bookticket=$this->input->get_post("bookticket");
$starttime=$this->input->get_post("starttime");
$score1=$this->input->get_post("score1");
$score2=$this->input->get_post("score2");
$startdate=$this->input->get_post("startdate");
if($this->schedule_model->create($stadium,$team1,$team2,$bookticket,$timestamp,$starttime,$score1,$score2,$startdate)==0)
$data["alerterror"]="New schedule could not be created.";
else
$data["alertsuccess"]="schedule created Successfully.";
$data["redirect"]="site/viewschedule";
$this->load->view("redirect",$data);
}
}
public function editschedule()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editschedule";
$data["stadium"]=$this->stadium_model->getdropdown();
$data["team1"]=$this->team_model->getdropdown();
$data["team2"]=$this->team_model->getdropdown();
$data["title"]="Edit schedule";
$data["before"]=$this->schedule_model->beforeedit($this->input->get("id"));
    $data['exp'] = explode(':', $data['before']->starttime);
$this->load->view("template",$data);
}
public function editschedulesubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("stadium","stadium","trim");
$this->form_validation->set_rules("team1","Team1","trim");
$this->form_validation->set_rules("team2","Team2","trim");
$this->form_validation->set_rules("bookticket","Book Ticket","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editschedule";
$data["stadium"]=$this->stadium_model->getdropdown();
$data["team1"]=$this->team_model->getdropdown();
$data["team2"]=$this->team_model->getdropdown();
$data["title"]="Edit schedule";
$data["before"]=$this->schedule_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$stadium=$this->input->get_post("stadium");
$team1=$this->input->get_post("team1");
$team2=$this->input->get_post("team2");
$bookticket=$this->input->get_post("bookticket");
$timestamp=$this->input->get_post("timestamp");
$starttime=$this->input->get_post("starttime");
$score1=$this->input->get_post("score1");
$score2=$this->input->get_post("score2");
    $startdate=$this->input->get_post("startdate");
if($this->schedule_model->edit($id,$stadium,$team1,$team2,$bookticket,$timestamp,$starttime,$score1,$score2,$startdate)==0)
$data["alerterror"]="New schedule could not be Updated.";
else
$data["alertsuccess"]="schedule Updated Successfully.";
$data["redirect"]="site/viewschedule";
$this->load->view("redirect",$data);
}
}
public function deleteschedule()
{
$access=array("1");
$this->checkaccess($access);
$this->schedule_model->delete($this->input->get("id"));
$data["redirect"]="site/viewschedule";
$this->load->view("redirect",$data);
}
public function viewshop()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewshop";
$data["base_url"]=site_url("site/viewshopjson");
$data["title"]="View shop";
$this->load->view("template",$data);
}
function viewshopjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_shop`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_shop`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_shop`.`product`";
$elements[2]->sort="1";
$elements[2]->header="Product";
$elements[2]->alias="product";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_shop`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_shop`.`link`";
$elements[4]->sort="1";
$elements[4]->header="Link";
$elements[4]->alias="link";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_shop`");
$this->load->view("json",$data);
}

public function createshop()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createshop";
$data["title"]="Create shop";
$this->load->view("template",$data);
}
public function createshopsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("product","Product","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("link","Link","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createshop";
$data["title"]="Create shop";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$product=$this->input->get_post("product");
$image=$this->input->get_post("image");
$link=$this->input->get_post("link");
$image=$this->menu_model->createImage();
   
if($this->shop_model->create($order,$product,$image,$link)==0)
$data["alerterror"]="New shop could not be created.";
else
$data["alertsuccess"]="shop created Successfully.";
$data["redirect"]="site/viewshop";
$this->load->view("redirect",$data);
}
}
public function editshop()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editshop";
$data["title"]="Edit shop";
$data["before"]=$this->shop_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editshopsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("product","Product","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("link","Link","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editshop";
$data["title"]="Edit shop";
$data["before"]=$this->shop_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$product=$this->input->get_post("product");
$image=$this->input->get_post("image");
$link=$this->input->get_post("link");
$image=$this->menu_model->createImage();
if($this->shop_model->edit($id,$order,$product,$image,$link)==0)
$data["alerterror"]="New shop could not be Updated.";
else
$data["alertsuccess"]="shop Updated Successfully.";
$data["redirect"]="site/viewshop";
$this->load->view("redirect",$data);
}
}
public function deleteshop()
{
$access=array("1");
$this->checkaccess($access);
$this->shop_model->delete($this->input->get("id"));
$data["redirect"]="site/viewshop";
$this->load->view("redirect",$data);
}
public function viewmerchandize()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewmerchandize";
$data["base_url"]=site_url("site/viewmerchandizejson");
$data["title"]="View merchandize";
$this->load->view("template",$data);
}
function viewmerchandizejson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_merchandize`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_merchandize`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_merchandize`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_merchandize`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_merchandize`.`link`";
$elements[4]->sort="1";
$elements[4]->header="Link";
$elements[4]->alias="link";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_merchandize`");
$this->load->view("json",$data);
}

public function createmerchandize()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createmerchandize";
$data["title"]="Create merchandize";
$this->load->view("template",$data);
}
public function createmerchandizesubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("link","Link","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createmerchandize";
$data["title"]="Create merchandize";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$link=$this->input->get_post("link");
    $image=$this->menu_model->createImage();
if($this->merchandize_model->create($order,$name,$image,$link)==0)
$data["alerterror"]="New merchandize could not be created.";
else
$data["alertsuccess"]="merchandize created Successfully.";
$data["redirect"]="site/viewmerchandize";
$this->load->view("redirect",$data);
}
}
public function editmerchandize()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editmerchandize";
$data["title"]="Edit merchandize";
$data["before"]=$this->merchandize_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editmerchandizesubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("link","Link","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editmerchandize";
$data["title"]="Edit merchandize";
$data["before"]=$this->merchandize_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$link=$this->input->get_post("link");
$image=$this->menu_model->createImage();
if($this->merchandize_model->edit($id,$order,$name,$image,$link)==0)
$data["alerterror"]="New merchandize could not be Updated.";
else
$data["alertsuccess"]="merchandize Updated Successfully.";
$data["redirect"]="site/viewmerchandize";
$this->load->view("redirect",$data);
}
}
public function deletemerchandize()
{
$access=array("1");
$this->checkaccess($access);
$this->merchandize_model->delete($this->input->get("id"));
$data["redirect"]="site/viewmerchandize";
$this->load->view("redirect",$data);
}
public function viewgallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewgallery";
$data["base_url"]=site_url("site/viewgalleryjson");
$data["title"]="View gallery";
$this->load->view("template",$data);
}
function viewgalleryjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_gallery`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_gallery`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_gallery`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_gallery`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_gallery`");
$this->load->view("json",$data);
}

public function creategallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="creategallery";
$data["title"]="Create gallery";
$this->load->view("template",$data);
}
public function creategallerysubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="creategallery";
$data["title"]="Create gallery";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->gallery_model->create($order,$name,$image)==0)
$data["alerterror"]="New gallery could not be created.";
else
$data["alertsuccess"]="gallery created Successfully.";
$data["redirect"]="site/viewgallery";
$this->load->view("redirect",$data);
}
}
public function editgallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editgallery";
$data["page2"]="block/galleryblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["title"]="Edit gallery";
$data["before"]=$this->gallery_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editgallerysubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editgallery";
$data["title"]="Edit gallery";
$data["before"]=$this->gallery_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->gallery_model->edit($id,$order,$name,$image)==0)
$data["alerterror"]="New gallery could not be Updated.";
else
$data["alertsuccess"]="gallery Updated Successfully.";
$data["redirect"]="site/viewgallery";
$this->load->view("redirect",$data);
}
}
public function deletegallery()
{
$access=array("1");
$this->checkaccess($access);
$this->gallery_model->delete($this->input->get("id"));
$data["redirect"]="site/viewgallery";
$this->load->view("redirect",$data);
}
public function viewgalleryslide()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewgalleryslide";
$data["page2"]="block/galleryblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["base_url"]=site_url("site/viewgalleryslidejson?id=".$this->input->get('id'));
$data["title"]="View galleryslide";
$this->load->view("templatewith2",$data);
}
function viewgalleryslidejson()
{
$id=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_galleryslide`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_galleryslide`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_galleryslide`.`gallery`";
$elements[2]->sort="1";
$elements[2]->header="Gallery";
$elements[2]->alias="gallery";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_galleryslide`.`name`";
$elements[3]->sort="1";
$elements[3]->header="Name";
$elements[3]->alias="name";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_galleryslide`.`image`";
$elements[4]->sort="1";
$elements[4]->header="Image";
$elements[4]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_galleryslide`","WHERE `jpp_galleryslide`.`gallery`='$id'");
$this->load->view("json",$data);
}

public function creategalleryslide()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="creategalleryslide";
$data["page2"]="block/galleryblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["gallery"]=$this->gallery_model->getdropdown();
$data["title"]="Create galleryslide";
$this->load->view("templatewith2",$data);
}
public function creategalleryslidesubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("gallery","Gallery","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["gallery"]=$this->gallery_model->getdropdown();
$data["page"]="creategalleryslide";
$data["title"]="Create galleryslide";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$gallery=$this->input->get_post("gallery");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->galleryslide_model->create($order,$gallery,$name,$image)==0)
$data["alerterror"]="New galleryslide could not be created.";
else
$data["alertsuccess"]="galleryslide created Successfully.";
$data["redirect"]="site/viewgalleryslide?id=".$gallery;
$this->load->view("redirect2",$data);
}
}
public function editgalleryslide()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editgalleryslide";
$data["page2"]="block/galleryblock";
$data["before1"]=$this->input->get('galleryid');
$data["before2"]=$this->input->get('galleryid');
$data["gallery"]=$this->gallery_model->getdropdown();
$data["title"]="Edit galleryslide";
$data["before"]=$this->galleryslide_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editgalleryslidesubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("gallery","Gallery","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["gallery"]=$this->gallery_model->getdropdown();
$data["page"]="editgalleryslide";
$data["title"]="Edit galleryslide";
$data["before"]=$this->galleryslide_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$gallery=$this->input->get_post("gallery");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->galleryslide_model->edit($id,$order,$gallery,$name,$image)==0)
$data["alerterror"]="New galleryslide could not be Updated.";
else
$data["alertsuccess"]="galleryslide Updated Successfully.";
$data["redirect"]="site/viewgalleryslide?id=".$gallery;
$this->load->view("redirect2",$data);
}
}
public function deletegalleryslide()
{
$access=array("1");
$this->checkaccess($access);
$this->galleryslide_model->delete($this->input->get("id"));
$gallery=$this->input->get("galleryid");
$data["redirect"]="site/viewgalleryslide?id=".$gallery;
$this->load->view("redirect2",$data);
}
public function viewnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewnews";
$data["base_url"]=site_url("site/viewnewsjson");
$data["title"]="View news";
$this->load->view("template",$data);
}
function viewnewsjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_news`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_news`.`type`";
$elements[1]->sort="1";
$elements[1]->header="Type";
$elements[1]->alias="type";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_news`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_news`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_news`.`timestamp`";
$elements[4]->sort="1";
$elements[4]->header="Timestamp";
$elements[4]->alias="timestamp";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_news`.`content`";
$elements[5]->sort="1";
$elements[5]->header="Content";
$elements[5]->alias="content";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_news`");
$this->load->view("json",$data);
}

public function createnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createnews";
$data["title"]="Create news";
$this->load->view("template",$data);
}
public function createnewssubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createnews";
$data["title"]="Create news";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$content=$this->input->get_post("content");
$image=$this->menu_model->createImage();
if($this->news_model->create($type,$name,$image,$timestamp,$content)==0)
$data["alerterror"]="New news could not be created.";
else
$data["alertsuccess"]="news created Successfully.";
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
}
public function editnews()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editnews";
$data["title"]="Edit news";
$data["before"]=$this->news_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editnewssubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editnews";
$data["title"]="Edit news";
$data["before"]=$this->news_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$timestamp=$this->input->get_post("timestamp");
$content=$this->input->get_post("content");
$image=$this->menu_model->createImage();
if($this->news_model->edit($id,$type,$name,$image,$timestamp,$content)==0)
$data["alerterror"]="New news could not be Updated.";
else
$data["alertsuccess"]="news Updated Successfully.";
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
}
public function deletenews()
{
$access=array("1");
$this->checkaccess($access);
$this->news_model->delete($this->input->get("id"));
$data["redirect"]="site/viewnews";
$this->load->view("redirect",$data);
}
public function viewplayers()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewplayers";
$data["base_url"]=site_url("site/viewplayersjson");
$data["title"]="View players";
$this->load->view("template",$data);
}
function viewplayersjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_players`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_players`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_players`.`type`";
$elements[2]->sort="1";
$elements[2]->header="Type";
$elements[2]->alias="type";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_players`.`name`";
$elements[3]->sort="1";
$elements[3]->header="Name";
$elements[3]->alias="name";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_players`.`nationality`";
$elements[4]->sort="1";
$elements[4]->header="Nationality";
$elements[4]->alias="nationality";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_players`.`jerseyno`";
$elements[5]->sort="1";
$elements[5]->header="Jerseyno";
$elements[5]->alias="jerseyno";
$elements[6]=new stdClass();
$elements[6]->field="`jpp_players`.`about`";
$elements[6]->sort="1";
$elements[6]->header="About";
$elements[6]->alias="about";
$elements[7]=new stdClass();
$elements[7]->field="`jpp_players`.`dob`";
$elements[7]->sort="1";
$elements[7]->header="Dob";
$elements[7]->alias="dob";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_players`");
$this->load->view("json",$data);
}

public function createplayers()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createplayers";
$data["title"]="Create players";
$this->load->view("template",$data);
}
public function createplayerssubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("nationality","Nationality","trim");
$this->form_validation->set_rules("jerseyno","Jerseyno","trim");
$this->form_validation->set_rules("about","About","trim");
$this->form_validation->set_rules("dob","Dob","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createplayers";
$data["title"]="Create players";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$nationality=$this->input->get_post("nationality");
$jerseyno=$this->input->get_post("jerseyno");
$about=$this->input->get_post("about");
$dob=$this->input->get_post("dob");
if($this->players_model->create($order,$type,$name,$nationality,$jerseyno,$about,$dob)==0)
$data["alerterror"]="New players could not be created.";
else
$data["alertsuccess"]="players created Successfully.";
$data["redirect"]="site/viewplayers";
$this->load->view("redirect",$data);
}
}
public function editplayers()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editplayers";
$data["title"]="Edit players";
$data["before"]=$this->players_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editplayerssubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("nationality","Nationality","trim");
$this->form_validation->set_rules("jerseyno","Jerseyno","trim");
$this->form_validation->set_rules("about","About","trim");
$this->form_validation->set_rules("dob","Dob","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editplayers";
$data["title"]="Edit players";
$data["before"]=$this->players_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$nationality=$this->input->get_post("nationality");
$jerseyno=$this->input->get_post("jerseyno");
$about=$this->input->get_post("about");
$dob=$this->input->get_post("dob");
if($this->players_model->edit($id,$order,$type,$name,$nationality,$jerseyno,$about,$dob)==0)
$data["alerterror"]="New players could not be Updated.";
else
$data["alertsuccess"]="players Updated Successfully.";
$data["redirect"]="site/viewplayers";
$this->load->view("redirect",$data);
}
}
public function deleteplayers()
{
$access=array("1");
$this->checkaccess($access);
$this->players_model->delete($this->input->get("id"));
$data["redirect"]="site/viewplayers";
$this->load->view("redirect",$data);
}
public function viewwallpapercategory()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewwallpapercategory";
$data["base_url"]=site_url("site/viewwallpapercategoryjson");
$data["title"]="View wallpapercategory";
$this->load->view("template",$data);
}
function viewwallpapercategoryjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_wallpapercategory`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_wallpapercategory`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_wallpapercategory`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_wallpapercategory`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_wallpapercategory`");
$this->load->view("json",$data);
}

public function createwallpapercategory()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createwallpapercategory";
$data["title"]="Create wallpapercategory";
$this->load->view("template",$data);
}
public function createwallpapercategorysubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createwallpapercategory";
$data["title"]="Create wallpapercategory";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->wallpapercategory_model->create($order,$name,$image)==0)
$data["alerterror"]="New wallpapercategory could not be created.";
else
$data["alertsuccess"]="wallpapercategory created Successfully.";
$data["redirect"]="site/viewwallpapercategory";
$this->load->view("redirect",$data);
}
}
public function editwallpapercategory()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editwallpapercategory";
$data["page2"]="block/wallpaperblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["title"]="Edit wallpapercategory";
$data["before"]=$this->wallpapercategory_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editwallpapercategorysubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editwallpapercategory";
$data["title"]="Edit wallpapercategory";
$data["before"]=$this->wallpapercategory_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->wallpapercategory_model->edit($id,$order,$name,$image)==0)
$data["alerterror"]="New wallpapercategory could not be Updated.";
else
$data["alertsuccess"]="wallpapercategory Updated Successfully.";
$data["redirect"]="site/viewwallpapercategory";
$this->load->view("redirect",$data);
}
}
public function deletewallpapercategory()
{
$access=array("1");
$this->checkaccess($access);
$this->wallpapercategory_model->delete($this->input->get("id"));
$data["redirect"]="site/viewwallpapercategory";
$this->load->view("redirect",$data);
}
public function viewwallpaper()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewwallpaper";
$data["page2"]="block/wallpaperblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["base_url"]=site_url("site/viewwallpaperjson?id=").$this->input->get("id");
$data["title"]="View wallpaper";
$this->load->view("templatewith2",$data);
}
function viewwallpaperjson()
{
$id=$this->input->get("id");
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_wallpaper`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_wallpaper`.`wallpapercategory`";
$elements[1]->sort="1";
$elements[1]->header="Wallpaper Category";
$elements[1]->alias="wallpapercategory";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_wallpaper`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_wallpaper`.`image1`";
$elements[3]->sort="1";
$elements[3]->header="Image1";
$elements[3]->alias="image1";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_wallpaper`.`image2`";
$elements[4]->sort="1";
$elements[4]->header="Image2";
$elements[4]->alias="image2";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_wallpaper`.`image3`";
$elements[5]->sort="1";
$elements[5]->header="Image3";
$elements[5]->alias="image3";
$elements[6]=new stdClass();
$elements[6]->field="`jpp_wallpaper`.`image4`";
$elements[6]->sort="1";
$elements[6]->header="Image4";
$elements[6]->alias="image4";
$elements[7]=new stdClass();
$elements[7]->field="`jpp_wallpaper`.`image5`";
$elements[7]->sort="1";
$elements[7]->header="Image5";
$elements[7]->alias="image5";
$elements[8]=new stdClass();
$elements[8]->field="`jpp_wallpaper`.`image6`";
$elements[8]->sort="1";
$elements[8]->header="Image6";
$elements[8]->alias="image6";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_wallpaper`","WHERE `jpp_wallpaper`.`wallpapercategory`='$id'");
$this->load->view("json",$data);
}

public function createwallpaper()
{
$access=array("1");
$this->checkaccess($access);
$data["wallpapercategory"]=$this->wallpapercategory_model->getdropdown();
$data["page"]="createwallpaper";
$data["page2"]="block/wallpaperblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["title"]="Create wallpaper";
$this->load->view("templatewith2",$data);
}
public function createwallpapersubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("wallpapercategory","Wallpaper Category","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image1","Image1","trim");
$this->form_validation->set_rules("image2","Image2","trim");
$this->form_validation->set_rules("image3","Image3","trim");
$this->form_validation->set_rules("image4","Image4","trim");
$this->form_validation->set_rules("image5","Image5","trim");
$this->form_validation->set_rules("image6","Image6","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["wallpapercategory"]=$this->wallpapercategory_model->getdropdown();
$data["page"]="createwallpaper";
$data["title"]="Create wallpaper";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$wallpapercategory=$this->input->get_post("wallpapercategory");
$name=$this->input->get_post("name");
 $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config);
			$filename="image1";
			$image1="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image1=$uploaddata['file_name'];
			}
			$filename="image2";
			$image2="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image2=$uploaddata['file_name'];
			}
    $filename="image3";
			$image3="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image3=$uploaddata['file_name'];
			}
    $filename="image4";
			$image4="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image4=$uploaddata['file_name'];
			}
    $filename="image5";
			$image5="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image5=$uploaddata['file_name'];
			}
    $filename="image6";
			$image6="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image6=$uploaddata['file_name'];
			}
if($this->wallpaper_model->create($wallpapercategory,$name,$image1,$image2,$image3,$image4,$image5,$image6)==0)
$data["alerterror"]="New wallpaper could not be created.";
else
$data["alertsuccess"]="wallpaper created Successfully.";
$data["redirect"]="site/viewwallpaper?id=".$wallpapercategory;
$this->load->view("redirect2",$data);
}
}
public function editwallpaper()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editwallpaper";
$data["page2"]="block/wallpaperblock";
$data["before1"]=$this->input->get('wallpapercategoryid');
$data["before2"]=$this->input->get('wallpapercategoryid');
$data["wallpapercategory"]=$this->wallpapercategory_model->getdropdown();
$data["title"]="Edit wallpaper";
$data["before"]=$this->wallpaper_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editwallpapersubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("wallpapercategory","Wallpaper Category","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image1","Image1","trim");
$this->form_validation->set_rules("image2","Image2","trim");
$this->form_validation->set_rules("image3","Image3","trim");
$this->form_validation->set_rules("image4","Image4","trim");
$this->form_validation->set_rules("image5","Image5","trim");
$this->form_validation->set_rules("image6","Image6","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editwallpaper";
$data["wallpapercategory"]=$this->wallpapercategory_model->getdropdown();
$data["title"]="Edit wallpaper";
$data["before"]=$this->wallpaper_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$wallpapercategory=$this->input->get_post("wallpapercategory");
$name=$this->input->get_post("name");
 $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config);
			$filename="image1";
			$image1="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image1=$uploaddata['file_name'];
			}
			$filename="image2";
			$image2="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image2=$uploaddata['file_name'];
			}
    $filename="image3";
			$image3="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image3=$uploaddata['file_name'];
			}
    $filename="image4";
			$image4="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image4=$uploaddata['file_name'];
			}
    $filename="image5";
			$image5="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image5=$uploaddata['file_name'];
			}
    $filename="image6";
			$image6="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image6=$uploaddata['file_name'];
			}
if($this->wallpaper_model->edit($id,$wallpapercategory,$name,$image1,$image2,$image3,$image4,$image5,$image6)==0)
$data["alerterror"]="New wallpaper could not be Updated.";
else
$data["alertsuccess"]="wallpaper Updated Successfully.";
$data["redirect"]="site/viewwallpaper?id=".$wallpapercategory;
$this->load->view("redirect2",$data);
}
}
public function deletewallpaper()
{
$access=array("1");
$this->checkaccess($access);
$this->wallpaper_model->delete($this->input->get("id"));
$wallpapercategoryid=$this->input->get('wallpapercategoryid');
$data["redirect"]="site/viewwallpaper?id=".$wallpapercategoryid;
$this->load->view("redirect2",$data);
}
public function viewpages()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewpages";
$data["base_url"]=site_url("site/viewpagesjson");
$data["title"]="View pages";
$this->load->view("template",$data);
}
function viewpagesjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_pages`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_pages`.`name`";
$elements[1]->sort="1";
$elements[1]->header="Name";
$elements[1]->alias="name";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_pages`.`content`";
$elements[2]->sort="1";
$elements[2]->header="Content";
$elements[2]->alias="content";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_pages`");
$this->load->view("json",$data);
}

public function createpages()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createpages";
$data["title"]="Create pages";
$this->load->view("template",$data);
}
public function createpagessubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createpages";
$data["title"]="Create pages";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
$content=$this->input->get_post("content");
if($this->pages_model->create($name,$content)==0)
$data["alerterror"]="New pages could not be created.";
else
$data["alertsuccess"]="pages created Successfully.";
$data["redirect"]="site/viewpages";
$this->load->view("redirect",$data);
}
}
public function editpages()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editpages";
$data["title"]="Edit pages";
$data["before"]=$this->pages_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editpagessubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editpages";
$data["title"]="Edit pages";
$data["before"]=$this->pages_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
$content=$this->input->get_post("content");
if($this->pages_model->edit($id,$name,$content)==0)
$data["alerterror"]="New pages could not be Updated.";
else
$data["alertsuccess"]="pages Updated Successfully.";
$data["redirect"]="site/viewpages";
$this->load->view("redirect",$data);
}
}
public function deletepages()
{
$access=array("1");
$this->checkaccess($access);
$this->pages_model->delete($this->input->get("id"));
$data["redirect"]="site/viewpages";
$this->load->view("redirect",$data);
}
public function viewteam()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewteam";
$data["base_url"]=site_url("site/viewteamjson");
$data["title"]="View team";
$this->load->view("template",$data);
}
function viewteamjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_team`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_team`.`type`";
$elements[1]->sort="1";
$elements[1]->header="Type";
$elements[1]->alias="type";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_team`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_team`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_team`.`content`";
$elements[4]->sort="1";
$elements[4]->header="Content";
$elements[4]->alias="content";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_team`");
$this->load->view("json",$data);
}

public function createteam()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createteam";
$data["title"]="Create team";
$this->load->view("template",$data);
}
public function createteamsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createteam";
$data["title"]="Create team";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$content=$this->input->get_post("content");
$image=$this->menu_model->createImage();
if($this->team_model->create($type,$name,$image,$content)==0)
$data["alerterror"]="New team could not be created.";
else
$data["alertsuccess"]="team created Successfully.";
$data["redirect"]="site/viewteam";
$this->load->view("redirect",$data);
}
}
public function editteam()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editteam";
$data["title"]="Edit team";
$data["before"]=$this->team_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editteamsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("type","Type","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
$this->form_validation->set_rules("content","Content","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editteam";
$data["title"]="Edit team";
$data["before"]=$this->team_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$type=$this->input->get_post("type");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$content=$this->input->get_post("content");
$image=$this->menu_model->createImage();
if($this->team_model->edit($id,$type,$name,$image,$content)==0)
$data["alerterror"]="New team could not be Updated.";
else
$data["alertsuccess"]="team Updated Successfully.";
$data["redirect"]="site/viewteam";
$this->load->view("redirect",$data);
}
}
public function deleteteam()
{
$access=array("1");
$this->checkaccess($access);
$this->team_model->delete($this->input->get("id"));
$data["redirect"]="site/viewteam";
$this->load->view("redirect",$data);
}
public function viewsponsor()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewsponsor";
$data["base_url"]=site_url("site/viewsponsorjson");
$data["title"]="View sponsor";
$this->load->view("template",$data);
}
function viewsponsorjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_sponsor`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_sponsor`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_sponsor`.`image`";
$elements[2]->sort="1";
$elements[2]->header="Image";
$elements[2]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_sponsor`");
$this->load->view("json",$data);
}

public function createsponsor()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createsponsor";
$data["title"]="Create sponsor";
$this->load->view("template",$data);
}
public function createsponsorsubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createsponsor";
$data["title"]="Create sponsor";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->sponsor_model->create($order,$image)==0)
$data["alerterror"]="New sponsor could not be created.";
else
$data["alertsuccess"]="sponsor created Successfully.";
$data["redirect"]="site/viewsponsor";
$this->load->view("redirect",$data);
}
}
public function editsponsor()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editsponsor";
$data["title"]="Edit sponsor";
$data["before"]=$this->sponsor_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editsponsorsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editsponsor";
$data["title"]="Edit sponsor";
$data["before"]=$this->sponsor_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->sponsor_model->edit($id,$order,$image)==0)
$data["alerterror"]="New sponsor could not be Updated.";
else
$data["alertsuccess"]="sponsor Updated Successfully.";
$data["redirect"]="site/viewsponsor";
$this->load->view("redirect",$data);
}
}
public function deletesponsor()
{
$access=array("1");
$this->checkaccess($access);
$this->sponsor_model->delete($this->input->get("id"));
$data["redirect"]="site/viewsponsor";
$this->load->view("redirect",$data);
}
public function viewvideogallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewvideogallery";
$data["base_url"]=site_url("site/viewvideogalleryjson");
$data["title"]="View videogallery";
$this->load->view("template",$data);
}
function viewvideogalleryjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_videogallery`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_videogallery`.`order`";
$elements[1]->sort="1";
$elements[1]->header="Order";
$elements[1]->alias="order";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_videogallery`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_videogallery`.`image`";
$elements[3]->sort="1";
$elements[3]->header="Image";
$elements[3]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_videogallery`");
$this->load->view("json",$data);
}

public function createvideogallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createvideogallery";
$data["title"]="Create videogallery";
$this->load->view("template",$data);
}
public function createvideogallerysubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createvideogallery";
$data["title"]="Create videogallery";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->videogallery_model->create($order,$name,$image)==0)
$data["alerterror"]="New videogallery could not be created.";
else
$data["alertsuccess"]="videogallery created Successfully.";
$data["redirect"]="site/viewvideogallery";
$this->load->view("redirect",$data);
}
}
public function editvideogallery()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editvideogallery";
$data["page2"]="block/videoblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["title"]="Edit videogallery";
$data["before"]=$this->videogallery_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editvideogallerysubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editvideogallery";
$data["title"]="Edit videogallery";
$data["before"]=$this->videogallery_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->videogallery_model->edit($id,$order,$name,$image)==0)
$data["alerterror"]="New videogallery could not be Updated.";
else
$data["alertsuccess"]="videogallery Updated Successfully.";
$data["redirect"]="site/viewvideogallery";
$this->load->view("redirect",$data);
}
}
public function deletevideogallery()
{
$access=array("1");
$this->checkaccess($access);
$this->videogallery_model->delete($this->input->get("id"));
$data["redirect"]="site/viewvideogallery";
$this->load->view("redirect",$data);
}
public function viewvideos()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewvideos";
$data["page2"]="block/videoblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["base_url"]=site_url("site/viewvideosjson?id=").$this->input->get('id');
$data["title"]="View videos";
$this->load->view("templatewith2",$data);
}
function viewvideosjson()
{
    $id=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_videos`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_videos`.`videogallery`";
$elements[1]->sort="1";
$elements[1]->header="Video Gallery";
$elements[1]->alias="videogallery";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_videos`.`order`";
$elements[2]->sort="1";
$elements[2]->header="Order";
$elements[2]->alias="order";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_videos`.`name`";
$elements[3]->sort="1";
$elements[3]->header="Name";
$elements[3]->alias="name";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_videos`.`url`";
$elements[4]->sort="1";
$elements[4]->header="url";
$elements[4]->alias="url";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_videos`.`image`";
$elements[5]->sort="1";
$elements[5]->header="Image";
$elements[5]->alias="image";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_videos`","WHERE `jpp_videos`.`videogallery`='$id'");
$this->load->view("json",$data);
}

public function createvideos()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createvideos";
$data["page2"]="block/videoblock";
$data["before1"]=$this->input->get('id');
$data["before2"]=$this->input->get('id');
$data["videogallery"]=$this->videogallery_model->getdropdown();
$data["title"]="Create videos";
$this->load->view("templatewith2",$data);
}
public function createvideossubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("videogallery","Video Gallery","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("url","url","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["videogallery"]=$this->videogallery_model->getdropdown();
$data["page"]="createvideos";
$data["title"]="Create videos";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$videogallery=$this->input->get_post("videogallery");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$url=$this->input->get_post("url");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->videos_model->create($videogallery,$order,$name,$url,$image)==0)
$data["alerterror"]="New videos could not be created.";
else
$data["alertsuccess"]="videos created Successfully.";
$data["redirect"]="site/viewvideos?id=".$videogallery;
$this->load->view("redirect2",$data);
}
}
public function editvideos()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editvideos";
$data["page2"]="block/videoblock";
$data["before1"]=$this->input->get('videogalleryid');
$data["before2"]=$this->input->get('videogalleryid');
$data["videogallery"]=$this->videogallery_model->getdropdown();
$data["title"]="Edit videos";
$data["before"]=$this->videos_model->beforeedit($this->input->get("id"));
$this->load->view("templatewith2",$data);
}
public function editvideossubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("videogallery","Video Gallery","trim");
$this->form_validation->set_rules("order","Order","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("url","url","trim");
$this->form_validation->set_rules("image","Image","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editvideos";
$data["videogallery"]=$this->videogallery_model->getdropdown();
$data["title"]="Edit videos";
$data["before"]=$this->videos_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$videogallery=$this->input->get_post("videogallery");
$order=$this->input->get_post("order");
$name=$this->input->get_post("name");
$url=$this->input->get_post("url");
$image=$this->input->get_post("image");
$image=$this->menu_model->createImage();
if($this->videos_model->edit($id,$videogallery,$order,$name,$url,$image)==0)
$data["alerterror"]="New videos could not be Updated.";
else
$data["alertsuccess"]="videos Updated Successfully.";
$data["redirect"]="site/viewvideos?id=".$videogallery;
$this->load->view("redirect2",$data);
}
}
public function deletevideos()
{
$access=array("1");
$this->checkaccess($access);
$this->videos_model->delete($this->input->get("id"));
$video=$this->input->get('videogalleryid');
$data["redirect"]="site/viewvideos?id=".$video;
$this->load->view("redirect2",$data);
}
public function viewcontactus()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewcontactus";
$data["base_url"]=site_url("site/viewcontactusjson");
$data["title"]="View contactus";
$this->load->view("template",$data);
}
function viewcontactusjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_contactus`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_contactus`.`email`";
$elements[1]->sort="1";
$elements[1]->header="Email";
$elements[1]->alias="email";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_contactus`.`name`";
$elements[2]->sort="1";
$elements[2]->header="Name";
$elements[2]->alias="name";
$elements[3]=new stdClass();
$elements[3]->field="`jpp_contactus`.`phone`";
$elements[3]->sort="1";
$elements[3]->header="Phone";
$elements[3]->alias="phone";
$elements[4]=new stdClass();
$elements[4]->field="`jpp_contactus`.`timestamp`";
$elements[4]->sort="1";
$elements[4]->header="Timestamp";
$elements[4]->alias="timestamp";
$elements[5]=new stdClass();
$elements[5]->field="`jpp_contactus`.`comment`";
$elements[5]->sort="1";
$elements[5]->header="Comment";
$elements[5]->alias="comment";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_contactus`");
$this->load->view("json",$data);
}

public function createcontactus()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createcontactus";
$data["title"]="Create contactus";
$this->load->view("template",$data);
}
public function createcontactussubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("phone","Phone","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
$this->form_validation->set_rules("comment","Comment","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createcontactus";
$data["title"]="Create contactus";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$email=$this->input->get_post("email");
$name=$this->input->get_post("name");
$phone=$this->input->get_post("phone");
$comment=$this->input->get_post("comment");
if($this->contactus_model->create($email,$name,$phone,$timestamp,$comment)==0)
$data["alerterror"]="New contactus could not be created.";
else
$data["alertsuccess"]="contactus created Successfully.";
$data["redirect"]="site/viewcontactus";
$this->load->view("redirect",$data);
}
}
public function editcontactus()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editcontactus";
$data["title"]="Edit contactus";
$data["before"]=$this->contactus_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editcontactussubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("name","Name","trim");
$this->form_validation->set_rules("phone","Phone","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
$this->form_validation->set_rules("comment","Comment","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editcontactus";
$data["title"]="Edit contactus";
$data["before"]=$this->contactus_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$email=$this->input->get_post("email");
$name=$this->input->get_post("name");
$phone=$this->input->get_post("phone");
$timestamp=$this->input->get_post("timestamp");
$comment=$this->input->get_post("comment");
if($this->contactus_model->edit($id,$email,$name,$phone,$timestamp,$comment)==0)
$data["alerterror"]="New contactus could not be Updated.";
else
$data["alertsuccess"]="contactus Updated Successfully.";
$data["redirect"]="site/viewcontactus";
$this->load->view("redirect",$data);
}
}
public function deletecontactus()
{
$access=array("1");
$this->checkaccess($access);
$this->contactus_model->delete($this->input->get("id"));
$data["redirect"]="site/viewcontactus";
$this->load->view("redirect",$data);
}
public function viewsubscribe()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewsubscribe";
$data["base_url"]=site_url("site/viewsubscribejson");
$data["title"]="View subscribe";
$this->load->view("template",$data);
}
function viewsubscribejson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`jpp_subscribe`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`jpp_subscribe`.`email`";
$elements[1]->sort="1";
$elements[1]->header="Email";
$elements[1]->alias="email";
$elements[2]=new stdClass();
$elements[2]->field="`jpp_subscribe`.`timestamp`";
$elements[2]->sort="1";
$elements[2]->header="Timestamp";
$elements[2]->alias="timestamp";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `jpp_subscribe`");
$this->load->view("json",$data);
}

public function createsubscribe()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createsubscribe";
$data["title"]="Create subscribe";
$this->load->view("template",$data);
}
public function createsubscribesubmit() 
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createsubscribe";
$data["title"]="Create subscribe";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$email=$this->input->get_post("email");
if($this->subscribe_model->create($email,$timestamp)==0)
$data["alerterror"]="New subscribe could not be created.";
else
$data["alertsuccess"]="subscribe created Successfully.";
$data["redirect"]="site/viewsubscribe";
$this->load->view("redirect",$data);
}
}
public function editsubscribe()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editsubscribe";
$data["title"]="Edit subscribe";
$data["before"]=$this->subscribe_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editsubscribesubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("email","Email","trim");
$this->form_validation->set_rules("timestamp","Timestamp","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editsubscribe";
$data["title"]="Edit subscribe";
$data["before"]=$this->subscribe_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$email=$this->input->get_post("email");
$timestamp=$this->input->get_post("timestamp");
if($this->subscribe_model->edit($id,$email,$timestamp)==0)
$data["alerterror"]="New subscribe could not be Updated.";
else
$data["alertsuccess"]="subscribe Updated Successfully.";
$data["redirect"]="site/viewsubscribe";
$this->load->view("redirect",$data);
}
}
public function deletesubscribe()
{
$access=array("1");
$this->checkaccess($access);
$this->subscribe_model->delete($this->input->get("id"));
$data["redirect"]="site/viewsubscribe";
$this->load->view("redirect",$data);
}

}
?>