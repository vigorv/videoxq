<?php
/**
 * Хэлпер постраничной навигации
 * Нумерация страниц начинается с 1
 *
 */
class PageNavigatorHelper extends AppHelper {

	public $helpers = array('Html');

	private $url = '';
	private $args = '';
	private $maxPage = 0;
	private $interval = 5;
	private $jump = 6;

	/**
	 * адрес страницы, к которой нужно добавить параметры навигации по страницам
	 *
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * дополнительные параметры, которые нужно добавить к ссылке для перехода на страницу
	 *
	 * @param array $args - ассоциативный массив [arg] = value
	 */
	public function setArgs($args = array())
	{
		$this->args = $args;
	}

	/**
	 * сформировать URL для ссылки навигатора
	 *
	 */
	public function getNavigateUrl($args = array())
	{
		$output = array($this->url);
		$outArgs = $this->args;
		if (!empty($args))
		{
			foreach ($args as $arg => $val)
				$outArgs[$arg] = $val;
		}
		if (!empty($outArgs))
		{
			foreach ($outArgs as $arg => $val)
				$output[] = $arg . ':' . $val;
		}
		$url = implode('/', $output);
		return $url;
	}

	/**
	 * указать максимальную страницу
	 *
	 * @param integer $maxNum
	 */
	public function setMaxPage($maxNum)
	{
		$this->maxPage = abs($maxNum);
		if (empty($this->maxPage))
			$this->maxPage = 1;
	}

	/**
	 * указать интервал - количество видимых ссылок на страницы
	 *
	 * @param integer $interval
	 */
	public function setInterval($interval = 5)
	{
		$this->interval = abs($interval);
		$this->interval--;
		if ($this->interval <= 0)
			$this->interval = 1;
		if ($this->jump <= $this->interval)
			$this->jump = $this->interval + 1;
	}

	/**
	 * указать шаг для пролистывания страниц
	 * (должен быть не меньше интервала setInterval)
	 *
	 * @param integer $jump
	 */
	public function setJump($jump = 6)
	{
		$this->jump = abs($jump);
		if ($this->jump <= $this->interval)
			$this->jump = $this->interval + 1;
	}

	/**
	 * сгенерировать HTML код постраничной навигации
	 *
	 * @param integer $page - текущая страница
	 */
	public function get($page)
	{
		if ($this->maxPage <= 1)
			return '';

		$output = array();

		$first = 1;
		$last = $this->maxPage;
		if ($page < $first) $page = 1;
		if ($page > $last) $page = 1;

		$start = $page - intval($this->interval / 2);
		$pgDown = $start - $this->jump;
		$prev = $page - 1;
		if ($pgDown < $first) $pgDown = $first;

		if ($start <= 0)
		{
			$first = 0; //ОТДЕЛЬНУЮ ССЫЛКУ НА ПЕРВУЮ СТРАНИЦУ ВЫВОДИТЬ НЕ БУДЕМ
			$pgDown = 0;
		}
		$start = max($start, 1);
		$finish = $start + $this->interval;
		$finish = min($finish, $this->maxPage);

		if ($finish - $start < $this->interval)
		{
			$start = $finish - $this->interval;
		}
		$pgDown = $start - $this->jump;
		if ($pgDown < $first) $pgDown = $first;
		if ($start <= 0)
		{
			$first = 0; //ОТДЕЛЬНУЮ ССЫЛКУ НА ПЕРВУЮ СТРАНИЦУ ВЫВОДИТЬ НЕ БУДЕМ
			$pgDown = 0;
		}
		$start = max($start, 1);

		$next = $page + 1;
		$pgUp = $finish + $this->jump;
		if ($pgUp > $last) $pgUp = $last;

		if ($last == $finish)
		{
			$last = 0; //ОТДЕЛЬНУЮ ССЫЛКУ НА ПОСЛЕДНЮЮ СТРАНИЦУ ВЫВОДИТЬ НЕ БУДЕМ
			$pgUp = 0;
		}

		if (!empty($first) && ($first < $start))
		{
			$output[] = $this->Html->link('1', $this->getNavigateUrl(array('page' => $first)), array('title' => __('first', true)));
			$output[] = '...';
		}
		if (!empty($pgDown) && ($pgDown < $start))
		{
			$output[] = $this->Html->link('<<', $this->getNavigateUrl(array('page' => $pgDown)), array('title' => __('rewind', true)));
		}
		if ($prev > 0)
		{
			$output[] = $this->Html->link('<', $this->getNavigateUrl(array('page' => $prev)), array('title' => __('previous', true)));
		}

		for ($cnt = $start; $cnt <= $finish; $cnt++)
		{
			if ($cnt == $page)
				$output[] = $cnt;
			else
				$output[] = $this->Html->link($cnt, $this->getNavigateUrl(array('page' => $cnt)));
		}

		if ($next <= $this->maxPage)
		{
			$output[] = $this->Html->link('>', $this->getNavigateUrl(array('page' => $next)), array('title' => __('next', true)));
		}
		if (!empty($pgUp) && ($pgUp > $finish))
		{
			$output[] = $this->Html->link('>>', $this->getNavigateUrl(array('page' => $pgUp)), array('title' => __('forward', true)));
		}
		if (!empty($last) && ($last > $finish))
		{
			$output[] = '...';
			$output[] = $this->Html->link($last, $this->getNavigateUrl(array('page' => $last)), array('title' => __('last', true)));
		}

		return implode(' &nbsp; ', $output);
	}
}