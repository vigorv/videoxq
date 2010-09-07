<?php
/* SVN FILE: $Id: persister_one.php 8119 2009-03-19 20:10:10Z gwoo $ */
/**
 * Test App Comment Model
 *
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package       cake
 * @subpackage    cake.tests.test_app.models
 * @since         CakePHP v 1.2.0.7726
 * @version       $Revision: 8119 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2009-03-20 02:10:10 +0600 (Fri, 20 Mar 2009) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class PersisterOne extends AppModel {
	var $useTable = 'posts';
	var $name = 'PersisterOne';

	var $actsAs = array('PersisterOneBehavior');

	var $hasMany = array('Comment');
}
?>