<?php
App::import('Model', 'MediaModel');
class Basket extends MediaModel {

    var $name = 'Basket';
    var $belongsTo = array(
//            'User' => array('className' => 'User',
//                            'foreignKey' => 'user_id',
//                            'conditions' => '',
//                            'fields' => '',
//                            'order' => ''
//            ),
            'Film' => array('className' => 'Film',
                            'foreignKey' => 'film_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            ),
            'FilmVariant' => array('className' => 'FilmVariant',
                            'foreignKey' => 'film_variant_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            ),
            'FilmFile' => array('className' => 'FilmFile',
                            'foreignKey' => 'film_file_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            )

    );

    var $actsAs = array('Containable');


    /**
     * Получаем список фильмов в корзине
     *
     * @param array $ids
     * @param int $userId
     * @return unknown
     */
    function getBasketsByVariantIds($ids, $userId)
    {
        $db = $this->getDataSource();
        $sql = 'SELECT `Basket`.`id`, `Film`.`dir`, `FilmFile`.`file_name`, `VideoType`.`dir`
                FROM `baskets` AS `Basket`
                LEFT JOIN `films` AS `Film` ON (`Basket`.`film_id` = `Film`.`id`)
                LEFT JOIN `film_variants` AS `FilmVariant` ON (`Basket`.`film_variant_id` = `FilmVariant`.`id`)
                LEFT JOIN `film_files` AS `FilmFile` ON (`Basket`.`film_file_id` = `FilmFile`.`id`)
                LEFT JOIN `video_types` AS `VideoType` ON (`FilmVariant`.`video_type_id` = `VideoType`.`id`)
                WHERE `FilmVariant`.`id`
                IN (' . implode(',', $db->value($ids, 'integer')) . ')
                AND `user_id` = ' . $db->value($userId, 'integer') . '
                ORDER BY find_in_set(`FilmVariant`.`id`, "' . implode(',', $db->value($ids, 'integer')) . '")';

        return $this->query($sql);
    }


    /**
     * Список фильмов в корзине для определенного юзера
     *
     * @param int $userId
     * @return unknown
     */
    function getBasketList($userId)
    {
        $db = $this->getDataSource();
        $sql ='SELECT `Basket`.`id`, `Basket`.`title`, COUNT(`Basket`.`id`) AS count, SUM(FilmFile.size) AS size, `Film`.`id`, `Film`.`imdb_rating`,
                       `FilmVariant`.`id`, `FilmFile`.`id`, `MediaRating`.`rating`, `Film`.`description`,
                        (SELECT file_name from `film_pictures` WHERE  `film_pictures`.`film_id` = `Basket`.`film_id` AND `film_pictures`.type = "poster" LIMIT 1) AS Poster
                 FROM `baskets` AS `Basket`
                 LEFT JOIN `films` AS `Film` ON (`Basket`.`film_id` = `Film`.`id`)
                 LEFT JOIN `media_ratings` AS `MediaRating` ON (`Basket`.`film_id` = `MediaRating`.`object_id` AND `MediaRating`.`type` = "film")
                 LEFT JOIN `film_variants` AS `FilmVariant` ON (`Basket`.`film_variant_id` = `FilmVariant`.`id`)
                 LEFT JOIN `film_files` AS `FilmFile` ON (`Basket`.`film_file_id` = `FilmFile`.`id`)
                 WHERE `user_id` = ' . $db->value($userId, 'integer') . '
                 GROUP BY `Basket`.`title`';
        return $this->query($sql);
    }

}
?>