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
    var $helpers = array('Html', 'javascript', 'tvvision', 'tvIcons');
    var $components = array('RequestHandler');
    var $uses = array('Film', 'Direction', 'News', 'Favorite',
        'UserDownloadHistory',
        'UserWishlist', 'UserFriends',
        'FilmFast', 'UserFast', 'UserMessages',
        'UserRequest', 'UserOption',
        'Pmsg'

    );
    var $page;
    var $per_page;
    var $page_count = 0;
    var $page_filter;
    var $parent_menu = 0;

    /**
     * модель настроек пользователя
     *
     * @var UserOption
     */
    public $UserOption;

    /**
     * модель личных сообщений форума
     *
     * @var Pmsg
     */
    public $Pmsg;

    function BeforeFilter() {       
        parent::beforeFilter();
        $ajax = 0;
        if (isset($_REQUEST['ajax'])) {
            $this->layout = 'ajax';
            $ajax = 1;
            Configure::write('debug', 0);
        }
        if (empty($this->authUser['userid']))
        {
            $this->redirect("/users/login");
        }
        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones, $_SERVER['REMOTE_ADDR'], 1);
        if ($zone)
            $this->ImgPath = Configure::read('Catalog.imgPath');
        else
            $this->ImgPath = Configure::read('Catalog.imgPathInet');
        $page = 1;
        $per_page = 0;//ПОЛУЧИМ ИЗ НАСТРОЕК
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

        {
            $this->per_page = 10;
            if (!empty($this->userOptions['Profile.itemsPerPage']))
            	$this->per_page = $this->userOptions['Profile.itemsPerPage'];
        }

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
        View::set('blocks_m_im', '/maina/bmim');

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
/**
		$msg = 'сообщение для Спарка от ' . date('y.m.d H:i:s');
		//$this->Pmsg->sendMessage('vanoveb', 'spark', $msg, $msg . ' ' . $msg);
		$this->Pmsg->setMessageRead(26);
echo '<h3>Исходящие</h3>';
    	$outMsgs = $this->Pmsg->getOutMessages($this->authUser['userid']);
    	foreach ($outMsgs as $m)
    	{
    		echo $m['Pmsg']['message'] . '<br /><br />';
    	}
echo '<h3>Входящие</h3>';
    	$inMsgs = $this->Pmsg->getInMessages($this->authUser['userid']);
    	foreach ($inMsgs as $m)
    	{
    		echo $m['Pmsg']['message'] . '<br /><br />';
    	}
exit;
//*/
        $this->redirect('/maina/profile');
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
    public function im($sub_act='') {
        if ($sub_act == '#') {$sub_act='';}
        if (!empty($this->passedArgs['page'])){
            $this->page = $this->passedArgs['page'];
        }
        //$this->per_page = 6;
        //если что то отсылали с подметодом new, смотрим чего там шлют
        if (!empty($_POST) && $sub_act=='new'){
            //если все поля заполнены
            if (isset($_POST['title']) && isset($_POST['msg']) && $_POST['to_user_name']&&
                      $_POST['title'] && $_POST['msg'] && $_POST['to_user_name']){

                $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
                $to_user_name = filter_var($_POST['to_user_name'], FILTER_SANITIZE_STRING);
                $from_user_name = $this->authUser['username'];
                if ($this->Pmsg->sendMessage($from_user_name, $to_user_name, $title, $msg))
                    $result_msg = 'Сообщение для пользователя '. $to_user_name .' успешно отправлено';
                else
                    $result_msg = 'Ошибка! Пользователя с имененем '. $to_user_name .' не существует.';

                    //установим сообщение и редирект!
                    $this->Session->setFlash($result_msg, true);
                    //$this->redirect(array('action'=>'im'));
                    $sub_act='in';

            }
            else{
                //если не все поля заполнены, то сообщим об этом
                $result_msg = 'Ошибка! Заполнены не все поля';
                $this->Session->setFlash($result_msg, true);
                //далее снова придется выводить форму ввода ((((
                //пожалеем юзера, сохраним его введенные данные в форме ))))
                $data = array(
                    'to_user_name' => $_POST['to_user_name'],
                    'title' => $_POST['title'],
                    'msg' => $_POST['msg']
                    );
                $this->set('data', $data);
            }
        }
        //сообщение послано (если не было ошибок ввода)!!!

        //если был ajax-запрос, то дадим знать об этом вьюхе
        $this->set('isAjax', $this->RequestHandler->isAjax());
        //но у нас может быть ajax-запрос в ajax-запросе - при клике "messages"
        //в верхнем меню (он тоже ajax'овый), поэтому что бы отсечь это, считаем
        //его как не ajax-запрос, для нашей локальной менюшки, и установим
        //подметод по умолчанию -> "in"

        if (!$sub_act && !empty($this->userOptions['Profile.im_subact'])){
            $this->set('isAjax', false);
            $sub_act = $this->userOptions['Profile.im_subact'];
            if ($sub_act == '#') {$sub_act='';}
        }
        elseif(!$sub_act){
            $this->set('isAjax', false);
            $sub_act = 'in';
        }
        //еще вьюхе надо знать какой у нас подметод, для того даем ей еще
        //одну переменную
        $this->set('sub_act', $sub_act);

        //выведем нужную вьюху в зависимости от "подметода"
        //подметоды in и out пока не рассматриваем
        switch ($sub_act) {
            case 'fulldel':
                //удаление текущего сообщения
                $result = $this->Pmsg->delInMessages($this->authUser['userid'],$msg_id_arr);
                if ($result){
                    $this->Session->setFlash('Сообщение удалено', true);
                }
                //установим $sub_act для вывода страницы входящих сообщений
                $sub_act = 'in';
                break;

            case 'del':
                //удаление сообщения
                if (!empty($this->passedArgs['msgid'])){
                    $msgid = intval($this->passedArgs['msgid']);
                }
                //если id сообщения указан то удаляем его
                if ($msgid){
                    $result = $this->Pmsg->delMessage($this->authUser['userid'],$msgid);
                    if ($result){
                        $this->Session->setFlash('Сообщение удалено', true);
                    }
                }
                //установим $sub_act для вывода страницы входящих сообщений
                $sub_act = 'in';
                break;

            case 'indel':
                //удаление входящих сообщений
                if (!empty($_POST['msg_id_list']) && $_POST['msg_id_list']){
                    //список id сообщений для удаления
                    $msg_id_list = $_POST['msg_id_list'];
                    $result = $this->Pmsg->delInMessages($this->authUser['userid'],$msg_id_list);
                    if ($result){
                        $this->Session->setFlash('Указанные входящие сообщения удалены', true);
                    }
                }
                //установим $sub_act для вывода страницы входящих сообщений
                $sub_act = 'in';
                break;

            case 'inclear':
                //удаление всех входящих сообщений
                $result = $this->Pmsg->delAllInMessages($this->authUser['userid']);
                if($result){
                    $this->Session->setFlash('Все входящие сообщения удалены', true);
                }
                //установим $sub_act для вывода страницы входящих сообщений
                $sub_act = 'in';
                break;

            case 'outdel':
                //удаление исходящих сообщений
                if (!empty($_POST['msg_id_list']) && $_POST['msg_id_list']){
                    //список id сообщений для удаления
                    $msg_id_list = $_POST['msg_id_list'];
                    $result = $this->Pmsg->delOutMessages($this->authUser['userid'],$msg_id_arr);
                    if ($result){
                        $this->Session->setFlash('Указанные исходящие сообщения удалены', true);
                    }
                }
                //установим $sub_act для вывода страницы исходящих сообщений
                $sub_act = 'out';
                break;

            case 'outclear':
                //удаление всех исходящих сообщений
                $result = $this->Pmsg->delAllOutMessages($this->authUser['userid']);
                if ($result){
                    $this->Session->setFlash('Все исходящие сообщения удалены', true);
                }
                //установим $sub_act для вывода страницы исходящих сообщений
                $sub_act = 'out';
                break;

            case 'new':
                //форма отправки нового сообщения
                $this->render('im_new');
                break;

            case 'full':
                //вывод полного выбранного содержимого сообщения
                if (!empty($this->passedArgs['msgid'])){
                    $msgid = intval($this->passedArgs['msgid']);
                }
                //если id сообщения указан то попробуем показать его
                if ($msgid){
                    //пометим сообщение как прочитанное
                    $this->Pmsg->setMessageRead($msgid);
                    //и дадим прочитать юзеру
                    $message = $this->Pmsg->getMessageFull($this->authUser['userid'], $msgid);
                    $this->set('message', $message);
                    $this->render('im_full');
                }
                else{
                    $sub_act = 'in';
                }
                break;
            case 'check':
                //проверка на новые сообщения
                $new_msg_id = $this->Pmsg->checkNewMessages($this->authUser['userid']);
                $this->set('new_msg_id', $new_msg_id);
                //вывод входящих сообщений (по умолчанию)
                $messages = $this->Pmsg->getInMessages($this->authUser['userid'], $this->page, $this->per_page);
                $this->set('messages', $messages);
                $this->render('im_check');
                break;
            case 'in':
            case 'out':
                break;
            default:
                //по умолчанию вывод входящих сообщений
                $sub_act = 'in';
                break;
        }

        //если подметод = "входящие" или "исходящие" (in/out), то подготовим
        //данные для пагинации и выведем нужную вьюху
        if($sub_act=='in' || $sub_act=='out'){

            //установим переменную во вьюхе еще раз, так как могла поменяться,
            //при удалении сообщений
            $this->set('sub_act', $sub_act);
            switch ($sub_act) {
                case 'out':
                    //вывод исходящих сообщений
                    $count_messages = $this->Pmsg->getCountOutMessages($this->authUser['userid']);
                    $this->set('count_messages', $count_messages);
                    //если задали недопустимо высокое значение page, сделаем его
                    //максимально допустимым
                    if ($this->page > (floor( $count_messages / $this->per_page)+1)){
                        $this->page = floor( $count_messages / $this->per_page)+1;
                    }
                    $this->page_count = ceil($count_messages / $this->per_page);
                    $im_pagination = array(
                        'page'=>$this->page,
                        'per_page'=>$this->per_page,
                        'page_count'=>$this->page_count
                    );
                    $this->set('im_pagination', $im_pagination);
                    $messages = $this->Pmsg->getOutMessages($this->authUser['userid'], $this->page, $this->per_page);
                    $this->set('messages', $messages);
                    $this->render('im_out');
                    break;

                case 'in':
                    //проверка на сущствование новых каждые 30 секунд
                    $new_msg_id = $this->Pmsg->checkNewMessages($this->authUser['userid']);
                    $this->set('new_msg_id', $new_msg_id);
                    //вывод входящих сообщений (по умолчанию)
                    $count_messages = $this->Pmsg->getCountInMessages($this->authUser['userid']);
                    $this->set('count_messages', $count_messages);
                    //если задали недопустимо высокое значение page, сделаем его
                    //максимально допустимым
                    if ($this->page > (floor( $count_messages / $this->per_page)+1)){
                        $this->page = floor( $count_messages / $this->per_page)+1;
                    }
                    $this->page_count = ceil($count_messages / $this->per_page);
                    $im_pagination = array(
                        'page'=>$this->page,
                        'per_page'=>$this->per_page,
                        'page_count'=>$this->page_count
                    );
                    $this->set('im_pagination', $im_pagination);
                    $messages = $this->Pmsg->getInMessages($this->authUser['userid'], $this->page, $this->per_page);
                    $this->set('messages', $messages);
                    $this->render('im_in');
                    break;
                default:
                    break;
            }
        }


    }
//------------------------------------------------------------------------------
    /**
     * Сохранить изменения в опции пользователя
     *
     * данные передаются методом POST
     * структура данных:
     * 		$_POST['optionName']	- название опции (имя)
     * 		$_POST['optionValue']	- значение опции
     *
     */
    public function saveoption()
    {
    	$result = '';
    	if (!empty($_POST['optionName']) && !empty($this->authUser['userid']))
    	{
    		$name = $_POST['optionName'];
    		$val = (empty($_POST['optionValue']) ? '' : $_POST['optionValue']);
    		$this->userOptions[$name] = $val;
			$this->Session->write('Profile.userOptions', $this->userOptions);
    		$this->UserOption->setOptions($this->authUser['userid'], $this->userOptions);
    		$result = 'ok';
    	}
    	$this->set('result', $result);
    }

    /**
     * Отображение фильма в телевизоре
     */
    public function filmview($filmId) {
		App::import('Controller','Media');
		//App::import('Controller', 'MediaController', true, array('d:/vano/home/videoxq-wc2/www/app/controllers'));
 	 	$media = new MediaController;
		$media->constructClasses();
		$this->viewPath = 'media/films';

		$this->view = 'view.ctp';
		$media->render('view');
		//$media->view($filmId);
    }

    /**
     * Страница "История скаченного
     */
    public function userhistory() {
        if ($this->authUser) {

        	if (!empty($this->page))
        	{
        		//$this->Session->write('Profile.historyCurrentPage', $this->page);
        	}
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


    /*
     * Избранное - вывод избранных пользователем фильмов
     *
     */
    public function favorites(){
        $user_id = $this->authUser['userid'];
        //достанем данные по избранным фильмам
        $favorites_data = array();
        $fav_data = $this->Favorite->getFavoritesFilmsInfo($user_id);
        //максимальное кол-во актеров для вывода
        $max_num_of_actors = 4;
        //начальный url к постерам
        $poster_img_path = Configure::read('Catalog.imgPath');
        //дополним эти данные более подробными, из модели Films
        foreach ($fav_data as $row){
            //$favorites_data['Favorite']['']
            $film_data = $this->Film->getShortFilmInfo($row['Favorite']['film_id']);
            //pr ($film_data);
            //формируем массив для вывода инфы о фильмах
            $film_director = '';
            $actors = array();
            $poster = '';
            //формируем массив путей картинок для постеров
            //точнее пока просто выбираем один постер :)
            if (!empty($film_data['FilmPicture']) && $film_data['FilmPicture']){
                foreach($film_data['FilmPicture'] as $pic_data){
                   $poster =  $poster_img_path.$pic_data['file_name'];
                   break;
                }
            }

            //формируем массив актеров
            if (!empty($film_data['Person']) && $film_data['Person']){
                foreach($film_data['Person'] as $person_data){
                        //проверим, не режисер ли нам попался? если он самый, то
                        //запомним его имя ))))
                        if ($person_data['FilmsPerson']['profession_id'] == 1){
                            $film_director = $person_data['Person']['name'];
                        }
                        //а если это актер, то в список его... в отдельный!!!!
                        elseif ($person_data['FilmsPerson']['profession_id'] == 3 && count($actors)<= $max_num_of_actors){
                            $actors[] =  $person_data['Person']['name'];
                        }
                    //4х актеров и режисера вполне достаточно!... еще бы актрис... но нельзя :)))
                    if(count($actors)>= $max_num_of_actors && $film_director) break;
                }
            }

            $favorites_data[] = array(
                'id'   => $film_data['Film']['id'],
                'year' => $film_data['Film']['year'],
                'film_name_rus'   => $film_data['Film']['title'],
                'film_name_org' => $film_data['Film']['title_en'],
                'director'  => $film_director,
                'actors'  => $actors,
                'poster'  => $poster
                );
        }

        $this->set('favorites_data',$favorites_data);
    }
    
    public function Check_message() {
        
    }
}