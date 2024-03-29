<?php
/* SVN FILE: $Id: uuiditem_fixture.php 8116 2009-03-18 17:55:58Z davidpersson $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.4667
 * @version       $Revision: 8116 $
 * @modifiedby    $LastChangedBy: davidpersson $
 * @lastmodified  $Date: 2009-03-18 23:55:58 +0600 (Wed, 18 Mar 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class UuiditemFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'Uuiditem'
 * @access public
 */
	var $name = 'Uuiditem';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'string', 'length' => 36, 'key' => 'primary'),
		'published' => array('type' => 'boolean', 'null' => false),
		'name' => array('type' => 'string', 'null' => false)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('id' => '481fc6d0-b920-43e0-a40d-6d1740cf8569', 'published' => 0, 'name' => 'Item 1'),
		array('id' => '48298a29-81c0-4c26-a7fb-413140cf8569', 'published' => 0, 'name' => 'Item 2'),
		array('id' => '482b7756-8da0-419a-b21f-27da40cf8569', 'published' => 0, 'name' => 'Item 3'),
		array('id' => '482cfd4b-0e7c-4ea3-9582-4cec40cf8569', 'published' => 0, 'name' => 'Item 4'),
		array('id' => '4831181b-4020-4983-a29b-131440cf8569', 'published' => 0, 'name' => 'Item 5'),
		array('id' => '483798c8-c7cc-430e-8cf9-4fcc40cf8569', 'published' => 0, 'name' => 'Item 6')
	);
}
?>