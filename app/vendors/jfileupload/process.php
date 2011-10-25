<?php

$upload_dir = "Z:/home/upl.pp/www/uploads";
$max_size = 2000000000;
$createsubfolders = "true";
$keepalive = "false";
$resume = "true";

if ($resume == "true")
{
  ignore_user_abort(TRUE);
}
//set_time_limit(0);
error_reporting(0);
$message ="";
if (!is_dir($upload_dir))
{
  if (!recursiveMkdir($upload_dir)) die ("cannot access upload directory");
  if (!chmod($upload_dir,0755)) die ("change permission to 755 failed.");
}

$REQUEST_METHOD=$_SERVER["REQUEST_METHOD"];
$headers = emu_getallheaders();
if ($REQUEST_METHOD=="HEAD")
{
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
        recursiveMkdir($target_folder);
      }
    }
    if ($file_name == "") $file_name = "put.bin";
    $message = do_put_upload($target_folder,$file_name,$max_size,$resume);
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
			$fname = $target_folder."/".$chunkbaseStr;
			if (file_exists($fname)) $fname = $fname.".".time();
			$fout = fopen ($fname, "wb");
            for ($c=1;$c<=$chunkamount;$c++)
			{
				$filein = $target_folder."/".$chunkbaseStr.".".$c;
				$fin = fopen ($filein, "rb");
			    while (!feof($fin))
			    {
			      $read = fread($fin,4096);
			      fwrite($fout,$read);
			    }
			    fclose($fin);
			    unlink($filein);
			}
			fclose($fout);
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
    $temp_name = $_FILES['uploadfile']['tmp_name'];
    $file_name = $_FILES['uploadfile']['name'];
    $file_size = $_FILES['uploadfile']['size'];
    $file_type = $_FILES['uploadfile']['type'];
    $file_error = $_FILES['uploadfile']['error'];
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
        $result = move_uploaded_file($temp_name, $file_path_tmp);
        $fout = fopen ($file_path, "rb+");
        fseek ($fout,$start_offset);
        $fin = fopen ($file_path_tmp, "rb");
	    while (!feof($fin))
		{
		  $read = fread($fin,4096);
		  fwrite($fout,$read);
		}
		fclose($fin);
		unlink($file_path_tmp);
		fclose($fout);
      }
    }
    else
    {
      // Regular upload.
      $result = move_uploaded_file($temp_name, $file_path);
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
    $file_path = $upload_dir."/".$file_name;
    // Check filename.
    if ($file_name =="")
    {
  	  $message = "Error - Invalid filename";
  	  return $message;
    }
    // Check file size.
    $file_size = $_SERVER['CONTENT_LENGTH'];
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
    $result = fclose($fout);
    fclose($putdata);
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
  <form action="process.php" method="post" ENCTYPE="multipart/form-data" name="upload" id="upload">
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