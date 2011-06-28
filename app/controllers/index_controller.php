 <?php
class IndexController extends AppController {

    var $name = 'Index';
    var $helpers = array('Html', 'Form', 'TagCloud', 'Ajax');
    var $components = array('Captcha','Cookie');
    //var $viewPath = 'media/films';
    var $uses = array('Post' , 'Blog', 'Film', 'News', 'Direction');

    /**
     * модель нововстей
     *
     * @var AppModel
     * */
    var $News;

    function index()
    {
    	$this->redirect('/media');
    	return;

    	App::import('Model');
		$this->paginate['Post'] = array(
        									'conditions' => array('or' => array('Post.access' => 'public',
                                                              'Post.user_id' => $this->authUser['userid']))
        								,'limit' => 3
        								);

	    $this->set('posts', $this->paginate('Post'));
    	if (!$maxId = Cache::read('Catalog.film_max_id', 'searchres'))
    	{
			$maxId = $this->Film->query('select id from films order by id desc limit 1');
			Cache::write('Catalog.film_max_id', $maxId, 'searchres');
    	}
		$maxId = $maxId[0]['films']['id'];
	    $this->set('forUMaxId', $maxId);
    }

    /**
     * получить данные о фильме для блока на главной
     *
     * @param integer $id	- идентификатор фильма
     * @param sting $detail	- детализация ('poster' - получить код для постера; 'all' - полная информация)
     */
    function filminfo($site = 'videoxq', $id = null, $detail = 'poster')
    {
		Configure::write('debug', 0);
		$memcache_obj = memcache_connect('localhost', 11211);
		$posts = memcache_get($memcache_obj, $site . '_randposts');

    	$this->layout = 'ajax';
    	switch ($site)
    	{
    		case "videoxq":
				if (!empty($id))
				{
					if (!$film = Cache::read('Catalog.film_view_' . $id,'media'))
			        {
				        $this->Film->recursive = 0;
				        $this->Film->contain(array('FilmType',
				                                     'Genre',
				                                     'Thread',
				                                     'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
				                                     'Country',
				                                     'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
				                                     'MediaRating',
				                                  )
				                             );
				        $film = $this->Film->read(null, $id);
					    Cache::write('Catalog.film_view_' . $id, $film,'media');
			        }
				}
				else
				{
					if (($detail == 'poster') && ($posts))
					{
						$posts = unserialize($posts);
						$postid = mt_rand(0, count($posts) - 1);
						$film = $posts[$postid];
						$film['FilmPicture'][0] = $film['p'];
						$film['FilmPicture'][0]['type'] = 'poster';
						$film['FilmPicture'][0]['file_name'] = str_replace(Configure::read('Catalog.imgPath'), '', $film['FilmPicture'][0]['file_name']);
					}
					else
					{
						$film = $this->Film->getRandomFilm();
					}
				}

				if (!$persons = Cache::read('Catalog.film_view_' . $id.'_persons','media'))
		        {
		    	    $persons = $this->Film->getFilmPersons($id);
			    	Cache::write('Catalog.film_view_' . $id.'_persons', $persons,'media');
		        }

		        $out = array();
		        foreach ($persons as $person)
		        {
		            if (!isset($out[$person['Person']['id']]))
		            {
		                unset($person['FilmsPerson']);
		                $person['Profession'] = array($person['Profession']['id'] => $person['Profession']['title']);
		                $out[$person['Person']['id']] = $person;
		            }
		            else
		            {
		                $out[$person['Person']['id']]['Profession'][$person['Profession']['id']] = $person['Profession']['title'];
		            }
		        }
		        $this->set('persons', $out);

            	$posters = Set::extract('/FilmPicture[type=poster]/.', $film);
				$picture = '';
				$i = array_rand($posters);
				if (isset($posters[$i]))
				{
					$picture = $posters[$i]['file_name'];
				}
				$picture = Configure::read('Catalog.imgPath') . $picture;
				$title = $film["Film"]["title"];
				$link = '/media/view/' . $film["Film"]["id"];
				$description = $film["Film"]["description"];
				$id = $film["Film"]["id"];
		        $this->set('film', $film);
			break;

    	}
        $this->set('id', $id);
        $this->set('maxId', $maxId);
        $this->set('picture', $picture);
        $this->set('title', htmlspecialchars($title));
        $this->set('link', $link);
        $this->set('description', $description);
        $this->set('site', $site);
        $this->set('detail', $detail);

		$memcache_obj->close();
    }

    public function about()//ГЛАВНАЯ СТРАНИЦА РАЗДЕЛА О НАС
    {
    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);

    	$lst = $this->News->findAll(array('News.hidden' => 0), null, 'News.created DESC');
    	$this->set('lst', $lst);
    }

}
