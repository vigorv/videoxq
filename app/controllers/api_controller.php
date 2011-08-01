<?php

/**
 * Description of api_controller
 *
 * @author snowing
 */
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
            $this->ImgPath = Configure::read('Catalog.imgPath');
        else
            $this->ImgPath = Configure::read('Catalog.imgPathInet');
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
        $data['Film'] = $this->Film->find('all', $param);
        foreach ($data['Film'] as &$film) {
            print_r($data);
            $film['poster'] = $this->imgPath . $film['FilmPicture']['file_name'];
            unset($film['FilmPicture']['file_name']);
        }
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getFullItemInfo($filmId) {
        $param = array();
        $this->Film->recursive = 0;
        $this->Film->contain(array(
            'conditions'=>array('is_license' => 1, 'active' => 1),
            'FilmType',
            'Genre',
            'Thread',
            'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
            'Country',
            'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
            'MediaRating',
                )
        );
        $film = $this->Film->read(null, $filmId);
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
