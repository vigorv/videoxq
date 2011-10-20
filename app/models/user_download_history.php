<?php

class UserDownloadHistory extends AppModel {

    var $name = 'UserDownloadHistory';
    var $useTable = 'userdownloadhistory';

    /**
     *
     * @param int $id
     * @param int $offset
     * @param int $count
     * @return $history
     */
    function GetHistoryForUser($id, $page=1, $count=50) {
        if ($id) {
            $offset = (int) ($page - 1) * $count;
            if ($offset < 0)
                $offset = 0;
            /*$history = $this->query('SELECT * FROM film_clicks
                    Left JOIN films as Film ON Film.id = film_clicks.film_id
                    WHERE user_id =' . $id . ' LIMIT ' . $offset . ',' . $count);*/
            $history = $this->query('SELECT * FROM film_clicks
                    WHERE user_id =' . $id . ' LIMIT ' . $offset . ',' . $count);
/* //SNOW CODE
            App::import('FilmFast');
            $filmfast= new FilmFast;
            foreach ($history as &$hinfo){
                $hinfo['film']=$filmfast->GetFilmOv($hinfo['film_clicks']['film_id'],1);
            }
//*/

//* //VANO CODE
            App::import('Film');
            $film = new Film;
            foreach ($history as &$hinfo){
                $hinfo['film'] = $film->getShortFilmInfo($hinfo['film_clicks']['film_id']);
            }
//*/
            return $history;

        }
    }

    function GetHistoryCountForUser($id){
        if ($id) {
            $historycount = $this->query('SELECT COUNT("id") FROM film_clicks
                    WHERE user_id =' . $id );
            return $historycount[0][0]['COUNT("id")'];
        }
    }

}