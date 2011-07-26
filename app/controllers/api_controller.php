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
    var $helpers = array('Html', 'javascript');
    var $components = array();
    var $uses = array('Direction', 'News');

    
    
    function Login() {
        
    }

    function Logout() {
        
    }

    function GetCategories($cat_id=null) {
        if ($cat_id == null)
            $cat_id = 0;
        
        
    }
    
    function GetItem() {
        $item_type = filter_var($_GET['item_type'],FILTER_SANITIZE_STRING);
        $item_id = filter_var($_GET['item_id'],FILTER_VALIDATE_INT);       
        switch ($item_type){
            case 'video':{
                
            }
            default:{
                
            }
        }
        
    }
    
    private function GetItemVideo($item_id){
        
    }
    

}

?>
