<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Media
 *
 * @author snowing
 */
class Media extends AppModel {

    var $name = 'Films';

    function findlinks($film_id=null,$variantes=null) {
        App::import('Film', 'FilmVariant', 'FilmLink');
        if (!empty($film_id)) {
            $film=array();
            $variant = new FilmVariant();
            $variants =$variant->findall(array('FilmVariant.film_id'=>$film_id,'FilmVariant.active'=>1),null,null,1);
            $links = new FilmLink();
            $id=$variants['0']['FilmVariant']['id'];
            return $links->findall(array('FilmLink.id'=>$id));
        }
        return null;
    }

    function findfiles($film_id=null,$variantes=null) {
        App::import('Film', 'FilmVariant', 'FilmFile');
        if (!empty($film_id)) {
            $film=array();
            $variant = new FilmVariant();
            $variants =$variant->findall(array('FilmVariant.film_id'=>$film_id,'FilmVariant.active'=>1),null,null,1);
            $files = new FilmFile();
            $id=$variants['0']['FilmVariant']['id'];
            return $files->findall(array('FilmFile.film_variant_id'=>$id));
        }
        return null;
    }


}

?>














