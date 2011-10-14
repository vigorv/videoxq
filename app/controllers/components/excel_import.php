<?php
App::import('Vendor','PHPExcel',array('file' => 'excel/PHPExcel.php'));
class ExcelImportComponent extends Object {

    var $controller = true;
    //var $disableStartup = true;

//------------------------------------------------------------------------------

    //вызывается перед Controller::beforeFilter()
    function initialize(&$controller, $settings = array()) {
        // инициализируем компонент
        // сохранение ссылки на контроллер для последующего использования
        $this->controller =& $controller;
        //подключаем необходимые модели
        if (empty($this->Copyrightholder))
            $this->Copyrightholder = $this->getModel('Copyrightholder');
        if (empty($this->CopyrightholdersFilm))
            $this->CopyrightholdersFilm = $this->getModel('CopyrightholdersFilm');
    }

//------------------------------------------------------------------------------

    //вызывается после Controller::beforeFilter()
    function startup(&$controller) {

    }

//------------------------------------------------------------------------------

    //вызывается после Controller::beforeRender()
    function beforeRender(&$controller) {

    }

//------------------------------------------------------------------------------

    //вызывается после Controller::render()
    function shutdown(&$controller) {

    }

//------------------------------------------------------------------------------

    //вызывается перед Controller::redirect()
    function beforeRedirect(&$controller, $url, $status=null, $exit=true) {

    }

//------------------------------------------------------------------------------

    function redirectSomewhere($value) {
        // вызов метода контроллера
        $this->controller->redirect($value);
    }

//------------------------------------------------------------------------------

    function test($value) {
        echo "TEST: ";
        if (!$value){
            echo $value;
        }
    }

//------------------------------------------------------------------------------

    function &getModel($name = null)
    {
        $model = null;

        if (PHP5)
            $model = ClassRegistry::init($name);
        else
            $model =& ClassRegistry::init($name);

        return $model;
    }

//------------------------------------------------------------------------------

     function importCopyrightholders($options=array()) {
/* ---------------------------------------------/
 * В вызывающем контроллере должны быть подключены модели
 * 'Copyrightholder','CopyrightholdersFilm'
 */
        //-------------------------------------------------
        // входные параметры:
        //------------------
        //параметры импорта строк "все" / "с" / "по"
        $all = $options['all'];
        $from = $options['from'];
        $to = $options['to'];
        //имя файла-источника для импорта
        $file_name = $options['file_name'];
        //удалять ли файл после импорта
        $delete_file_after_import = $options['delete_file_after_import'];
        //выстота шапки таблицы excel (по умолч. = 1 строка)
        $header_size = $options['header_size'];
        //номер колонки с имененм правообладателя
        $cname_col = $options['cname_col'];
        //номер колонки с сылкой на фильм (там есть id фильма)
        $film_link_col = $options['film_link_col'];
        //возвращаемый массив с результатом;
        $data = array();
        //событие импорта - пока небыло
        $import_event = false;
        //число проанализированных строк
        $count_analysed_rows = 0;
        //число импортируемых связей
        $count_imported_links = 0;
        //инициализируем список импортированных правообладателей для вывода
        $imported_list = array();
        //----------------------




        if ($file_name){
            //отключим кэш запросов кейка - очень мешает смотеть только что
            //вставленные записи
            $this->Copyrightholder->cacheQueries = false;

/* Алгоритм импорта из Excel
 * -------------------------
 * 1. чтение файла Excel
 * 2. импорт всех его строк в массив $temp[col_1, col_2, col_2...]
 * 3. создание списка $cnames[copyrightholders_names, film_id]
 * 4. импорт в БД новых "copyrightholders_names" из $cnames[]
 * 5. анализ $cnames[], отбрасываем неполные пары
 * 6. создание списка $links[copyrightholders_id, film_id]
 * 7. удаление связей "copyrightholders_id, film_id" из БД для всех
 * правообладателей из списка $links[]
 * 8. добавление новых связей "copyrightholders_id, film_id" в БД из для всех
 * правообладателей из списка $links[]
 * -------------------------
 */

            // 1. чтение файла Excel
            $reader = new PHPExcel_Reader_Excel5();
            $reader->setReadDataOnly(true);
            $excel = $reader->load($file_name);
            // 2. импорт всех его строк в массив $temp[col_1, col_2, col_2...]
            $temp = $excel->getActiveSheet()->toArray();
            if (file_exists($file_name) && $delete_file_after_import){
                //удалим загруженый файл - он больше не нужен
                //соблюдаем чистоту на рабочем месте!!!! :)
                @unlink($file_name);
            }
            //флаг факта импорта файла включим!
            $import_event = true;
            $cnames = array();
            //счетчик строк (всех, включая шапку и пустые)
            $n = 0;

            // если массив с данными для импорта не пустой,
            // то анализируем его, и импортируем
            if(!empty($temp)){

                //3. создание списка $cnames[copyrightholders_names, film_id]
                foreach($temp as $key=>$row){
                    //увеличим счетчик проанализированных строк
                        $n++;
                    if ($row) {
                        // если начались пустые строки, то прекращаем анализ
                        if (!trim(implode('',$row))) {break;}
                        //пропускаем 1ю строку - шапка таблицы (это шапка таблицы)
                        if (($n-$header_size)==0) continue;
                        //если в параметрах указано "с" "по"
                        if (!$all){
                            //делаем поправку на шапку таблицы (ее высота у нас 1 строка -> $n-1)
                            //пропустим все строки до указанной "с"
                            if (($n-$header_size) < $from) continue;
                            //остановим анализ если достигли "по"
                            if (($n-$header_size) > $to) break;
                        }
                        //уберем двойные пробелы в поле "cname"
                        $cname = preg_replace('/\s/', ' ', trim($row[$cname_col]));

                        //вычислим film_id из ссылки на фильм .../24324 (fid)
                        preg_match('/(\/+)([0-9]+)$/', trim($row[$film_link_col]), $matches);
                        //если id фильма нет (нет сссылки на файл), fid=0
                        $fid = (!empty($matches[2]) && $matches[2])? $matches[2] : 0;
                        //если имя правообладателя не пустое то внесем его в список
                        if ($cname){
                            $cnames[] = array('cname' => $cname, 'film_id'=>$fid);
                        }
                        //увеличим число проанализированных строк
                        $count_analysed_rows++;
                    }


                }

                // 4. импорт в БД новых "copyrightholders_names" из $cnames[]
                foreach ($cnames as $key => $row ){
                    //узнаем id правообладателя по его имени, если его нет в БД,
                    //то вставим его в БД и все равно узнаем id, установив флаг 'new'
                    $result = $this->Copyrightholder->getCopyrightholderIdByName($row['cname']);
                    $cid = $result['cid'];

                    //если это новый праообладатель, которого не было в БД,
                    if ($result['new']){
                        //то увеличим число импортированных правообладателей
                        //(хотя оно не нужно, можно вычислить в конце count($imported_list))
                        //$count_imported_copyrightholders++;

                        //заполняем список импортированых правообладателей для вывода на экран
                        $imported_list[] = array('cid'=>$cid, 'cname'=>$row['cname']);
                    }

                    // 5. анализ $cnames[], отбрасываем неполные пары.
                    //'film_id' может быть равным 0, если ссылка на фильм была
                    //неверная или пустая
                    if(!$cid || !$row['film_id']){
                        unset ($cnames[$key]);
                    }
                    else{
                        // 6. создание списка $links[copyrightholders_id, film_id]
                        //список нужен для создания связей, используем тотже массив
                        //$cnames для экономии ресурсов (мало ли какой огромный список будет :))
                        //удалив из него поле 'cname'
                        unset ($cnames[$key]['cname']);
                        $cnames[$key]['copyrightholder_id'] = $cid;
                    }
                }
                // теперь массив $cnames[] - это список связей для импорта
                //
                //число импортируемых связей
                $count_imported_links = count($cnames);
                // 7. удаление связей "copyrightholders_id, film_id" из БД для
                // всех правообладателей из списка $links[]
                foreach($cnames as $row){
                    $this->Copyrightholder->deleleLinksForCopyrightholder($row['copyrightholder_id']);
                }

                //8. добавление новых связей "copyrightholders_id, film_id" в
                //БД из для всех правообладателей из списка $links[]
                //в нашем случае вместо $links используем $cnames
                $this->CopyrightholdersFilm->saveAll($cnames);
                //очистим после себя :)))))
                unset($cnames);

            }

        }

        $data = array();
        $data['count_analysed_rows'] = $count_analysed_rows;
        $data['count_imported_links'] = $count_imported_links;
        $data['imported_list'] = $imported_list;
        $data['import_event'] = $import_event;

        return $data;
    }

//------------------------------------------------------------------------------

}
?>