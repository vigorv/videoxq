<?php

$upload_dir = "c:/Temp/uploads";
$max_size = 2000000000;
$createsubfolders = "true";
$keepalive = "false";
$resume = "false";
$debug=true;

if ($resume == "true")
{
  ignore_user_abort(TRUE);
}
//set_time_limit(0);

$REQUEST_METHOD=$_SERVER["REQUEST_METHOD"];
if ($debug)
{
  $lg = fopen($upload_dir."/process_debug.log", "a");
  fwrite($lg,"----- ".date("D M j G:i:s T Y")." -----\r\n");
  fwrite($lg,"PHP.ini setup:".phpversion()."\r\n");
  fwrite($lg," Free space for ".$upload_dir.":".diskfreespace($upload_dir)."\r\n");
  fwrite($lg," file_uploads:".ini_get('file_uploads')."\r\n");
  fwrite($lg," upload_max_filesize:".ini_get('upload_max_filesize')." (script max_size:".$max_size.")\r\n");
  fwrite($lg," post_max_size:".ini_get('post_max_size')."\r\n");
  fwrite($lg," max_execution_time:".ini_get('max_execution_time')."\r\n");
  fwrite($lg," max_input_time:".ini_get('max_input_time')."\r\n");
  fwrite($lg," memory_limit:".ini_get('memory_limit')."\r\n");
  fwrite($lg," ignore_user_abort:".ini_get('ignore_user_abort')."\r\n");
  fwrite($lg," magic_quotes_gpc:".ini_get('magic_quotes_gpc')."\r\n");
  fwrite($lg,"Method dump:".$REQUEST_METHOD."\r\n");
  fwrite($lg,"Headers dump:"."\r\n");
  $headers = emu_getallheaders();
  while (list ($header, $value) = each ($headers))
  {
    fwrite($lg," ".$header."=".$value."\r\n");
  }
  fwrite($lg,"GET dump:"."\r\n");
  while (list ($header, $value) = each ($_GET))
  {
    fwrite($lg," ".$header."=".$value."\r\n");
  }
  fwrite($lg,"POST dump:"."\r\n");
  while (list ($header, $value) = each ($_POST))
  {
    fwrite($lg," ".$header."=".$value."\r\n");
  }

}

error_reporting(0);
$message ="";
if (!is_dir($upload_dir))
{
  if (!recursiveMkdir($upload_dir)) die ("cannot access upload directory");
  if (!chmod($upload_dir,0755)) die ("change permission to 755 failed.");
}

if ($debug) fwrite($lg,"Processing request"."\r\n");

if ($REQUEST_METHOD=="HEAD")
{
  if ($debug) fwrite($lg,"Check if file exists"."\r\n");
  $filename = "";
  if (isset($headers['RELATIVEFILENAME']))
  {
    $filename = $headers['RELATIVEFILENAME'];
  }
  else
  {
    if (isset($headers['FILENAME']))
    {
      $filename = $headers['FILENAME'];
    }
  }
  if(get_magic_quotes_gpc()) $filename = stripslashes($filename);
  if ($filename != "")
  {
    if ($keepalive == "false")
    {
       header("Connection: close");
    }
    $account = "";
    if (isset($headers['ACCOUNT']))
    {
      $account = $headers['ACCOUNT'];
      if (substr($account,0,1) != "/") $account = "/".$account;
    }
    $fhead=$upload_dir.$account."/".$filename;
    $fhead = str_replace("\\","/",$fhead);
    if ($debug) fwrite($lg," file: ".$fhead."\r\n");
    if (file_exists($fhead))
    {
    	header("size: ".filesize($fhead));
    }
    else header("HTTP/1.1 404");
  }
}

// PUT upload support.
if ($REQUEST_METHOD=="PUT")
{
  if ((isset($headers['TODO'])) && ($headers['TODO']=="upload"))
  {
    $account = "";
    if (isset($headers['ACCOUNT']))
    {
      $account = $headers['ACCOUNT'];
      if (substr($account,0,1) != "/") $account = "/".$account;
    }
    $target_folder=$upload_dir.$account;
    if (isset($headers['ACCOUNTCREATION']))
    {
      if ($headers['ACCOUNTCREATION'] == "true") recursiveMkdir($target_folder);
    }
    $relative = $headers['RELATIVEFILENAME'];
    $file_name = $headers['FILENAME'];
    if(get_magic_quotes_gpc())
    {
      $relative = stripslashes($relative);
      $file_name = stripslashes($file_name);
    }
    if (($createsubfolders == "true") && ($relative != ""))
    {
  	  $inda=strlen($relative);
	  $indb=strlen($file_name);
	  if (($indb > 0) && ($inda > $indb))
	  {
		$subfolder = substr($relative,0,($inda-$indb)-1);
        $subfolder = str_replace("\\","/",$subfolder);
        $target_folder = $upload_dir.$account."/".$subfolder;
        if ($debug) fwrite($lg,"Creating directory:".$target_folder."\r\n");
        recursiveMkdir($target_folder);
      }
    }
    if ($file_name == "") $file_name = "put.bin";
    $message = do_put_upload($target_folder,$file_name,$max_size,$resume);
    if ($debug) fwrite($lg,"do_put_upload completed:".$message."\r\n");
  }
}

// POST upload support.
if ($_POST['todo']=="upload")
{
  $account = "";
  if (isset($_POST['account']))
  {
    $account = $_POST['account'];
    if (substr($account,0,1) != "/") $account = "/".$account;
  }
  $target_folder=$upload_dir.$account;
  if (isset($_POST['accountcreation']))
  {
    if ($_POST['accountcreation'] == "true") recursiveMkdir($target_folder);
  }
  // relalivefilename support for folders and subfolders creation.
  $relative = $_POST['relativefilename'];
  if(get_magic_quotes_gpc()) $relative = stripslashes($relative);
  if (($createsubfolders == "true") && ($relative != ""))
  {
	$file_name = $_FILES['uploadfile']['name'];
	if(get_magic_quotes_gpc()) $file_name = stripslashes($file_name);
	$inda=strlen($relative);
	$indb=strlen($file_name);
	if (($indb > 0) && ($inda > $indb))
	{
		$subfolder = substr($relative,0,($inda-$indb)-1);
        $subfolder = str_replace("\\","/",$subfolder);
        $target_folder = $upload_dir.$account."/".$subfolder;
        if ($debug) fwrite($lg,"Creating directory:".$target_folder."\r\n");
        recursiveMkdir($target_folder);
    }
  }
  if ($_FILES['uploadfile'])
  {
    if ($keepalive == "false")
    {
       header("Connection: close");
    }
    $message = do_post_upload($target_folder,$max_size,$resume);
    if ($debug) fwrite($lg,"do_post_upload completed:".$message."\r\n");
    // Recompose file from chunks (if any).
    $chunkid = $_POST['chunkid'];
    $chunkamount = $_POST['chunkamount'];
    $chunkbaseStr = $_POST['chunkbase'];
    if(get_magic_quotes_gpc()) $chunkbaseStr = stripslashes($chunkbaseStr);
    if (($chunkid != "") && ($chunkamount != "") && ($chunkbaseStr != ""))
    {
		if ($chunkid == $chunkamount)
        {
			// recompose file.
			if ($debug) fwrite($lg,"Recomposing file from chunks"."\r\n");
			$fname = $target_folder."/".$chunkbaseStr;
			if (file_exists($fname)) $fname = $fname.".".time();
			if ($debug) fwrite($lg," Creating:".$fname."\r\n");
			$fout = fopen ($fname, "wb");
            for ($c=1;$c<=$chunkamount;$c++)
			{
				$filein = $target_folder."/".$chunkbaseStr.".".$c;
				if ($debug) fwrite($lg," Opening:".$filein."\r\n");
				$fin = fopen ($filein, "rb");
			    while (!feof($fin))
			    {
			      $read = fread($fin,4096);
			      fwrite($fout,$read);
			    }
			    fclose($fin);
			    if ($debug) fwrite($lg," Deleting:".$filein."\r\n");
			    unlink($filein);
			}
			fclose($fout);
			if ($debug) fwrite($lg,"Recomposition completed"."\r\n");
        }
     }
  }
  else
  {
     $emptydirectory = $_POST['emptydirectory'];
     if ($emptydirectory != "")
     {
         recursiveMkdir($upload_dir.$account."/".$emptydirectory);
     }
     $message = "No uploaded file(s).";
  }
}

function do_post_upload($upload_dir,$max_size,$resume_enabled)
{
    global $lg, $debug;
    if ($debug) fwrite($lg," do_post_upload ".$upload_dir."\r\n");
    $temp_name = $_FILES['uploadfile']['tmp_name'];
    $file_name = $_FILES['uploadfile']['name'];
    $file_size = $_FILES['uploadfile']['size'];
    $file_type = $_FILES['uploadfile']['type'];
    $file_error = $_FILES['uploadfile']['error'];
    if ($debug)
    {
       fwrite($lg," temp_name:".$temp_name."\r\n");
       fwrite($lg," file_name:".$file_name."\r\n");
       fwrite($lg," file_size:".$file_size."\r\n");
       fwrite($lg," file_type:".$file_type."\r\n");
       fwrite($lg," file_error:".$file_error."\r\n");
    }
    if(get_magic_quotes_gpc()) $file_name = stripslashes($file_name);
    //$file_name = str_replace("\\","/",$file_name);
    $file_path = $upload_dir."/".$file_name;

    // Check filename.
    if ($file_name =="")
    {
  	  $message = "Error - Invalid filename";
  	  return $message;
    }

    // Check file size.
    if ($file_size > $max_size)
    {
      $errormsg = "- File size is over ".$max_size." bytes";
      header("HTTP/1.1 405");
      header("custommessage: ".$errormsg);
      $message = "Error ".$errormsg;
      return $message;
    }

    $result = FALSE;
    if (($resume_enabled == "true") && (file_exists($file_path)) && (isset($_SERVER['HTTP_CONTENT_RANGE'])))
    {
       // Resume support.
       $range_header = $_SERVER['HTTP_CONTENT_RANGE'];
       if (substr($range_header,0,6) == 'bytes ')
       {
         $minus = strpos($range_header,'-');
         $start_offset = substr($range_header,6,$minus-6);
         $file_path_tmp = $file_path.".".$start_offset.".".time();
         if ($debug) fwrite($lg," move_uploaded_file:".$temp_name." to ".$file_path_tmp."\r\n");
         $result = move_uploaded_file($temp_name, $file_path_tmp);
         if ($debug) fwrite($lg," move_uploaded_file:".$result."\r\n");
         if ($debug) fwrite($lg,"  Resuming at offset ".$start_offset." with ".$file_path_tmp."\r\n");
         $fout = fopen ($file_path, "rb+");
         fseek ($fout,$start_offset);
         $fin = fopen ($file_path_tmp, "rb");
	     while (!feof($fin))
		 {
			$read = fread($fin,4096);
			fwrite($fout,$read);
		 }
		 fclose($fin);
		 if ($debug) fwrite($lg,"  Deleting:".$file_path_tmp."\r\n");
		 unlink($file_path_tmp);
		 fclose($fout);
		 if ($debug) fwrite($lg,"  Resume completed"."\r\n");
       }
       else
       {
          if ($debug) fwrite($lg,"  Cannot resume:".$range_header."\r\n");
       }
    }
    else
    {
       // Regular upload.
       if ($debug) fwrite($lg," move_uploaded_file:".$temp_name." to ".$file_path."\r\n");
       $result = move_uploaded_file($temp_name, $file_path);
       if ($debug) fwrite($lg," move_uploaded_file:".$result."\r\n");
    }
    if ($result)
    {
      chmod($file_path,0755);
      $message = "$file_name uploaded successfully.";
      return $message;
    }
    else
    {
      $errormsg = "- PHP upload failed";
      header("HTTP/1.1 405");
      header("custommessage: ".$errormsg);
      $message = "Error ".$errormsg;
      return $message;
    }
}

function do_put_upload($upload_dir,$file_name,$max_size,$resume_enabled)
{
    global $lg, $debug;
    if ($debug) fwrite($lg," do_put_upload ".$upload_dir."\r\n");
    $file_path = $upload_dir."/".$file_name;
    if ($debug) fwrite($lg," filename: ".$file_name."\r\n");
    // Check filename.
    if ($file_name =="")
    {
  	  $message = "Error - Invalid filename";
  	  return $message;
    }
    // Check file size.
    $file_size = $_SERVER['CONTENT_LENGTH'];
    if ($debug) fwrite($lg," filesize: ".$file_size."\r\n");
    if ($file_size > $max_size)
    {
      $errormsg = "- File size is over ".$max_size." bytes";
      header("HTTP/1.1 405");
      header("custommessage: ".$errormsg);
      $message = "Error ".$errormsg;
      return $message;
    }

    $result = FALSE;
    $start_offset = 0;
    $woption = "wb";
    if (($resume_enabled == "true") && (file_exists($file_path)) && (isset($_SERVER['HTTP_CONTENT_RANGE'])))
    {
       // Resume support.
       $range_header = $_SERVER['HTTP_CONTENT_RANGE'];
       if (substr($range_header,0,6) == 'bytes ')
       {
         $minus = strpos($range_header,'-');
         $start_offset = substr($range_header,6,$minus-6);
         if ($debug) fwrite($lg,"  Resuming at offset ".$start_offset." with ".$file_path."\r\n");
         $woption = "rb+";
       }
    }
    $putdata = fopen("php://input","r");
    $fout = fopen ($file_path, $woption);
    if ($start_offset > 0) fseek ($fout,$start_offset);
    while (!feof($putdata))
    {
      $read = fread($putdata,4096);
	  fwrite($fout,$read);
    }
    $offset = ftell($fout);
    $result = fclose($fout);
    fclose($putdata);
    if ($debug) fwrite($lg," filesize on disk ".$offset."\r\n");
    if ($result)
    {
      chmod($file_path,0755);
      $message = "$file_name uploaded successfully.";
      return $message;
    }
    else
    {
      $errormsg = "- PHP upload failed";
      header("HTTP/1.1 405");
      header("custommessage: ".$errormsg);
      $message = "Error ".$errormsg;
      return $message;
    }
}


function recursiveMkdir($path)
{
	if (!file_exists($path))
    {
		recursiveMkdir(dirname($path));
        return mkdir($path, 0755);
    }
    else return true;
}

function emu_getallheaders()
{
   foreach($_SERVER as $h=>$v)
       if(ereg('HTTP_(.+)',$h,$hp))
           $headers[$hp[1]]=$v;
   return $headers;
}
?>

<html>
<head>
<title>Upload file: PHPScript sample</title>
</head>
<body>
<center>
   <br>
   <? echo $message ?>
  <form action="" method="post" ENCTYPE="multipart/form-data" name="upload" id="upload">
    Select file to upload :
    <input type="hidden" name="todo" value="upload">
    <input type="file" name="uploadfile">
    <input type="submit" name="upload" value="Upload">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p align="center">&nbsp;</p>
  <p align="center">&nbsp;</p>
  <p align="center"><font size="-1" face="Courier New, Courier, mono"><a href="http://www.jfileupload.com" target="_blank">JFileUpload</a></font></p>
   </form>
</center>
</body>
</html>