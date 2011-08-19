<?php

/**
 * Description of api_controller
 *
 * @author snowing
 */

/**
 * @property FilmFast $FilmFast
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
    var $uses = array('Film', 'Genres', 'FilmFast','Server');
    var $imgPath;
    var $page;
    var $per_page;

    function BeforeFilter() {
        parent::BeforeFilter();
//        $this->autoRender = false;

        $geoInfo = array();
        $geoInfo = $this->Session->read('geoInfo');
        $ip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        if (!empty($geoInfo) && $geoInfo['ip'] <> $ip) {
            $geoInfo = array(); //ЕСЛИ ИЗМЕНИЛСЯ АДРЕС ГЕО ДАННЫЕ ОПРЕДЕЛЯЕМ ПО НОВОЙ
        }
        $servers = Cache::read('servers', 'block');
        if (empty($servers)) {
            $servers = $this->Server->findAll(array('Server.is_active' => 1), null, 'Server.priority DESC');
            Cache::write('servers', $servers, 'block');
        }
        $configServers = array();
        foreach ($servers as $server) {
            $configServers[] = array(
                'server' => 'http://' . $server['Server']['addr'] . '/',
                'share' => 'http://' . $server['Server']['addr'] . '/',
                'letter' => $server['Server']['letter'],
                'zone' => $server['Server']['zone'],
            );
        }
        Configure::write('Catalog.downloadServers', $configServers); //Эмулируем старый способ
//

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
        $page = 1;
        $per_page = 50;
        if (isset($_GET['page'])) {
            $page = filter_var($_GET['page'], FILTER_VALIDATE_INT);
        }
        if ($page > 0)
            $this->page = $page;
        else
            $this->page = 1;
        if (isset($_GET['per_page']))
            $per_page = filter_var($_GET['per_page'], FILTER_VALIDATE_INT);
        if (($per_page > 0) && ($per_page < 100))
            $this->per_page = $per_page;
        else
            $this->per_page = 50;
    }

    /*
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
     */

    function getserviceinfo() {
        $data = array();
        $data['sort_order'][]['sort'] = array('id' => 1, 'caption' => 'По дате добавления');
        $data['sort_order'][]['sort'] = array('id' => 2, 'caption' => 'По году выпуска');
        /*   $genres = $this->Genres->query("SELECT id,title FROM genres");
          //print_r ($data['genres']);
          foreach ($genres as &$genre) {
          if (isset($genre['genres']['id']) && $genre['genres']['id']) {
          $genre_count = $this->Genres->query("SELECT COUNT('films_genres.id') as count from films_genres
          INNER JOIN films ON films_genres.film_id = films.id
          WHERE films_genres.genre_id =" . $genre['genres']['id']." AND films.is_license=1");
          $genre_count = (int) $genre_count[0][0]['count'];
          if (($genre_count > 0)) {
          $genre['genres']['count'] = $genre_count;
          $data['genres'][] = $genre;
          }
          }
          } */
        $data['genres'] = $this->FilmFast->GetFullGenresList();
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getitems() {
        $param = array();
        $param['lic'] = 1;
        if (isset($_GET['genre_id'])) {
            $genre_id = (int) $_GET['genre_id'];
            if ($genre_id > 0)
                $param['genre_id'] = $genre_id;
        }
        $param['fields'] = 'Film.id, Film.title, Film.title_en,Film.year, Film.imdb_rating , FilmPicture.file_name';
        if (isset($_GET['direction']) && ($_GET['direction'] == 'ASC'))
            $param['direction'] = 'ASC';
        else
            $param['direction'] = 'DESC';
        if (isset($_GET['sort_order'])) {
            $sort = (int) $_GET['sort_order'];
            switch ($sort) {
                case 1: {
                        $param['order'] = 'Film.created';
                        break;
                    }
                case 2: {
                        $param['order'] = 'Film.year';
                        break;
                    }
                default: {
                        
                    }
            }
        }
        if (isset($_GET['title'])) {
            $param['title'] = FILTER_VAR($_GET['title'], FILTER_SANITIZE_STRING);
        }
        $count = $this->FilmFast->GetFilmsCount($param);
        if ($count)
            $data['Count'][]['count']['count'] = $count;
        $data['Films'] = $this->FilmFast->GetFilmsA($param, $this->page, $this->per_page);
        foreach ($data['Films'] as &$film) {
            $film['poster']['href'] = $this->imgPath . $film['FilmPicture']['file_name'];
            unset($film['FilmPicture']);
        }
        $this->set('xml_data', $data);
        $this->render('api_view');
    }

    function getfulliteminfo($filmId=0) {
        $filmId = (int) $filmId;
        if ($filmId <= 0) {
            
        } else {

            /*    $this->Film->contain();

              $params = array();
              $params['conditions'] = array('Film.is_license' => 1, 'Film.active' => 1, 'Film.id' => $filmId);
              $params['fields'] = array('Film.id', 'Film.title', 'Film.title_en', 'Film.year', 'Film.imdb_rating', 'Film.description', 'FilmPicture.file_name');
              $params['joins'] = array(array('table' => 'film_pictures', 'alias' => 'FilmPicture', 'type' => 'LEFT', 'conditions' => 'FilmPicture.film_id = Film.id'));
              $params['limit'] = 1;
              $data['Films'] = $this->Film->find('all', $params);
              foreach ($data['Films'] as &$film) {

              }
              }
             */
            $params = array();
            $params['lic'] = 1;
            $params['variant'] = 2;
            $params['fields'] = '
                Film.id, Film.title, Film.title_en,
                Film.year, Film.imdb_rating,
                Film.description, FilmPicture.file_name,
                FilmFile.file_name,Film.dir';
            $data['Films'] = $this->FilmFast->GetFilmA($filmId, $params);
        }

        /*
         */
        //       pr ($data['Films']);
        if (empty($data['Films'])) {
            $data['errors'][]['errors']['desc'] = 'No Film';
            unset($data['Films']);
        } else {
            $data['Films'][0]['poster']['href'] = $this->imgPath . $data['Films'][0]['FilmPicture']['file_name'];
            unset($data['Films'][0]['FilmPicture']['file_name']);
            unset($data['Films'][0]['FilmPicture']);
            $data['Films'][0]['poster']['url'] = Film::set_input_server($data['Films'][0]['Film']['dir']) . '/' . $data['Films'][0]['FilmFile']['file_name'];
            unset($data['Films'][0]['FilmFile']['file_name']);
            unset($data['Films'][0]['FilmFile']);
            unset($data['Films'][0]['Film']['dir']);
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

}

?>
