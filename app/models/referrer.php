<?php
/**
 * Модель адресов страниц (для системы учета переходов)
 *
 */
class Referrer extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Referrer';

    public $belongsTo = array(
            'Alias' => array('className' => 'Alias',
                                'foreignKey' => 'alias_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

	public $hasMany = array(
		'Chain'	=> array(
			'className'		=> 'Chain',
			'conditions'	=> '',
			'order'			=> '',
			'limit'			=> '',
			'foreignKey'	=> 'url_id',
			'dependent'		=> true,
			)
		);

	/**
	 * Выбираем точки входа
	 *
	 * @param integer $external - значения 0/1 для выборки точек входа для внутренних/внешних рефералов
	 * @return array - массив точек входа (записи по таблице рефералов)
	 */
	public function getEnters($external = 0)
	{
		$externalSql = ' and referrers.external = ' . $external;
		$sql = '
			SELECT referrers.*, COUNT(chains.id) AS chaincnt FROM referrers
				LEFT JOIN chains ON (chains.url_id = referrers.id and chains.parent_id = 0)
			WHERE NOT ISNULL(referrers.alias_id) ' . $externalSql. ' GROUP BY chains.url_id ORDER BY chaincnt desc
		';
		$enters = $this->query($sql);
		return $enters;
	}

	/**
	 * Выбираем начальные звенья цепочек (суммарно по всем посетителям)
	 *
	 * @param array $referrer	- реферал точки входа (запись таблицы рефералов)
	 * @param integer $external	- признак "внешний" реферал
	 * @return array - массив начальных звеньев цепочек
	 */
	public function getChainEnters($referrer, $external = 0)
	{
		$externalSql = 'referrers.id = chains.url_id and chains.url_id = ' . $referrer['referrers']['id'] . ' and referrers.external = 0';
		$externalAliasSql = '';
		$lName = '';
		$groupBy = 'referrers.alias_id';
		if ($external)
		{
			$externalSql = 'referrers.id = chains.referrer_id and chains.referrer_id = ' . $referrer['referrers']['id'] .' and referrers.external = 1';
			//ДЛЯ ВНЕШНИХ РЕФЕРАЛОВ ДЕЛАЕМ ДОПОЛНИТЕЛЬЛНЫЙ ПОДЗАПРОС К РЕФЕРАЛУ АДРЕСА И ЕГО АЛИАСУ
			$externalAliasSql = '
			LEFT JOIN referrers as r2 ON (r2.id = chains.url_id and r2.external = 0)
			LEFT JOIN aliases as a2 ON (a2.id = r2.alias_id)
			';
			$lName = ', a2.name as lname';
			$groupBy = 'r2.alias_id';
		}
		$sql = '
			SELECT chains.*, COUNT(referrers.alias_id) AS chaincnt, referrers.url as rurl' . $lName . ', aliases.name as aname FROM chains
			INNER JOIN referrers ON (' . $externalSql. ')
			LEFT JOIN aliases ON (aliases.id = referrers.alias_id)
			' . $externalAliasSql . '
			WHERE chains.parent_id = 0 and chains.external = ' . $external . '
			GROUP BY ' . $groupBy . ' ORDER BY chaincnt desc
		';
		$enters = $this->Chain->query($sql);
		return $enters;
	}

	/**
	 * выборка цепочки, начиная от звена parent
	 * цепочка "тянется" от одной точки входа
	 *
	 * @param integer $id - идентификатор точки входа (по таблице referrers)
	 * @return array многомерный массив (рекурсивно все звенья цепочки)
	 */
	public function getChain($parent)
	{
		$tree = array();
		//рекурсивный вызов по parent_id
		$sql = '
			SELECT chains.*, COUNT(referrers.alias_id) AS chaincnt, referrers.url as rurl, aliases.name as aname FROM chains
			LEFT JOIN referrers ON (referrers.id = chains.url_id)
			LEFT JOIN aliases ON (aliases.id = referrers.alias_id)
			WHERE chains.referrer_id = ' . $parent['chains']['url_id'] . '
				and chains.enter_id = ' . $parent['chains']['enter_id'] . '
				and chains.external = ' . $parent['chains']['external'] . '
				and chains.step > ' . $parent['chains']['step'] . '
			GROUP BY referrers.alias_id ORDER BY chaincnt desc
		';
//			GROUP BY referrers.alias_id ORDER BY chaincnt desc
		$childs = $this->Chain->query($sql);
		if (count($childs) > 0)
		{
			foreach ($childs as $child)
			{
				$tree[] = array(
					"zveno"	=> $child,
					"tree"	=> $this->getChain($child)
				);
			}
		}
		return $tree;
	}
}