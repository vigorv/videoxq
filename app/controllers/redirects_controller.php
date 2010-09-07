<?php
class RedirectsController extends AppController
{
    var $name = 'Redirects';
    var $uses= array('Redirect');

    function index($link=null)
    {
        if (empty($link))
        {
        	$this->redirect('http://www.videoxq.com/');
        }
        $row=$this->Redirect->findbylink($link);
        //pr($row);
        //pr($_SERVER);
        if (!$row){$id=0;$url='http://www.videoxq.com/';}
        else{$id=$row['Redirect']['id'];$url=$row['Redirect']['url'];$link='';}

    	if(isset($_SERVER['HTTP_REFERER']))$referer_uri=$_SERVER['HTTP_REFERER']; else $referer_uri='';
    	$referer=parse_url($referer_uri,PHP_URL_HOST);
    	$ip=$_SERVER['REMOTE_ADDR'];
    	$sql="insert into redirect_clicks (redirect_id,referer,referer_uri,ip,link)values('{$id}','{$referer}','{$referer_uri}','{$ip}','{$link}')";
    	//echo $sql;
    	$this->Redirect->query($sql);
       	$this->redirect($url);
    }
}
?>