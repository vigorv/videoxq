<?php
App::import('Model', 'SimilarFilm');
App::import('Model', 'Film');
/**
 * Управление группами похожих, по мнению экспертов, фильмов
 *
 */
class SimilarFilmsController extends AppController
{
    public $name = 'SimilarFilms';
    public $uses = array('SimilarFilm', 'Film');

    /**
     * Модель групп похожих фильмов
     *
     * @var SimilarFilm
     */
    public $SimilarFilm;

    /**
     * Модель фильмов
     *
     * @var Film
     */
    public $Film;

    /**
     * получить список имен фильмов по списку идентификаторов
     *
     * @param string $ids
     * @return array
     */
    public function getFilmsNames($ids)
    {
    	$names = array();
		$this->Film->contain(array());
		$ids = explode(',', $ids);
		$films = $this->Film->findAll(array('Film.id' => $ids), array('Film.title'));
		foreach ($films as $film)
			$names[] = $film['Film']['title'];
		return $names;
    }
    /**
     * Список групп
     */
	public function admin_index()
	{
		$groups = $this->paginate();
		if ($groups)
		{
			foreach($groups as $key => $value)
			{
				$films = $this->getFilmsNames($value['SimilarFilm']['films']);
				$groups[$key]['SimilarFilm']['films'] = implode(', ', $films);
			}
		}
		$this->set('groups', $groups);
	}

	/**
     * Форма редактирования/добавления групп
	 *
	 * @param integer $id - идентификатор группы
	 */
	public function admin_form($id = null)
	{
		$group = null;
		if(!empty($id))
		{
			$group = $this->SimilarFilm->read(null, $id);
			$names = $this->getFilmsNames($group['SimilarFilm']['films']);
			$group['SimilarFilm']['films'] = implode(', ', $names) . ', ';
		}
		$this->set('group', $group);
	}

	/**
     * Удалить группу
	 *
	 * @param integer $id			- идентификатор группы
	 */
	public function admin_delete($id)
	{
		$result = 0;
		if (!empty($id))
		{
			$result = $this->SimilarFilm->del($id);
		}
		$this->set('result', $result);
	}

    /**
     * Добавление/сохранение группы
     */
	public function admin_save()
	{
		$result = 0;

		if (!empty($this->data))
		{
			if ((!empty($this->data['SimilarFilm']['title'])) && (!empty($this->data['SimilarFilm']['films'])))
			{
				$names = array();
				foreach (explode(',', $this->data['SimilarFilm']['films']) as $name)
				{
					$name = trim($name);
					if (empty($name)) continue;
					$names[] = $name;
				}

				if (!empty($names))
				{
					$this->Film->contain(array());
					$films = $this->Film->findAll(array('Film.title' => $names), array('Film.id'));
					$ids = array();
					if (!empty($films))
					{
						foreach ($films as $film)
						{
							$ids[] = $film['Film']['id'];
						}
					}
					$this->data['SimilarFilm']['films'] = implode(',', $ids);
					$result = $this->SimilarFilm->save($this->data);
					foreach ($ids as $id)//СБРАСЫВАЕМ КЭШ ПО КАЖДОМУ ФИЛЬМУ
					{
						Cache::delete('Catalog.film_similar_' . $id, 'media');
					}
				}
			}
		}
		$this->set('result', $result);
	}

    function autoComplete()
    {
	    $this->layout = "ajax";
        $search = $this->params['url']['q'];
        $this->set('films', $this->Film->findAll(array('Film.title like ' => '%' . $search . '%'), array('Film.title'), null, 5, 1, 0));
    }
}
