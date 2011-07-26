<?php

/**
 * Description of api_controller
 *
 * @author snowing
 */
class ApiController extends Controller {

    var $name = 'Api';
    var $layout = 'ajax';
    var $viewPath = 'api';
    var $helpers = array('Html', 'javascript', 'XML');
    var $components = array();
    var $uses = array('Direction', 'News');

    function BeforeFilter() {
        parent::BeforeFilter();
        $this->autoRender = false;
    }

    function Login() {
        //$user->login();
    }

    function Logout() {
        //$user->logout();
    }

    function GetMenu($cat_id=null) {
        if ($cat_id == null)
            $cat_id = 0;
        switch ($cat_id) {
        case 0:{
            $res = GetStartMenu();            
            break;
        }
        case 'video': {
            //$res = GetVideoCat();
            break;
        }
        case ' videocat': {
            //$res=GetVideoCatItems();
            break;
        }
        default: {
            break;
        }
    }
    if ($res)
    echo $this->Xml->serialize($res);
}


function GetItem() {
    $item_type = filter_var($_GET['item_type'], FILTER_SANITIZE_STRING);
    $item_id = filter_var($_GET['item_id'], FILTER_VALIDATE_INT);
    switch ($item_type) {
        case 'video': {
                $result = GetItemVideo($item_id);
            }
        case 'profile': {
                //$res=GetProfileInfo();
            }
        default: {
                
            }
    }
    }

    private function GetStartMenu(){
        $data=array();
        $data['vxq'][0]['name']='Video';
        $data['vxq'][0]['type']='menu';
        
        $data['vxq'][1]['name']='Profile';
        $data['vxq'][1]['type']='Item';
        
        $data['vxq'][2]['name']='Logout';
        $data['vxq'][2]['type']='Action';
        
        
    }
    
    
    private  function GetItemVideo($item_id) {
        
    }

}

?>
