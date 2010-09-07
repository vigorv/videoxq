<html>
<head>
<title><?php echo $page_title?></title>
<META http-equiv="content-type" content="text/html;charset=utf-8" />
<?php if(Configure::read() == 0) { ?>
<meta http-equiv="Refresh" content="<?php echo $pause?>;url=<?php echo $url?>"/>
<?php } ?>
<style><!--
P { text-align:center; font:bold small sans-serif }
A { color:red}
A:HOVER { text-decoration: underline; color:#44E }
--></style>
</head>
<body>
<p><a href="<?php echo $url?>"><?php echo $message?></a></p>
</body>
</html>
