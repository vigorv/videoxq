<?php
class NewsController extends AppController {

    var $name = 'News';
    var $helpers = array('Html', 'Form', 'Calendar', 'Directions');

    var $uses = array('News', 'Direction');
    var $components = array('BlockStats');
    var $vendors = array('Utils');

	/**
	 * модель таблицы News
	 *
	 * @var AppModel
	 */
    var $News;

	/**
	 * модель таблицы Directions
	 *
	 * @var Directions
	 */
    var $Direction;

    /**
     * вывод списка новостей
     * если указан $dir_id, то выводим список новостей категории
     *
     * @param integer $dir_id - идентификатор направления (категории)
     * @param integer $dt - дата в формате YYYY-MM-DD или YYYY-MM
     */
    function index($dir_id = 0, $dt = '')
    {
	$lang = $this->Session->read("language");
	if ($lang == _ENG_)
            $this->redirect('/media');
/*
 * Старый (простой одноуровневый список) способ вывода разделов новостей
 *
    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);
    	$this->set('dir_id', $dir_id);
*/
        $dir_id = intval($dir_id);
        //----------------------------------------------------------------------
        //упрощенный, оптимизированый способ генерации дерева html в виде
        //списка, с использванием кейковскоко метода модель->generatetreelist()
        //очень удобно! не надо заморачиваться с рекурсией и т.п. на выходе уже
        //готовое дерево, отсортировано и по порядку вложенности, для пометки и
        //подсчета уровня вложенности, используем символ "#", количество "#" в
        //начале строки, соотвествует уровню вложенности. ВНИМАНИЕ! Этот символ
        //"#" являеется "служебным", и не должен присутствовать в значении
        //самого заголовка, в нашем случае поле 'title' данной модели, иначе
        //используем другой специальный символ.
        //
        //задаем параметры выборки
        $conditions = array();
        $conditions[] = array('hidden' => 0);//нам скрытые разделы не нужны!!!
        //задаем наш служебный спец.символ
        $level_char = '';

        $tree_arr = Cache::read('News.categoriesFullTree', 'block');
        if (empty($tree_arr))
        {
	        //генерируем список элементов html
	        $tree_arr = $this->Direction->generatetreelist($conditions, null, null, $level_char);
                //учитываем ли скрытые новости в подсчете?
                $do_count_hidden = false;
                //добавим к этим данным, количество существующих новостей на каждый
                //раздел, включая его потомков.
                foreach ($tree_arr  as $direction_id=>$direction_title){
                    //узнаем idшки вложенных категорий
                    //выберем список вложенных категорий
                    $directions = $this->Direction->getSubDirections($direction_id);
                    $directions_ids = array();
                    $directions_ids[] = $direction_id;
                    //и из этого списка создадим список id этих же категорий
                    foreach($directions as $direction_row){
                        $directions_ids[] = $direction_row['Direction']['id'];
                    }

                    $tree_arr[$direction_id] = array (
                        'title' => $direction_title,
                        'count' => $this->Direction->countNewsInDirections($directions_ids, $do_count_hidden)
                        );
                }




	        Cache::write('News.categoriesFullTree', $tree_arr, 'block');
        }
        //если не задали текущий раздел, то выцепим id корневого элемента
        if (!$dir_id && $tree_arr) {
            reset($tree_arr);
            $dir_id = key($tree_arr);
            }
        //формируем массив данных для хелпера вывода html дерева
        $directions_data = array(
            'list' => $tree_arr,
            'current_id' => $dir_id,
            'level_char' => $level_char,
            'html_container_id' => 'left-menu'
        );
        $this->set('directions_data', $directions_data);
        //----------------------------------------------------------------------




    	$conditions = array('News.hidden' => 0);
    	if (!empty($dir_id))
    	{
    		if (!empty($dt))
    		{
	   			$ymd = explode('-', $dt);
    			//ВЫБОРКА ПО ДАТЕ
    			$year = $ymd[0];
    			if (!empty($ymd[1])) $month = $ymd[1]; else $month = 1;
    			if (!empty($ymd[2])) $day = $ymd[2]; else $day = 1;
    			$strt = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year));

    			if (!empty($ymd[2])) $day++;//ВЫБИРАЕМ ВСЕ ЗА ДЕНЬ
    			if (empty($ymd[2])) $month++; //ВЫБИРАЕМ ЗА МЕСЯЦ
    			$fin = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year) - 1);
	    		$conditions['News.created >='] = $strt;
	    		$conditions['News.created <='] = $fin;
		    	$this->set('year', $year);
    			$this->set('month', $month);
    		}
    		//$conditions['News.direction_id'] = $dir_id;
	    	$subDirections = $this->Direction->getSubDirections($dir_id);
	    	$ids = array($dir_id);
	    	if (!empty($subDirections))
	    	{
	    		foreach ($subDirections as $sD)
	    		{
	    			$ids[] = $sD['Direction']['id'];
	    		}
	    	}
	    	$conditions['News.direction_id'] = $ids;
    	}
		$this->set('dir_id', $dir_id);
		$this->set('dt', $dt);
//pr($conditions);

        //$this->Paginator->options(array('url' => 'news/index/'.$dir_id));

        $rows_per_page = 10;
        $this->paginate = array(
//                    'page' => 1,
                    'conditions' => $conditions,
                    'limit' => $rows_per_page,
                    'order' => array(
                        'News.created' => 'desc'
                        )
                    );

 /*
            $total_rows_count = $this->News->find('count',
                                                            array(
                                                                'conditions' =>$conditions,
                                                                'recursive' => 0));

*/
        $lst = $this->paginate('News');
//        pr ($data);



    	//$lst = $this->News->findAll($conditions, null, 'News.created DESC');


    	$this->set('lst', $lst);
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid New.', true));
            $this->redirect('/');
        }

        $conditions = array();
        $conditions[] = array('hidden' => 0);//нам скрытые разделы не нужны!!!
        //задаем наш служебный спец.символ
        $level_char = '#';

        $tree_arr = Cache::read('News.categoriesFullTree', 'block');
        if (empty($tree_arr))
        {
	        //генерируем список элементов html
	        $tree_arr = $this->Direction->generatetreelist($conditions, null, null, $level_char);
	        Cache::write('News.categoriesFullTree', $tree_arr, 'block');
        }

        $dir_id = 0; $id = intval($id);
		$info = $this->News->findById($id);

		if (!empty($info))
		{
			$dir_id = $info['News']['direction_id'];
		}

        //формируем массив данных для хелпера вывода html дерева
        $directions_data = array(
            'list' => $tree_arr,
            'current_id' => $dir_id,
            'level_char' => $level_char
        );
        $this->set('directions_data', $directions_data);

    	$conditions = array('News.hidden' => 0);
    	$subDirections = $this->Direction->getSubDirections($dir_id);
    	$ids = array($dir_id);
    	if (!empty($subDirections))
    	{
    		foreach ($subDirections as $sD)
    		{
    			$ids[] = $sD['Direction']['id'];
    		}
    	}
    	$conditions['News.direction_id'] = $ids;
    	$lst = $this->News->findAll($conditions, null, 'News.created DESC');
    	$this->set('lst', $lst);
		$this->set('dir_id', $dir_id);

		if (!empty($info))// && !$info['News']['hidden'])
		{
			if (!empty($info['News']['poll_id']))
			{
				$voteData = $this->BlockStats->getPoll($info['News']['poll_id']);
				$this->set('block_poll', $voteData);
			}
            $this->set('info', $info);

	    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
	    	$this->set('dirs', $dirs);

        	$dat = date('Y-m-d', strtotime($info['News']['created']));
	        $this->set('dat', $dat);

	    	$isWS = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER["REMOTE_ADDR"], 1);
	    	if ($isWS == 'OPERA-MINI') $isWS = false;
	        if ($isWS)
	        {
	        	$flowServerAddr = '92.63.196.52';
	        	$flowServerAddrPort = '92.63.196.52:82';
	        }
	        else
	        {
	        	$flowServerAddr = '87.226.225.78:83';
	        	$flowServerAddrPort = '87.226.225.78:83';
	        }
	        $this->set('flowServerAddr', $flowServerAddr);
	        $this->set('flowServerAddrPort', $flowServerAddrPort);
			$ftpInfo = Cache::read('News.ftpInfo.' . $info['News']['id'], 'rocket');
			if (empty($ftpInfo))
			{
				$ftpInfo = array();
			}
			if (empty($ftpInfo[$dat]))
			{
				$ftp_id = ftp_connect($flowServerAddr, 0, 5);
		        if ($ftp_id)
		        {
			        $login = ftp_login($ftp_id, 'mp4', '9043uj53456t');
			        if ($login)
			        {
			        	$dir = $dat;
			        	if (!empty($info['News']['ftpdir']))
			        	{
			        		$dir = $info['News']['ftpdir'];
			        	}
			        	$res = ftp_pasv($ftp_id, true);
				        $lst = ftp_nlist($ftp_id, $dir);
				        if (!empty($lst))
				        {
				        	for ($match = 1; $match < 20; $match++)//БЕРЕМ КОЛ-ВО МАТЧЕЙ С ЗАПАСОМ
				        	{
				        		$matchContent = ftp_nlist($ftp_id, $dir . '/' . $match);
				        		if (!empty($matchContent))
				        		{
				        			$infoTxt = @file_get_contents('http://' . $flowServerAddr . '/' . $dir . '/' . $match . '/info.txt');
				        			$ftpInfo[$dir][$match] = array(
				        				'video' => ftp_nlist($ftp_id, $dir . '/' . $match . '/video'),
				        				'foto'	=> ftp_nlist($ftp_id, $dir . '/' . $match . '/foto'),
				        				'info'	=> $infoTxt,
				        			);
				        		}
				        	}
				        	$video = ftp_nlist($ftp_id, $dir . '/other/video');
				        	$foto = ftp_nlist($ftp_id, $dir . '/other/foto');
				        	$ftpInfo[$dir]['video'] = $video;//ПРОЧЕЕ ВИДЕО
				        	$ftpInfo[$dir]['foto'] = $foto;//ПОРЧЕЕ ФОТО
		        			$infoTxt = @file_get_contents('http://' . $flowServerAddr . '/' . $dir . '/other/info.txt');
				        	$ftpInfo[$dir]['info'] = $infoTxt;
				        	Cache::write('News.ftpInfo.' . $info['News']['id'], $ftpInfo, 'rocket');
				        }
			        }
			        ftp_close($ftp_id);
		        }
			}
//pr($ftpInfo);
	        $this->set('ftpInfo', $ftpInfo);
		}
		else
		{
			$this->redirect('/news');
		}
    }

    /**
     * импорт новостей партнеров
     *
     */
    public function frompartners()
    {
        App::import('Vendor', 'utils');
    	$pDirection = Configure::read('News.partnerDirectionId');
    	$feeds  = array(
    		array(
    			'partner'	=> 'ООО "Мастер Тэйп"',
    			'url'		=> 'http://master-tape.ru',
    			'prefix'	=> 'mastertape',
    			'rss'		=> 'http://master-tape.ru/component/option,com_rss/feed,RSS2.0/no_html,1',
    			'encoding'	=> 'utf-8',
    		),
    		array(
    			'partner'	=> 'ООО "Централ Партнершип Сейлсхаус"',
    			'url'		=> 'http://www.centpart.ru/news/',
    			'prefix'	=> 'centpart',
    			'rss'		=> '',
    			'encoding'	=> 'utf-8',
    		),
    		array(
    			'partner'	=> 'ООО "ДиВиДи Экспо"',
    			'url'		=> 'http://www.ruscico.com',
    			'prefix'	=> 'ruscico',
    			'rss'		=> '',
    			'encoding'	=> 'utf-8',
    		),
    		array(
    			'partner'	=> 'ООО "Союз-Видео"',
    			'url'		=> 'http://www.soyuzvideo.ru',
    			'prefix'	=> 'soyuzvideo',
    			'rss'		=> '',
    			'encoding'	=> 'utf-8',
    		),
    		array(
    			'partner'	=> 'ООО "Прокатная группа "Страна"',
    			'url'		=> 'http://www.stranamedia.com/Novosti.17.0.html',
    			'prefix'	=> 'stranamedia',
    			'rss'		=> '',
    			'encoding'	=> 'utf-8',
    		),
    		array(
    			'partner'	=> 'ООО "Ассоцияация ДВД издателей"',
    			'url'		=> 'http://www.advdp.ru/news_catalog.html',
    			'prefix'	=> 'advdp',
    			'rss'		=> '',
    			'encoding'	=> 'windows-1251',
    		),
    	);

			//ПАРСИНГ
			global $tag;
			global $item;
			global $items;
			global $itemStart;
			global $encoding;

			function prepareLink($url, $link)
			{
				if (strpos($link, 'http://') === 0)
					return $link;
				$info = parse_url($url);

				return 'http://' . $info['host'] . $link;
    		}

    		function advdp($content, $url)
    		{
		/*
<td class="newsDate">01.09.2011</td>
        </tr>
        <tr>
         <td class="newsText">
         <p><strong><font face="Times New Roman" color="#000000" size="3"><font color="#ff0000">Шведская пиратская партия осудила действия властей страны, связанные</font><font color="#ff0000"> с арестом</font> подростка, который обвиняется в незаконном обмене файлами. </font></strong>
         </p> <br><a href="news.html?name=news1&item_id=1374">далее »</a></td>
		*/
				global $items;

				$items = array();
				//РАЗБИВАЕМ КОНТЕНТ НА НОВОСТИ
				$matches = array();
				preg_match_all('/<td class="newsDate">(.*?)<\/td>(.*?)<td class="newsText">(.*?)<a href="([^"]*)"[^>]*>(.*?)<\/a><\/td>/sim', $content, $matches, PREG_SET_ORDER);
/*
pr($matches);
exit;
//*/
				if (!empty($matches))
				{
					foreach ($matches as $m)
					{
						$dt = trim($m[1]);
						$dt = explode('.', $dt);
						$dt = $dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00';
						$dt = date('Y-m-d H:i:s', strtotime($dt));
						//$img = str_replace($m[6], prepareLink($url, $m[6]), $m[4]);
						$items[] = array(
							'title' => $m[3],
							'created' => $dt,
							'modified' => $dt,
							'link' => '/' . $m[4],
							'stxt' => strip_tags($m[3]),
							'txt' => '',
						);
					}
				}
    		}

    		function stranamedia($content, $url)
    		{
		/*
<div class="content-news-date">29-08-11</div>
<div class="content-news-record"><div class="content-news-header">
<a href="Novosti.17.0.html?&amp;tx_mininews_pi1[showUid]=217&amp;cHash=94d02046d0" >Так прошёл день кино</a></div>
<div class="content-news-text">Кинематографисты и киноманы 27 августа отметили День кино&nbsp;
<span class="content-news-more"><a href="Novosti.17.0.html?&amp;tx_mininews_pi1[showUid]=217&amp;cHash=94d02046d0" >Подробнее</a></span>
		*/
				global $items;

				$items = array();
				//РАЗБИВАЕМ КОНТЕНТ НА НОВОСТИ
				$matches = array();
				preg_match_all('/<div class="content-news-date">(.*?)<\/div>(.*?)<div class="content-news-header">(.*?)<a href="([^"]*)"[^>]*>(.*?)<\/a>(.*?)<div class="content-news-text">(.*?)<span class="content-news-more">/sim', $content, $matches, PREG_SET_ORDER);
/*
pr($matches);
exit;
//*/
				if (!empty($matches))
				{
					foreach ($matches as $m)
					{
						$dt = trim($m[1]);
						$dt = explode('-', $dt);
						$dt = $dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00';
						$dt = date('Y-m-d H:i:s', strtotime($dt));
						//$img = str_replace($m[6], prepareLink($url, $m[6]), $m[4]);
						$items[] = array(
							'title' => $m[5],
							'created' => $dt,
							'modified' => $dt,
							'link' => '/' . $m[4],
							'stxt' => strip_tags($m[7]),
							'txt' => '',
						);
					}
				}
    		}

    		function soyuzvideo($content, $url)
    		{
    			/*
	<span class="szv-main-news-item-name">
	<h2><a href="/detail/19/%D0%A0%D1%8D%D0%B9+%D0%91%D1%80%D1%8D%D0%B4%D0%B1%D0%B5%D1%80%D1%80%D0%B8/">Рэй Брэдберри</a></h2>
	</span>
	   		<span class="szv-main-news-item-date">24.08.2011</span>
</div>
     <div class="clrboth"></div>
		<div class="szv-main-news-item-img" style="width:79px;">
			<a href="/detail/19/%D0%A0%D1%8D%D0%B9+%D0%91%D1%80%D1%8D%D0%B4%D0%B1%D0%B5%D1%80%D1%80%D0%B8/"><img src="/upload/iblock/678/Ray-Bradbury.jpg" width="79" height="96" alt="" title="Рэй Брэдберри" /></a>
		</div>
	<div class="szv-main-news-item-text" style="width:446px;">
    	22 августа 2011 года американскому писателю-фантасту <strong>Рэю Брэдберри</strong> исполнился 91 год. Знаменитую повесть «Вино из одуванчиков» собирается экранизировать российский режиссер Родион Нахапетов, он же автор сценария.				                <div class="szv-read-more">
	<span>
		*/
				global $items;

				$items = array();
				//РАЗБИВАЕМ КОНТЕНТ НА НОВОСТИ
				$matches = array();
				preg_match_all('/<span class="szv-main-news-item-name">(.*?)<h2><a href="([^"]*)"[^>]*>(.*?)<\/a>(.*?)<span class="szv-main-news-item-date">(.*?)<\/span>(.*?)(<img(.*?)src="([^"]+)"(.*?)\/>)(.*?)<div class="szv-main-news-item-text"[^>]*>(.*?)<div class="szv-read-more">/sim', $content, $matches, PREG_SET_ORDER);
/*
pr($matches);
exit;
//*/
				if (!empty($matches))
				{
					foreach ($matches as $m)
					{
						$dt = trim($m[5]);
						$dt = explode('.', $dt);
						$dt = $dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00';
						$img = str_replace($m[9], prepareLink($url, $m[9]), $m[7]);
						$items[] = array(
							'title' => $m[3],
							'created' => $dt,
							'modified' => $dt,
							'link' => $m[2],
							'stxt' => $img . strip_tags($m[12]),
							'txt' => '',
						);
					}
				}
    		}

    		function ruscico($content, $url)
    		{
				/* ПРИМЕР КОДА НОВОСТИ
<div class='nc_row'>
<h3>Восстановление сайта</h3>
<p class='nc_announce'><p><br />
<img width="150" height="150" vspace="0" hspace="5" border="1" align="left" alt="" src="/netcat_files/Image/news/230611/refcc.jpg" /></p>
<p class="MsoNormal">Спешим вам сообщить, что на сайте восстановлен и открыт для заказа <a href="http://ruscico.ru/catalog/cataloguedvd/">каталог фильмов</a>, который со временем будет дополняться утерянными позициями и нашими новинками.</p>
<p class="MsoNormal">&nbsp;</p>
<p class="MsoNormal">Если вы заметите неточность в описании фильма, несоответствие характеристикам фильма или другие ошибки, просьба сообщить на <span lang="EN-US" style="mso-ansi-language:EN-US"><a href="mailto:honest@ruscico.com">honest<span lang="RU" style="mso-ansi-language:RU">@</span>ruscico<span lang="RU" style="mso-ansi-language:RU">.</span>com</a><o:p></o:p></span></p>

<p class="MsoNormal">Благодарим за внимание.</p>
<p>&nbsp;</p></p><div class='nc_datetime'><span class='nc_date'>23.06.2011 </span></div><span class='nc_more'><a href='/news/new_9.html'>подробнее...</a></span>
<br/><span><b>комментарии и ответы:</b> </span>0
</div>
    			*/
				global $items;

				$items = array();
				//РАЗБИВАЕМ КОНТЕНТ НА НОВОСТИ
				$matches = array();
				preg_match_all('/<div class=\'nc_row\'>(.*?)<h3>([^<]*)<\/h3>(.*?)(<img(.*?)src="([^"]+)"(.*?)\/>)(.*?)<span class=\'nc_date\'>(.*?)<\/span>(.*?)href=\'([^\']+)\'/sim', $content, $matches, PREG_SET_ORDER);
/*
pr($matches);
exit;
//*/				if (!empty($matches))
				{
					foreach ($matches as $m)
					{
						$dt = trim($m[9]);
						$dt = explode('.', $dt);
						$dt = $dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00';
						$img = str_replace($m[6], prepareLink($url, $m[6]), $m[4]);
						$items[] = array(
							'title' => $m[2],
							'created' => $dt,
							'modified' => $dt,
							'link' => $m[11],
							'stxt' => $img . strip_tags($m[8]),
							'txt' => '',
						);
					}
				}
    		}

			function centpart($content, $url)
			{
				/* ПРИМЕР КОДА НОВОСТИ
                <div class="newslist">
                    <div class="newsitem">

                        <div class="pic">
                            <img src="/files/images/gallery/1808_2_CompressedImage.jpeg" width="94" height="53" alt="" />
                        </div>
                        <div class="txt">
                            <a href="/news/transformeri-3--temnaa-storona/" class="head">
                                ТРАНСФОРМЕРЫ 3: ТЕМНАЯ СТОРОНА ЛУНЫ вошли в топ-5 за всю историю российского кинопроката!</a>
                                <span style="color: black; font-size: 12px;">ТРАНСФОРМЕРЫ 3: ТЕМНАЯ СТОРОНА ЛУНЫ вошли в топ-5 за всю историю российского кинопроката!</span>
                                <span>09 августа 2011</span>

                        </div>
                    </div>
                </div>
				*/
				global $items;

				$items = array();
				//РАЗБИВАЕМ КОНТЕНТ НА НОВОСТИ
				$matches = array();
				preg_match_all('/<div class="newslist">(.*?)(<img(.*?)src="([^"]+)"(.*?)\/>)(.*?)<a href="([^"]{1,})"[^>]*>(.*?)<\/a>(.*?)<span[^>]*>(.*?)<\/span>(.*?)<span[^>]*>(.*?)<\/span>(.*?)<\/div>/sim', $content, $matches, PREG_SET_ORDER);
/*
pr($matches);
exit;
//*/
				if (!empty($matches))
				{
					$months = array(
						'01' => 'января',
						'02' => 'февраля',
						'03' => 'марта',
						'04' => 'апреля',
						'05' => 'мая',
						'06' => 'июня',
						'07' => 'июля',
						'08' => 'августа',
						'09' => 'сентября',
						'10' => 'октября',
						'11' => 'ноября',
						'12' => 'декабря');
					$fMonths = array_flip($months);

					foreach ($matches as $m)
					{
						$dt = trim($m[12]);
						$dt = explode(' ', $dt);
						if (!empty($fMonths[$dt[1]]))
						{
							$dt[1] = $fMonths[$dt[1]];
						}
						$dt = $dt[2] . '-' . $dt[1] . '-' . $dt[0] . ' 00:00:00';
						$img = str_replace($m[4], prepareLink($url, $m[4]), $m[2]);
						$items[] = array(
							'title' => $m[10],
							'created' => $dt,
							'modified' => $dt,
							'link' => $m[7],
							'stxt' => $img . strip_tags($m[10]),
							'txt' => '',
						);
					}
				}
			}

			function mastertape_start_el($parser, $name, $attrs)
			{
			    global $tag;
				global $item;
				global $itemStart;
			    $tag = strtoupper($name);

			    switch ($tag)
			    {
			    	case "ITEM":
			    		$itemStart = true;
		    			$item = array();
			    	break;
			    }
			}

			function mastertape_data($parser, $data)
			{
			    global $tag;
			    global $item;
			    global $itemStart;
			    global $encoding;

				if (!trim($data))
					return;

				if (!$itemStart)
					return;

				if ($encoding <> 'utf-8')
				{
					$data = iconv($encoding, 'utf-8', $data);
				}

				switch ($tag)
				{
			    	case "TITLE":
						$item['title'] = $data;
					break;

			    	case "LINK":
						$item['link'] = $data;
			    	break;

			    	case "DESCRIPTION":
						$item['stxt'] = '';
						$item['txt'] = '';
						if (mb_strlen($data) >= 255)
						{
							$item['txt'] = $data;
						}
						else
						{
							$item['stxt'] = $data;
						}
			    	break;

			    	case "LINK":
						$item['link'] = $data;
			    	break;

			    	case "PUBDATE":
						$item['created'] = date('Y-m-d H:i:s', strtotime($data));
						$item['modified'] = $item['created'];
			    	break;
				}
			}

			function mastertape_end_el($parser, $name)
			{
			    global $tag;
			    global $item;
			    global $items;
			    global $itemStart;
			    $tag = strtoupper($name);

			    switch ($tag)
			    {
			    	case "ITEM":
			    		$itemStart = false;
		    			$items[] = $item;
		    			$tag = '';
			    	break;
			    }
			}

	    	foreach($feeds as $feed)
	    	{
				$items = array();
	    		if (!empty($feed['rss']))
	    		{
					$xml = file_get_contents($feed['rss']);
					if (!empty($xml))
					{
						$encoding = $feed['encoding'];
						$xml_parser = xml_parser_create();
						xml_set_element_handler($xml_parser, $feed['prefix'] . "_start_el", $feed['prefix'] . "_end_el");
						xml_set_character_data_handler($xml_parser, $feed['prefix'] . "_data");

						if (!xml_parse($xml_parser, $xml))
						{
							echo xml_error_string(xml_get_error_code($xml_parser));
                    		echo xml_get_current_line_number($xml_parser);
						}
						xml_parser_free($xml_parser);
					}
			    }
			    else
			    {
			    	//ПАРСИМ СО СТРАНИЦЫ
					$content = file_get_contents($feed['url']);
					if ($feed['encoding'] <> 'utf-8')
					{
						$content = iconv($feed['encoding'], 'utf-8', $content);
					}
					if (!empty($content))
					{
						$feed['prefix']($content, $feed['url']);
					}
			    }

				if (!empty($items))
				{
//СОХРАНЕНИЕ В БД
					foreach ($items as $item)
					{
						$item['link'] = prepareLink($feed['url'], $item['link']);
						$dub = $this->News->find(array('News.link' => $item['link']));
						if (empty($dub))
						{
							if (empty($item['txt']) && empty($item['stxt']))
							{
								$item['txt'] = '';
								$item['stxt'] = $item['title'];
							}

							$item['title'] = Utils::substrWord(strip_tags($item['title']), 60);
							$a = '<br /><a href="' . $item['link'] . '">оригинал на сайте ' . $feed['partner'] . '</a><br /><br />';
							if (!empty($item['txt']))
							{
								$item['txt'] .= $a;
							}
							if (!empty($item['stxt']))
							{
								$item['stxt'] .= $a;
							}
							$item['img'] = '';
							$item['hidden'] = 0;
							$item['filesinfo'] = '';
							$item['matchesinfo'] = '';
							$item['direction_id'] = Configure::read('News.partnerDirectionId');
							$item['ftpdir'] = '';
							$item['poll_id'] = 0;
							$data = array('News' => $item);
/*
//echo $xml;
pr($data);
//exit;
//*/
							$this->News->create();
							$this->News->save($data);
						}
					}
				}
	    	}
    }

    /**
     * администрирование списка новостей
     * если указан $dir_id, то выводим список новостей категорим
     *
     * @param integer $dir_id - идентификатор направления (категории)
     */
    function admin_index($dir_id = 0) {

        $dirs = $this->Direction->findAll(null, null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);
    	$paginate = array(
    		'order' => 'created DESC'
    		);
    	if (!empty($dir_id))
    	{
    		$paginate['conditions']['direction_id'] = $dir_id;
    	}
        $this->set('lst', $this->News->find('all', $paginate));
    }

	function unlinkTempFiles($dir, $userid)
	{
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            if (ereg('^temp_' . $userid, $file))
		            {
		            	unlink($dir . '/' . $file);
		            }
		        }
		        closedir($dh);
		    }
		}
	}

	function findByPreview($dir, $name)
	{
		$result = '';
		if (is_dir($dir)) {
			$info = pathinfo($name);
			$name = ereg_replace('.' . $info['extension'] . '$', '', $name);
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            if (ereg('^' . $name, $file))
		            {
		            	$result = $file;
		            	break;
		            }
		        }
		        closedir($dh);
		    }
		}
		return $result;
	}

    function admin_edit($id = null) {
    	$uploadDir = Configure::read('App.webroot') . '/files/news';
    	$this->set('uploadDir', $uploadDir);

    	$dirs = $this->Direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
    	$this->set('dirs', $dirs);

        if (!empty($this->data['News']['id']))
    	{
	    	$id = $this->data['News']['id'];
    	}
        if (!empty($id)) {
            $newInfo = $this->News->read(null, $id);
            $this->set('info', $newInfo);
        }
        $this->set('authUser', $this->authUser);

        if (!empty($this->data)) {
        	if (!empty($this->data['picture']))
        	{
				$dir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
        		//ПЕРЕИМЕНОВЫВАЕМ ВРЕМЕННЫЕ ИМЕНА
        		$picture = $this->findByPreview($dir, $this->data['picture']);
				$picture = $dir . '/' . $picture;
				$info = pathinfo($picture);
				if (!empty($newInfo) && !empty($newInfo['News']['img']) && ($newInfo['News']['img'] <> $this->data['picture']))
				{
					//if (!empty($newInfo) && !empty($newInfo['News']['img']))
					{
						$old = $dir . '/' . $newInfo['News']['img'];
						//УДАЛЕНИЕ ПРЕДЫДУЩЕЙ КАРТИНКИ
						if (file_exists($old))
						{
							unlink($old);
						}

						$old = $dir . '/small/' . $newInfo['News']['img'];
						//УДАЛЕНИЕ ПРЕДЫДУЩЕЙ ПРЕВЬЮШКИ
						if (file_exists($old))
						{
							unlink($old);
						}
					}
        		}
	        	else
	        	{
	        		unset($this->data['News']['img']);
	        	}

				$newPicture = $dir . '/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];

				$preview = $dir . '/small/' . $this->data['picture'];
				$info = pathinfo($preview);
				$newPreview = $dir . '/small/' . $this->authUser['userid'] . '_' . time() . '.' . $info['extension'];

				if (file_exists($preview) &&
					(empty($this->data['News']['id'])
						|| (!empty($newInfo) && (basename($preview) != $newInfo['News']['img']))))
				{
					rename($preview, $newPreview);
					rename($picture, $newPicture);
        			$this->data['News']['img'] = basename($newPreview);
				}
        	}
        	else
        	{
        		unset($this->data['News']['img']);
        	}

        	if (empty($this->data['News']['id']))
        	{
        		$this->News->create();
        		$this->data['News']['img'] = '';
        		$this->data['News']['poll_id'] = 0;
        	}

        	$this->data['News']['modified'] = date('Y-m-d H:i:s');

        	//ВЫРЕЗАЕМ ТЭГИ ФОНТОВ
        	$this->data['News']['stxt'] = preg_replace('/<font face[^>]{1,}>/imU', '', $this->data['News']['stxt']);
        	$this->data['News']['stxt'] = preg_replace('/<font size[^>]{1,}>/imU', '', $this->data['News']['stxt']);
        	$this->data['News']['stxt'] = str_ireplace('</font>', '', $this->data['News']['stxt']);
        	$this->data['News']['txt'] = preg_replace('/<font face[^>]{1,}>/imU', '', $this->data['News']['txt']);
        	$this->data['News']['txt'] = preg_replace('/<font size[^>]{1,}>/imU', '', $this->data['News']['txt']);
        	$this->data['News']['txt'] = str_ireplace('</font>', '', $this->data['News']['txt']);
            if ($this->News->save($this->data)) {

            	if ($this->data['News']['id'])
            	{
            		cache::delete('News.ftpInfo.' . $this->data['News']['id'], 'rocket');
            	}

                $this->Session->setFlash(__('The New has been saved', true));
                $this->redirect(array('action'=>'index'));
            }
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for News', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->News->del($id)) {
            $this->Session->setFlash(__('News deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>