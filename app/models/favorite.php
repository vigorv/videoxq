<?php
App::import('Model', 'MediaModel');
class Favorite extends MediaModel {
    /*
     * Модель для Избранное - личного кабинета пользователя
     *
     */
    var $name = 'Favorite';

//------------------------------------------------------------------------------
    /*
     *  Добавление в избранное фильма
     *
     * @param integer $user_id - id пользователя
     * @param integer $film_id - id фильма
     * @param string $description - описание фильма (название)
     *
     * @return boolean $result - результат выполнения вставки (save)
     */
    function addToFavorites($user_id = null, $film_id = null , $description = null){
        $result = false;
        if (!empty($user_id) && $user_id && !empty($film_id) && $film_id){
            $new_data = array('Favorite' => array(
                'film_id' => $film_id,
                'user_id' => $user_id,
                'description' => $description
            ));

            $result = $this->save($new_data);
        }
        return $result;
    }

//------------------------------------------------------------------------------
    /*
     *  Удаление фильма из избранного
     *
     * @param integer $user_id - id пользователя
     * @param integer $film_id - id фильма
     *
     * @return boolean $result - результат выполнения вставки (save)
     */
    function removeFromFavorites($user_id = null, $film_id = null){
        $result = false;
        if (!empty($user_id) && $user_id && !empty($film_id) && $film_id){
            $sql = 'delete  from favorites
                            where
                            favorites.film_id='.$film_id.' AND
                            favorites.user_id='.$user_id;
            $result = $this->query($sql);
        }
        return $result;
    }

//------------------------------------------------------------------------------
    /*
     *  Проверка существования фильма в избранном
     *
     * @param integer $user_id - id пользователя
     * @param integer $film_id - id фильма
     *
     * @return boolean $result - результат выполнения проверки (true/false)
     */

    function checkExistFilmInFavorites($user_id = null, $film_id = null){
        $result = false;
        if (!empty($user_id) && $user_id && !empty($film_id) && $film_id){
            $result = $this->find('first',array(
                'conditions'=>array('film_id'=>$film_id, 'user_id'=>$user_id),
                'fields' => 'film_id'
                ));
        }
        return $result;
    }

//------------------------------------------------------------------------------
    /*
     * Выборка инфы об избранных фильмах для юзера с указанным id
     *
     * @param integer $user_id - id пользователя
     *
     * @return mixed $info - массив с данными
     */

    function getFavoritesFilmsInfo($user_id = null){
        $data = array();
        if (!empty($user_id) && $user_id){
            $data = $this->find('all',array(
                'conditions'=>array('user_id'=>$user_id),
                'fields' => array('film_id','description')
                ));
        }
        return $data;
    }

}
?>