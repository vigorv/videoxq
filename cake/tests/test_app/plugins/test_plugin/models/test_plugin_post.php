<?php
/* SVN FILE: $Id: test_plugin_post.php 7848 2008-11-08 02:58:37Z renan.saddam $ */
/**
 * Test Plugin Post Model
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
 * @subpackage    cake.cake.tests.test_app.plugins.test_plugin
 * @since         CakePHP v 1.2.0.4487
 * @version       $Revision: 7848 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-08 08:58:37 +0600 (Sat, 08 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class TestPluginPost extends TestPluginAppModel {
/**
 * Name property
 *
 * @var string
 */
	var $name = 'Post';
/**
 * useTable property
 *
 * @var string
 */
	var $useTable = 'posts';
}