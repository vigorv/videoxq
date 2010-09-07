<?php
/* SVN FILE: $Id: error404.ctp 7847 2008-11-08 02:54:07Z renan.saddam $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7847 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-08 08:54:07 +0600 (Sat, 08 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php __('Error'); ?>: </strong>
	<?php echo sprintf(__("The requested address %s was not found on this server.", true), "<strong>'{$message}'</strong>")?>
</p>