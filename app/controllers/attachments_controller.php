<?php
// For ADMINISTRATION of attachments
class AttachmentsController extends AppController {

	function _rmdir_r($path) {
		if (!is_dir($path)) {
			return false;
		}
		$stack = Array($path);
		while ($dir = array_pop($stack)) {
			if (@rmdir($dir)) {
				continue;
			}
			$stack[] = $dir;
			$dh = opendir($dir);
			while (($child = readdir($dh)) !== false) {
				if ($child[0] == '.') {
					continue;
				}
				$child = $dir . DIRECTORY_SEPARATOR . $child;
				if (is_dir($child)) {
					$stack[] = $child;
				} else {
					unlink($child);
				}
			}
		}
		return true;
	}

	function beforeRender() {
		if (!isset ($this->viewVars['data'])) {
			$this->set('data', $this->data);
		}
	}

	function admin_clear_webroot($type = 'img') {
		$folder = new Folder (WWW_ROOT . $type);
		$data = $folder->read();
		list($dirs, $files) = $data;
		$found =  array();
		foreach ($dirs as $dir) {
			if (preg_match("/Articles|Event|Blog|[0-9]{1,4}x[0-9]{1,4}$/i", $dir)) {
				$found[] = $dir;
				$this->_rmdir_r (WWW_ROOT . $type . DS . $dir);
			}
		}
		if ($type == 'img') {
			// Use a redirect incase there is a lot of work to do.
			$this->redirect(array('files'),null, true);
		} else {
			$this->redirect(array('action' => 'index'),null, true);
		}
	}

	function admin_add ($class = null, $foreignKey = null) {
		if (!$class||!$foreignKey) {
			$this->Session->setFlash('Can\'t add a new file as it can\'t be associated with anything');
			$this->redirect($this->referer(array('action' => 'index'), null, true));
		}
		$this->Attachment->bindModel(array('belongsTo' => array( $class => array (
			'class' => $class,
			'conditions' => array('Attachment.class' => $class)
		))));
		$pClass = Inflector::pluralize($class);	
		if ($this->data) {
			$this->data['Attachment']['foreign_id'] = $foreignKey;
			$this->data['Attachment']['class'] = $class; 
			if ($this->Attachment->save($this->data)) {
				$this->Session->setFlash('New Attachment for ' . $class . ' id ' . $this->data['Attachment']['foreign_id'] . ' added.');
				$this->redirect(array(
					'controller' => Inflector::underscore(Inflector::Pluralize($class)),
					'action' => 'edit',
					$this->data['Attachment']['foreign_id']),
			       null, true);
			}
		}
		$this->set('foreignClass',$class);
		$this->set('foreigns',$this->Attachment->$class->generateList());
		$this->render('admin_edit');
	}

	function admin_edit($id) {
		$this->Attachment->enableUpload(false);
		if (!empty ($this->data)) {
			if ($this->{$this->modelClass}->save($this->data)) {
				$this->Session->setFlash($this->modelClass . ' updated');
				$this->redirect(array ('action' => 'index'), null, true);
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		} else {
			$this->data = $this->{$this->modelClass}->read(null, $id);
		}
		$foreignClass = $this->data['Attachment']['class'];
		$foreignIds = $this->Attachment->$foreignClass->generateList();
		$this->set(compact('foreignClass', 'foreignIds'));
		$this->render('admin_edit');
	}

	function admin_set_thumb ($thumbId, $thumb = 0) {
		$data = $this->Attachment->read(array('class', 'foreign_id'), $thumbId);
		$constraint = $data['Attachment'];
		$this->Attachment->updateAll(array('thumb' => 0), $constraint);
		$this->Attachment->saveField('thumb', $thumb);
		$this->redirect($this->referer(), null, true);
	}
}
?>
