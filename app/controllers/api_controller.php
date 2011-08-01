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
    var $helpers = array('Html', 'javascript', 'Xml');
    var $components = array();
    var $uses = array('Film', 'Genres');

    function BeforeFilter() {
        parent::BeforeFilter();
//        $this->autoRender = false;
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
            case 0: {
                    $res = $this->GetStartMenu();
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
        $this->set('xml_data', $res);
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

    private function GetStartMenu() {
        $data = array();
        $data['vxq']['item']['cname'] = 'Video';
        $data['vxq']['item']['type'] = 'menu';

        $data['vxq'][1]['cname'] = 'Profile';
        $data['vxq'][1]['type'] = 'item';

        $data['vxq'][2]['cname'] = 'Logout';
        $data['vxq'][2]['type'] = 'action';
        return $data;
    }

    private function GetItemVideo($item_id) {
        
    }

    function getserviceinfo() {
        $data = array();
        $data['sort_order'][]['sort'] = array('id' => 1, 'caption' => 'По дате добавления');
        $data['sort_order'][]['sort'] = array('id' => 2, 'caption' => 'По году выпуска');
        $data['genres'] = $this->Genres->query("SELECT id,title FROM genres");

        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getfulliteminfo() {
        
    }

    function getitems() {
        $order = array();
        $param = array();
        $param['conditions']['Film.is_license'] = 1;
        $param['conditions']['Film.active'] = 1;
        if (isset($this->passedArgs["sort_order"])) {
            $param ['order'] = array($this->passedArgs["sort_order"] => $this->passedArgs["direction"]);
        } //else $cond['order']
        if (isset($this->passedArgs["limit"])) {
            $i = int($this->passedArgs["limit"]);
            if ($i < 50) {
                $param ['limit'] = $this->passedArgs["limit"];
            } else
                $param ['limit'] = 50;
        } else
            $param ['limit'] = 30;
        $data['Film'] = $this->Film->find('all', $param);
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getTop10() {
        
    }

    function addtofavorites() {
        
    }

    function removefromfavorites() {
        
    }

    function getfavorites() {
        
    }

}

?>
