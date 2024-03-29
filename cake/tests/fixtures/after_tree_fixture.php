<?php
/* SVN FILE: $Id: after_tree_fixture.php 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 * Short description for after_tree_fixture.php
 *
 * Long description for after_tree_fixture.php
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 * @link          http://www.cakephp.org
 * @package       cake
 * @subpackage    cake.tests.fixtures
 * @since         1.2
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:30:26 +0600 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * AdFixture class
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class AfterTreeFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'AfterTree'
 * @access public
 */
	var $name = 'AfterTree';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'parent_id' => array('type' => 'integer'),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('parent_id' => null, 'lft' => 1,  'rght' => 2, 'name' => 'One'),
		array('parent_id' => null, 'lft' => 3,  'rght' => 4, 'name' => 'Two'),
		array('parent_id' => null, 'lft' => 5,  'rght' => 6, 'name' => 'Three'),
		array('parent_id' => null, 'lft' => 7, 'rght' => 12, 'name' => 'Four'),
		array('parent_id' => null, 'lft' => 8,  'rght' => 9, 'name' => 'Five'),
		array('parent_id' => null, 'lft' => 10, 'rght' => 11, 'name' => 'Six'),
		array('parent_id' => null, 'lft' => 13, 'rght' => 14, 'name' => 'Seven')
	);
}
?>