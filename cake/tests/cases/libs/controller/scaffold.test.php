<?php
/* SVN FILE: $Id: scaffold.test.php 8276 2009-08-03 17:38:40Z mark_story $ */
/**
 * ScaffoldTest file
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
 * @subpackage    cake.tests.cases.libs.controller
 * @since         CakePHP(tm) v 1.2.0.5436
 * @version       $Revision: 8276 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2009-08-04 00:38:40 +0700 (Tue, 04 Aug 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', 'Scaffold');
/**
 * ScaffoldMockController class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldMockController extends Controller {
/**
 * name property
 *
 * @var string 'ScaffoldMock'
 * @access public
 */
	var $name = 'ScaffoldMock';
/**
 * scaffold property
 *
 * @var mixed
 * @access public
 */
	var $scaffold;
}
/**
 * ScaffoldMockControllerWithFields class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldMockControllerWithFields extends Controller {
/**
 * name property
 *
 * @var string 'ScaffoldMock'
 * @access public
 */
	var $name = 'ScaffoldMock';
/**
 * scaffold property
 *
 * @var mixed
 * @access public
 */
	var $scaffold;
/**
 * function _beforeScaffold
 *
 * @param string method
 */
	function _beforeScaffold($method) {
		$this->set('scaffoldFields', array('title'));
		return true;
	}
}
/**
 * TestScaffoldMock class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class TestScaffoldMock extends Scaffold {
/**
 * Overload __scaffold
 *
 * @param unknown_type $params
 */
    function __scaffold($params) {
        $this->_params = $params;
    }
/**
 * Get Params from the Controller.
 *
 * @return unknown
 */
    function getParams() {
        return $this->_params;
    }
}
/**
 * ScaffoldMock class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldMock extends CakeTestModel {
/**
 * useTable property
 *
 * @var string 'posts'
 * @access public
 */
	var $useTable = 'articles';
/**
 * belongsTo property
 *
 * @var array
 * @access public
 */
	var $belongsTo = array(
		'User' => array(
			'className' => 'ScaffoldUser',
			'foreignKey' => 'user_id',
		)
	);
/**
 * hasMany property
 *
 * @var array
 * @access public
 */
	var $hasMany = array(
		'Comment' => array(
			'className' => 'ScaffoldComment',
			'foreignKey' => 'article_id',
		)
	);
}
/**
 * ScaffoldUser class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldUser extends CakeTestModel {
/**
 * useTable property
 *
 * @var string 'posts'
 * @access public
 */
	var $useTable = 'users';
/**
 * hasMany property
 *
 * @var array
 * @access public
 */
	var $hasMany = array(
		'Article' => array(
			'className' => 'ScaffoldMock',
			'foreignKey' => 'article_id',
		)
	);
}
/**
 * ScaffoldComment class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldComment extends CakeTestModel {
/**
 * useTable property
 *
 * @var string 'posts'
 * @access public
 */
	var $useTable = 'comments';
/**
 * belongsTo property
 *
 * @var array
 * @access public
 */
	var $belongsTo = array(
		'Article' => array(
			'className' => 'ScaffoldMock',
			'foreignKey' => 'article_id',
		)
	);
}
/**
 * TestScaffoldView class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class TestScaffoldView extends ScaffoldView {
/**
 * testGetFilename method
 *
 * @param mixed $action
 * @access public
 * @return void
 */
	function testGetFilename($action) {
		return $this->_getViewFileName($action);
	}
}
/**
 * ScaffoldViewTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldViewTest extends CakeTestCase {
/**
 * fixtures property
 *
 * @var array
 * @access public
 */
	var $fixtures = array('core.article', 'core.user', 'core.comment');
/**
 * startTest method
 *
 * @access public
 * @return void
 */
	function startTest() {
		$this->Controller =& new ScaffoldMockController();
	}
/**
 * endTest method
 *
 * @access public
 * @return void
 */
	function endTest() {
		unset($this->Controller);
	}
/**
 * testGetViewFilename method
 *
 * @access public
 * @return void
 */
	function testGetViewFilename() {
		$_admin = Configure::read('Routing.admin');
		Configure::write('Routing.admin', 'admin');

		$this->Controller->action = 'index';
		$ScaffoldView =& new TestScaffoldView($this->Controller);
		$result = $ScaffoldView->testGetFilename('index');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'index.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('edit');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('add');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('view');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'view.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('admin_index');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'index.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('admin_view');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'view.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('admin_edit');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('admin_add');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'libs' . DS . 'view' . DS . 'scaffolds' . DS . 'edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('error');
		$expected = 'cake' . DS . 'libs' . DS . 'view' . DS . 'errors' . DS . 'scaffold_error.ctp';
		$this->assertEqual($result, $expected);

		$_back = array(
			'viewPaths' => Configure::read('viewPaths'),
			'pluginPaths' => Configure::read('pluginPaths'),
		);
		Configure::write('viewPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'views' . DS));
		Configure::write('pluginPaths', array(TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins' . DS));

		$Controller =& new ScaffoldMockController();
		$Controller->scaffold = 'admin';
		$Controller->viewPath = 'posts';
		$Controller->action = 'admin_edit';
		$ScaffoldView =& new TestScaffoldView($Controller);
		$result = $ScaffoldView->testGetFilename('admin_edit');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' .DS . 'views' . DS . 'posts' . DS . 'scaffold.edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('edit');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' .DS . 'views' . DS . 'posts' . DS . 'scaffold.edit.ctp';
		$this->assertEqual($result, $expected);

		$Controller =& new ScaffoldMockController();
		$Controller->scaffold = 'admin';
		$Controller->viewPath = 'tests';
		$Controller->plugin = 'test_plugin';
		$Controller->action = 'admin_add';
		$ScaffoldView =& new TestScaffoldView($Controller);
		$result = $ScaffoldView->testGetFilename('admin_add');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins'
			. DS .'test_plugin' . DS . 'views' . DS . 'tests' . DS . 'scaffold.edit.ctp';
		$this->assertEqual($result, $expected);

		$result = $ScaffoldView->testGetFilename('add');
		$expected = TEST_CAKE_CORE_INCLUDE_PATH . 'tests' . DS . 'test_app' . DS . 'plugins'
			. DS .'test_plugin' . DS . 'views' . DS . 'tests' . DS . 'scaffold.edit.ctp';
		$this->assertEqual($result, $expected);

		Configure::write('viewPaths', $_back['viewPaths']);
		Configure::write('pluginPaths', $_back['pluginPaths']);
		Configure::write('Routing.admin', $_admin);
	}
/**
 * test default index scaffold generation
 *
 * @access public
 * @return void
 **/
	function testIndexScaffold() {
		$this->Controller->action = 'index';
		$this->Controller->here = '/scaffold_mock';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'scaffold_mock'),
			'controller' => 'scaffold_mock',
			'action' => 'index',
		);
		//set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();
		ob_start();
		new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertPattern('#<h2>Scaffold Mock</h2>#', $result);
		$this->assertPattern('#<table cellpadding="0" cellspacing="0">#', $result);
		//TODO: add testing for table generation
		$this->assertPattern('#<a href="/scaffold_users/view/1">1</a>#', $result); //belongsTo links
		$this->assertPattern('#<li><a href="/scaffold_mock/add/">New Scaffold Mock</a></li>#', $result);
		$this->assertPattern('#<li><a href="/scaffold_users/">List Scaffold Users</a></li>#', $result);
		$this->assertPattern('#<li><a href="/scaffold_comments/add/">New Comment</a></li>#', $result);
	}
/**
 * test default view scaffold generation
 *
 * @access public
 * @return void
 **/
	function testViewScaffold() {
		$this->Controller->action = 'view';
		$this->Controller->here = '/scaffold_mock';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(1),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'scaffold_mock'),
			'controller' => 'scaffold_mock',
			'action' => 'view',
		);
		//set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();

		ob_start();
		new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertPattern('/<h2>View Scaffold Mock<\/h2>/', $result);
		$this->assertPattern('/<dl>/', $result);
		//TODO: add specific tests for fields.
		$this->assertPattern('/<a href="\/scaffold_users\/view\/1">1<\/a>/', $result); //belongsTo links
		$this->assertPattern('/<li><a href="\/scaffold_mock\/edit\/1">Edit Scaffold Mock<\/a>\s<\/li>/', $result);
		$this->assertPattern('/<li><a href="\/scaffold_mock\/delete\/1"[^>]*>Delete Scaffold Mock<\/a>\s*<\/li>/', $result);
		//check related table
		$this->assertPattern('/<div class="related">\s*<h3>Related Scaffold Comments<\/h3>\s*<table cellpadding="0" cellspacing="0">/', $result);
		$this->assertPattern('/<li><a href="\/scaffold_comments\/add\/">New Comment<\/a><\/li>/', $result);
	}
/**
 * test default view scaffold generation
 *
 * @access public
 * @return void
 **/
	function testEditScaffold() {
		$this->Controller->action = 'edit';
		$this->Controller->here = '/scaffold_mock';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(1),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'scaffold_mock'),
			'controller' => 'scaffold_mock',
			'action' => 'edit',
		);
		//set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();
		ob_start();
		new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertPattern('/<form id="ScaffoldMockEditForm" method="post" action="\/scaffold_mock\/edit\/1">/', $result);
		$this->assertPattern('/<legend>Edit Scaffold Mock<\/legend>/', $result);

		$this->assertPattern('/input type="hidden" name="data\[ScaffoldMock\]\[id\]" value="1" id="ScaffoldMockId"/', $result);
		$this->assertPattern('/input name="data\[ScaffoldMock\]\[user_id\]" type="text" maxlength="11" value="1" id="ScaffoldMockUserId"/', $result);
		$this->assertPattern('/input name="data\[ScaffoldMock\]\[title\]" type="text" maxlength="255" value="First Article" id="ScaffoldMockTitle"/', $result);
		$this->assertPattern('/input name="data\[ScaffoldMock\]\[published\]" type="text" maxlength="1" value="Y" id="ScaffoldMockPublished"/', $result);
		$this->assertPattern('/textarea name="data\[ScaffoldMock\]\[body\]" cols="30" rows="6" id="ScaffoldMockBody"/', $result);
		$this->assertPattern('/<li><a href="\/scaffold_mock\/delete\/1"[^>]*>Delete<\/a>\s*<\/li>/', $result);
	}
/**
 * Test Admin Index Scaffolding.
 *
 * @access public
 * @return void
 **/
	function testAdminIndexScaffold() {
		$_backAdmin = Configure::read('Routing.admin');

		Configure::write('Routing.admin', 'admin');
		$params = array(
			'plugin' => null,
			'pass' => array(),
			'form' => array(),
			'named' => array(),
			'prefix' => 'admin',
			'url' => array('url' =>'admin/scaffold_mock'),
			'controller' => 'scaffold_mock',
			'action' => 'admin_index',
			'admin' => 1,
		);
		//reset, and set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/admin/scaffold_mock', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->action = 'admin_index';
		$this->Controller->here = '/tests/admin/scaffold_mock';
		$this->Controller->webroot = '/';
		$this->Controller->scaffold = 'admin';
		$this->Controller->constructClasses();

		ob_start();
		$Scaffold = new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertPattern('/<h2>Scaffold Mock<\/h2>/', $result);
		$this->assertPattern('/<table cellpadding="0" cellspacing="0">/', $result);
		//TODO: add testing for table generation
		$this->assertPattern('/<li><a href="\/admin\/scaffold_mock\/add\/">New Scaffold Mock<\/a><\/li>/', $result);

		Configure::write('Routing.admin', $_backAdmin);
	}
/**
 * Test Admin Index Scaffolding.
 *
 * @access public
 * @return void
 **/
	function testAdminEditScaffold() {
		$_backAdmin = Configure::read('Routing.admin');

		Configure::write('Routing.admin', 'admin');
		$params = array(
			'plugin' => null,
			'pass' => array(),
			'form' => array(),
			'named' => array(),
			'prefix' => 'admin',
			'url' => array('url' =>'admin/scaffold_mock/edit'),
			'controller' => 'scaffold_mock',
			'action' => 'admin_edit',
			'admin' => 1,
		);
		//reset, and set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/admin/scaffold_mock/edit', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->action = 'admin_index';
		$this->Controller->here = '/tests/admin/scaffold_mock';
		$this->Controller->webroot = '/';
		$this->Controller->scaffold = 'admin';
		$this->Controller->constructClasses();

		ob_start();
		$Scaffold = new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertPattern('#admin/scaffold_mock/edit/1#', $result);
		$this->assertPattern('#Scaffold Mock#', $result);

		Configure::write('Routing.admin', $_backAdmin);
	}
}
/**
 * Scaffold Test class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.controller
 */
class ScaffoldTest extends CakeTestCase {
/**
 * Controller property
 *
 * @var SecurityTestController
 * @access public
 */
	var $Controller;
/**
 * fixtures property
 *
 * @var array
 * @access public
 */
	var $fixtures = array('core.article', 'core.user', 'core.comment');
/**
 * startTest method
 *
 * @access public
 * @return void
 */
	function startTest() {
		$this->Controller =& new ScaffoldMockController();
	}
/**
 * endTest method
 *
 * @access public
 * @return void
 */
	function endTest() {
		unset($this->Controller);
	}
/**
 * Test the correct Generation of Scaffold Params.
 * This ensures that the correct action and view will be generated
 *
 * @access public
 * @return void
 */
	function testScaffoldParams() {
		$this->Controller->action = 'admin_edit';
		$this->Controller->here = '/admin/scaffold_mock/edit';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'admin/scaffold_mock/edit'),
			'controller' => 'scaffold_mock',
			'action' => 'admin_edit',
			'admin' => true,
		);
		//set router.
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => 'admin/scaffold_mock', 'webroot' => '/')));

		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();
		$Scaffold =& new TestScaffoldMock($this->Controller, $params);
		$result = $Scaffold->getParams();
		$this->assertEqual($result['action'], 'admin_edit');
	}
/**
 * test that the proper names and variable values are set by Scaffold
 *
 * @return void
 **/
	function testScaffoldVariableSetting() {
		$this->Controller->action = 'admin_edit';
		$this->Controller->here = '/admin/scaffold_mock/edit';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'admin/scaffold_mock/edit'),
			'controller' => 'scaffold_mock',
			'action' => 'admin_edit',
			'admin' => true,
		);
		//set router.
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => 'admin/scaffold_mock', 'webroot' => '/')));

		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();
		$Scaffold =& new TestScaffoldMock($this->Controller, $params);
		$result = $Scaffold->controller->viewVars;

		$this->assertEqual($result['singularHumanName'], 'Scaffold Mock');
		$this->assertEqual($result['pluralHumanName'], 'Scaffold Mock');
		$this->assertEqual($result['modelClass'], 'ScaffoldMock');
		$this->assertEqual($result['primaryKey'], 'id');
		$this->assertEqual($result['displayField'], 'title');
		$this->assertEqual($result['singularVar'], 'scaffoldMock');
		$this->assertEqual($result['pluralVar'], 'scaffoldMock');
		$this->assertEqual($result['scaffoldFields'], array('id', 'user_id', 'title', 'body', 'published', 'created', 'updated'));
	}
/**
 * test that the proper names and variable values are set by Scaffold
 *
 * @return void
 **/
	function testEditScaffoldWithScaffoldFields() {
		$this->Controller = new ScaffoldMockControllerWithFields();
		$this->Controller->action = 'edit';
		$this->Controller->here = '/scaffold_mock';
		$this->Controller->webroot = '/';
		$params = array(
			'plugin' => null,
			'pass' => array(1),
			'form' => array(),
			'named' => array(),
			'url' => array('url' =>'scaffold_mock'),
			'controller' => 'scaffold_mock',
			'action' => 'edit',
		);
		//set router.
		Router::reload();
		Router::setRequestInfo(array($params, array('base' => '/', 'here' => '/scaffold_mock', 'webroot' => '/')));
		$this->Controller->params = $params;
		$this->Controller->controller = 'scaffold_mock';
		$this->Controller->base = '/';
		$this->Controller->constructClasses();
		ob_start();
		new Scaffold($this->Controller, $params);
		$result = ob_get_clean();

		$this->assertNoPattern('/textarea name="data\[ScaffoldMock\]\[body\]" cols="30" rows="6" id="ScaffoldMockBody"/', $result);
	}
}
?>