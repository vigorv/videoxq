<?php
/**
 * Контроллер для работы с икартинками и файлами
 *
 */
class ImagesController extends AppController
{

    var $versions = array('tiny'      => '16x16',
                          'small'     => '32x32',
                          'thumb'     => '100x100',
                          'medium'    => '300x300',
                          'large'     => '800x800'
    );

    function __setupDir($destination)
    {
        new Folder(dirname($destination), true, 0755); // make sure folders exist
        if (! file_exists(dirname($destination)))
        {
            die('couldn\'t create webdir folder');
        }
        return true;
    }

    /**
     * Ресайзит, копирует и редиректит на ресайзы
     *
     */
    function view()
    {
        $this->layout = 'ajax';
        $args = func_get_args();
        $filename = array_pop($args);
        $dir = implode(DS, $args);
        $this->autoRender = false;
        $this->Image->recursive = - 1;
        $data = $this->Image->findByFilenameAndDir($filename, $dir);
        if (! $data)
        {
            die('No file here');
        }
        $this->Image->id = $data['Image']['id'];
        $this->Image->data = $data;
        $filename = $data['Image']['filename'];
        $dir = $data['Image']['dir'];
        $size = $this->params['size'];

        $original = $this->Image->absolutePath();
        if (! file_exists($original))
        {
            $this->redirect('/img/types/missing.png', null, true);
        }
        $destination = WWW_ROOT . $this->params['url']['url'];
        if ($size == 'original')
        {
            $this->__setupDir($destination);
            if (! copy($original, $destination))
            {
                die('couldn\'t  move file to webdir');
            }
            $this->redirect('/' . $this->params['url']['url'], null, true);
        }
        elseif ($size == 'default')
        {
            $this->__setupDir($destination);
            $width = 300;
            $height = 1000; // Don't care about the height.
            if ($data['Image']['width'] <= $width)
            {
                if (!copy($original, $destination))
                {
                    die('couldn\'t move file to webdir');
                }

                $this->redirect('/' . $this->params['url']['url'], null, true);
            }
            else
                $this->redirect('/img/' . $this->versions['medium']
                                . DS . $dir . DS . $filename, null, true);

        }
        else
        {
            list($width, $height) = explode('x', $size);
        }
        if (strpos($data['Image']['mimetype'], 'image/') === false
            && $size != 'original')
        {
            list($gType, $type) = explode('/', $data['Image']['mimetype']);
            if (! file_exists(WWW_ROOT . 'img/types/' . $type . '.png'))
            {
                $type = 'generic';
            }
            $this->redirect('/img/types/' . $type . '.png', null, true);
        }

        $image = $this->Image->resize(null, $width, $height, $destination);
        if (!$image)
        {
            die('could not resize the file');
        }
        Configure::write('debug', 0);
        header('Content-type: ' . $data['Image']['mimetype'], true);
        header("Cache-Control: cache"); // HTTP/1.1
        header("Pragma: cache"); // HTTP/1.0
        echo $image;
        die();
    }
}
?>
