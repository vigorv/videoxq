<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../' . CAKE . 'libs/model/behaviors/acl.php');

class Acl2Behavior extends AclBehavior
{
	function afterSave(&$model, $created) {
		if ($created) {
			$type = $this->__typeMaps[strtolower($this->settings[$model->name]['type'])];
			$parent = $model->parentNode();
			if (!empty($parent)) {
				$parent = $this->node($model, $parent);
			} else {
				$parent = null;
			}
		}
	}

	function afterDelete(&$model) {
	}
}
