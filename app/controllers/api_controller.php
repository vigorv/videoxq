<?php

/**
 * Description of api_controller
 *
 * @author snowing
 */
function quot_make(&$item) {
    if (is_array($item))
        array_walk($item, 'quot_make');
    else if (is_string($item)) {
        return $item = htmlspecialchars($item);
    }
}

class ApiController extends Controller {

    var $name = 'Api';
    var $layout = 'xml_api';
    var $viewPath = 'api';
    var $helpers = array('Html', 'javascript', 'Xml');
    var $components = array();
    var $uses = array('Film', 'Genres');
    var $imgPath;

    function BeforeFilter() {
        parent::BeforeFilter();
//        $this->autoRender = false;

        $lang = Configure::read('Config.language');
        $this->langFix = '';
        if ($lang == _ENG_)
            $this->langFix = '_' . _ENG_;

        $this->set('lang', $lang);
        $this->set('langFix', $this->langFix);
        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones, $_SERVER['REMOTE_ADDR'], 1);
        if ($zone)
            $this->imgPath = Configure::read('Catalog.imgPath');
        else
            $this->imgPath = Configure::read('Catalog.imgPathInet');
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
print_r ($data['genres']);
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getitems() {
        $order = array();
        $param = array();

        $this->Film->contain();
        $param['conditions'] = array('is_license' => 1, 'active' => 1, 'FilmPicture.type' => 'smallposter');
        $param['recursive'] = 0;
        $param['joins'] = array(array('table' => 'film_pictures', 'alias' => 'FilmPicture', 'type' => 'LEFT', 'conditions' => 'FilmPicture.film_id = Film.id'));
        if (isset($_GET['direction']) && ($_GET['direction'] == 'ASC')) {
            $direction = 'ASC';
        } else
            $direction = 'DESC';
        if (isset($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
            if ($offset > 0)
                $param['offset'] = $offset;
        }

        if (isset($_GET['sort_order'])) {
            $sort = (int) $_GET['sort_order'];
            switch ($sort) {
                case 1: {
                        $order = 'Film.created';
                        break;
                    }
                case 2: {
                        $order = 'Film.year';
                    }
                case 3: {
                        break;
                    }
            }
            $param ['order'] = array($order => $direction);
        } //else $cond['order']
        if (isset($_GET['limit'])) {
            $i = (int) $_GET['limit'];
            if ($i < 50) {
                $param['limit'] = $i;
            } else
                $param['limit'] = 50;
        } else
            $param['limit'] = 30;

        if (isset($_GET['title'])) {
            $title = FILTER_VAR($_GET['title'], FILTER_SANITIZE_STRING);
            $param['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $param['sphinx']['index'] = array('films'); //ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ
            $param['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $param['search'] = $title;
        }
        $count = $this->Film->find('count', $param);
        if ($count)
            $data['Count'][]['count']['count'] = $count;

        $param['fields'] = array('DISTINCT id', 'title', 'year', 'imdb_rating', 'FilmPicture.file_name');
        $data['Films'] = $this->Film->find('all', $param);
        foreach ($data['Films'] as &$film) {
            $film['poster']['href']= $this->imgPath . $film['FilmPicture']['file_name'];
            unset($film['FilmPicture']);
        }
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getfulliteminfo($filmId=0) {        
        $filmId = (int) $filmId;
        if ($filmId <= 0) {            
        } else {
            $this->Film->contain();
            $params = array();
            $params['conditions'] = array('Film.is_license' => 1, 'Film.active' => 1, 'Film.id' => $filmId);
            $params['fields'] = array('Film.id', 'Film.title', 'Film.title_en', 'Film.year', 'Film.imdb_rating', 'Film.description', 'FilmPicture.file_name');
            $params['joins'] = array(array('table' => 'film_pictures', 'alias' => 'FilmPicture', 'type' => 'LEFT', 'conditions' => 'FilmPicture.film_id = Film.id'));
            $params['limit'] = 1;
            $data['Films'] = $this->Film->find('all', $params);
            foreach ($data['Films'] as &$film) {
                $film['poster']['href'] = $this->imgPath . $film['FilmPicture']['file_name'];
                unset($film['FilmPicture']['file_name']);
                unset($film['FilmPicture']);
            }
        }

        if (empty($data['Films'])){
            $data['errors'][]['errors']['desc']='No Film';
            unset($data['Films']);
        }
        array_walk($data, 'quot_make');
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
