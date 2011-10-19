<?php

/**
 * Description of mobile_controller
 * @author snowing
 */

/**
 * @property FilmFast $FilmFast
 * @property UserFast $UserFast
 * @property UserFriends $UserFriends
 * @property UserMessages $UserMessages
 * @property UserWishlist $UserWishlist
 * @property UserDownloadHistory $UserDownloadHistory
 * @property UserTags   $UserTags
 * @property UserRequest $UserRequest
 */
class MainaController extends AppController {

    var $name = 'Maina';
    var $layout = 'newstyle';
    var $viewPath = 'maina';
    var $helpers = array('Html', 'javascript');
    var $components = array();
    var $uses = array('Film', 'Direction', 'News',
        'UserDownloadHistory',
        'UserWishlist', 'UserFriends',
        'FilmFast', 'UserFast', 'UserMessages',
        'UserRequest'
    );
    var $page;
    var $per_page;
    var $page_count = 0;
    var $page_filter;
    var $parent_menu = 0;

    function BeforeFilter() {
        parent::beforeFilter();
        $ajax = 0;
        if (isset($_REQUEST['ajax'])) {
            $this->layout = 'ajax';
            $ajax = 1;
            Configure::write('debug', 0);
        }
        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones, $_SERVER['REMOTE_ADDR'], 1);
        if ($zone)
            $this->ImgPath = Configure::read('Catalog.imgPath');
        else
            $this->ImgPath = Configure::read('Catalog.imgPathInet');
        $page = 1;
        $per_page = 50;
        $ajaxmode = 0;
        if (isset($_GET['page'])) {
            $page = filter_var($_GET['page'], FILTER_VALIDATE_INT);
            if ($ajax)
                $ajaxmode = 1;
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
        if (isset($_GET['filter']))
            $this->page_filter = filter_var($_GET['filter'], FILTER_SANITIZE_STRING);
        else
            $this->page_filter = '';
        View::set('page_filter', $this->page_filter);
        View::set('page_link', '/' . $this->params['controller'] . '/' . $this->params['action']);
        $this->set('ajaxmode', $ajaxmode);
        View::set('blocks_top', '/maina/btop');
        View::set('blocks_right', '/maina/bright');
        View::set('blocks_m_top', '/maina/bmtop');
        View::set('theme_id', 1);
    }

    function BeforeRender() {
//parent::BeforeRender();
        $lang = Configure::read('Config.language');
        $langFix = '';
        if ($lang == _ENG_)
            $langFix = '_' . _ENG_;
        View::set('parent_menu',$this->parent_menu);
        $this->set('lang', $lang);
        $this->set('langFix', $langFix);
        $this->set('authUser', $this->authUser);
        $this->set('imgPath', $this->ImgPath);
        $this->set('page_count', $this->page_count);
        $this->set('page', $this->page);
        $this->set('per_page', $this->per_page);
        $this->set('controller', $this->params['controller']);
    }

    /**
     * Func for updating elements
     */
    function blockupdate($block) {
        $this->layout = 'ajax';
        if (!isset($block))
            return;
        if (isset($_GET['link'])) {
            $link = filter_var($_GET['link'], FILTER_SANITIZE_STRING);
            switch ($block) {
                
            }
        }
    }

    private function CheckForControl($control) {
        /*
          $UserPages = array();
          if (control in $vip) Redirect becomevip
         */
    }

    /* Pages */

    /**
     * Страница "станьте Вип-Пользователем"
     */
    function becomevip() {
        
    }

    /**
     * Страница стартовая
     */
    function index() {
        $this->redirect('/maina/filmlist');
    }

    /**
     * действие для вкладки Личного кабинета "Профиль"
     *
     */
    public function profile() {
        
    }

    public function friends($sub_act='list') {
        $this->autoRender = false;
        switch ($sub_act) {
            case 'add': $this->friend_add();
                break;
            case 'del_req':
                if ($this->friend_del_req())
                    echo "Succecssful delete";
                break;
            case 'out':
                $this->friendlist(2);
                break;
            case 'in':
                $this->friendlist(1);
                break;
            default:
            case 'list':
                $this->friendlist();
                break;
        }
    }

    /**
     * Страница Личных сообщений
     * @param type $sub_act 
     */
    public function im($sub_act='in') {
        //-----  Проверка Созданного сообщения ------//
        if (isset($_POST['send_msg'])) {
            if (isset($_POST['userid'])) {
                $user_id = (int) $_POST['userid'];
                if ($user_id > 0) {
                    if ($this->UserFast->UserExists($user_id)) {
                        $txt = FILTER_VAR($_POST['txt'], FILTER_SANITIZE_STRING);
                        $this->UserMessages->CreateMessageForUser($this->authUser['userid'], $user_id, $txt);
                    }
                }
            }
        }
        //----- /Проверка созданного сообщение/-----//
        //----- Выборка действия ----- //
        //----- Новое, Входящие, Исходящие -----//
        switch ($sub_act) {
            case 'new':
                if (isset($_GET['user'])) {
                    $user_id = (int) $_GET['user'];
                    if ($user_id > 0) {
                        $this->set('user_id', $user_id);
                        $user = $this->UserFast->GetUserById($user_id);
                        if (!empty($user)) {
                            $this->set('user', $user);
                            $this->render('im_new');
                            break;
                        }
                    }
                }
            default:
            case 'in':
                $userMessages = $this->UserMessages->getMessagesForUser($this->authUser['userid'], $this->page, $this->per_page);
                $this->set('userMessages', $userMessages);
                break;
            case 'out':
                $userSent = $this->UserMessages->getMessagesFromUser($this->authUser['userid'], $this->page, $this->per_page);
                $this->set('userSent', $userSent);
                break;
        }
    }

    /**
     * Страница "История скаченного
     */
    public function userhistory() {
        if ($this->authUser) {
            $history = $this->UserDownloadHistory->GetHistoryForUser($this->authUser['userid'], $this->page, $this->per_page);
            $count = $this->UserDownloadHistory->GetHistoryCountForUser($this->authUser['userid']);
            $this->set('history', $history);
            $this->page_count = ceil($count / $this->per_page);
        }
    }

    /**
     * 
     */
    public function tags() {
        if ($_POST) {
            
        }
//$ta
//$tags= $this->UserTags->query('SELECT * FROM `usertags` WHERE user_id='.$this->authUser['id'].' LIMIT 50');
    }

    /**
     * Страница желаний
     * @param type $wish_id 
     */
    public function wishlist($wish_id = null) {
        if ($_POST) {
//$this->UserWishlist->save($data);
        }
        $wish_id = (int) $wish_id;
        if ($wish_id > 0) {
            $wishlist = $this->UserWishlist->GetWishById($wish_id, $this->authUser['userid']);
        }else
            $wishlist = $this->UserWishlist->GetWishForUser($this->authUser['userid']);
        $this->set('wishlist', $wishlist);
    }

    public function userrequest($sub_act='list') {
        $this->autoRender = false;
        switch ($sub_act) {
            default:
            case 'list': $this->user_request_list();
                break;
        }
    }
    
    /**
     * Список фильмов
     * @param type $id  - фильм
     */
    public function filmlist($id=null) {
        $this->parent_menu = 1;//
        if (!$id) {
            $this->per_page = 6; // 6 на страницу
            $this->pageTitle = __('Video catalog', true);
            $cond = array('lic' => 1);
            if ($this->page_filter == '') {
                $films = $this->FilmFast->GetFilms($cond, $this->page, $this->per_page);
                $count = $this->FilmFast->GetFilmsCount($cond);
                $this->page_count = ceil($count / $this->per_page);
                $this->set('films', $films);
            } else {
                //// поисковый запрос
                $films = $this->FilmFast->SearchByTitle($this->page_filter, 1, $this->page, $this->per_page);
                $this->set('films', $films);
                $this->render('search');
            }
        } else { //Просмотр подробной информации о фильме
        }
    }

    

    /**
     *  Community
     */
    public function userlist($id=0) {
        $this->parent_menu = 2;//
        $id = (int) $id;
        if ($id > 0) {
            $user = $this->UserFast->GetUserById($id);
            $friend = $this->UserFriends->isUserFriend($this->authUser['userid'], $id);
            $this->set('user', $user);
            $this->set('friend', $friend);
            $this->render('userview');
        } else {
            if ($this->page_filter == '') {
                $userlist = $this->UserFast->GetUserList(array(), $this->page, $this->per_page);
                $count = $this->UserFast->GetUserListCount();
                $this->page_count = ceil($count / $this->per_page);
            } else {
                $userlist = $this->UserFast->SearchUserByName($this->page_filter, $this->per_page);
            }
            $this->set('users', $userlist);
        }
    }

    /*
     *  Friends actions
     */

    private function friendlist($type=0) {
        if ($this->authUser) {
            if (isset($_POST['friend'])) {
                $fname = FILTER_VAR($_POST['friend'], FILTER_SANITIZE_STRING);
            }
            if ($type == 0) {
                $myfriends = $this->UserFriends->getUserFriends($this->authUser['userid']);
                $this->set('friends', $myfriends);
            }
            if ($type == 2) {
                $myOutRequests = $this->UserFriends->getOutRequestsForUser($this->authUser['userid']);
                $this->set('friendsOutReq', $myOutRequests);
            }
            if ($type == 1) {
                $myInRequests = $this->UserFriends->getInRequestsForUser($this->authUser['userid']);
                $this->set('friendsInReq', $myInRequests);
            }
            $this->render('friendlist');
        }
    }

    private function friend_add() {
        if (isset($_POST['userid'])) {
            $user_id = (int) $_POST['userid'];
            if ($user_id > 0) {
                $user = $this->UserFast->GetUserById($user_id);
                $is_requested = $this->UserFriends->isRequestedAsFriend($this->authUser['userid'], $user_id);
                if (($user[0]['user']['userid'] == $user_id) && (!$is_requested)) {
                    if ($this->UserFriends->RequestFriend($this->authUser['userid'], $user_id))
                        return true;
                    return false;
                }
            }
        }
        if (isset($_GET['user'])) {
            $user_id = (int) ($_GET['user']);
            if ($user_id > 0) {
                $user = $this->UserFast->GetUserById($user_id);
                $is_friend = $this->UserFriends->isUserFriend($this->authUser['userid'], $user_id);
                if ($is_friend)
                    $this->set('is_friend', $is_friend);
                else {
                    $is_requested = $this->UserFriends->isRequestedAsFriend($this->authUser['userid'], $user_id);
                    if ($is_requested)
                        $this->set('is_requested', $is_requested);
                }
                if ($user[0]['user']['userid'] == $user_id) {
                    $this->set('user', $user);
                    $this->render('friend_add');
                }
            }
        }
    }

    private function friend_del_req() {
        if (isset($_GET['userid'])) {
            $user_id = (int) ($_GET['userid']);
            if ($user_id > 0) {
                $user = $this->UserFast->GetUserById($user_id);
                $is_friend = $this->UserFriends->isUserFriend($this->authUser['userid'], $user_id);
                $is_requested = $this->UserFriends->isRequestedAsFriend($this->authUser['userid'], $user_id);
                if ((!$is_friend) && ($is_requested)) {
                    $this->UserFriends->RequestDelete($this->authUser['userid'], $user_id);
                }
            }
        }
    }

    /**
     *  UserRequest
     */
    private function user_request_list() {
        $lst = $this->UserRequest->GetRequestForUser($this->authUser['userid']);
        $this->render('user_request_list');
    }

    private function user_request_add() {
        
    }

    private function user_request_del() {
        
    }

    public function user_favorite() {
//$ufavorite = $this->UserFavorite->query('Select * FROM `userfavorite` WHERE user_id= '.$this->authUser['id'].' LIMIT 20');
//$this -> set('ufavorite',$ufavorite);
    }

}