<?php
class Categoriez extends AppModel {

    var $name = 'Categoriez';
    var $actsAs = array('Tree');

}

/*
CREATE TABLE categoriezs (
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	parent_id INTEGER(10) DEFAULT NULL,
	lft INTEGER(10) DEFAULT NULL,
	rght INTEGER(10) DEFAULT NULL,
	name VARCHAR(255) DEFAULT '',
	PRIMARY KEY  (id)
);

INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(1, 'My categoriezs', NULL, 1, 30);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(2, 'Fun', 1, 2, 15);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(3, 'Sport', 2, 3, 8);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(4, 'Surfing', 3, 4, 5);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(5, 'Extreme knitting', 3, 6, 7);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(6, 'Friends', 2, 9, 14);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(7, 'Gerald', 6, 10, 11);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(8, 'Gwendolyn', 6, 12, 13);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(9, 'Work', 1, 16, 29);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(10, 'Reports', 9, 17, 22);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(11, 'Annual', 10, 18, 19);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(12, 'Status', 10, 20, 21);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(13, 'Trips', 9, 23, 28);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(14, 'National', 13, 24, 25);
INSERT INTO `categoriezs` (`id`, `name`, `parent_id`, `lft`, `rght`) VALUES(15, 'International', 13, 26, 27);

*/
?>