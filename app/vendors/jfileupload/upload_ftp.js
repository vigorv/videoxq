<!--
var _info = navigator.userAgent;
var _ns = false;
var _ns6 = false;
var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
if (_info.indexOf("Opera") > 0) _ie = false;
var _ns = (navigator.appName.indexOf("Netscape") >= 0 && ((_info.indexOf("Win") > 0 && _info.indexOf("Win16") < 0) || (_info.indexOf("Sun") > 0) || (_info.indexOf("Linux") > 0) || (_info.indexOf("AIX") > 0) || (_info.indexOf("OS/2") > 0) || (_info.indexOf("IRIX") > 0)));
var _ns6 = ((_ns == true) && (_info.indexOf("Mozilla/5") >= 0));
if (_ie == true) {
  document.writeln('<OBJECT classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH="350" HEIGHT="300" NAME="fileupload" codebase="http://java.sun.com/update/1.4.2/jinstall-1_4-windows-i586.cab#Version=1,4,0,0">');
}
else if (_ns == true && _ns6 == false) { 
  // BEGIN: Update parameters below for NETSCAPE 3.x and 4.x support.
  document.write('<EMBED ');
  document.write('type="application/x-java-applet;version=1.4" ');
  document.write('CODE="jfileupload.upload.client.MApplet.class" ');
  document.write('JAVA_CODEBASE="./" ');
  document.write('ARCHIVE="/lib/jfileupload/lib/jfileupload.jar,/lib/jfileupload/lib/ftpimpl.jar,/lib/jfileupload/lib/cnet.jar,/lib/jfileupload/lib/clogging.jar" ');
  document.write('NAME="fileupload" ');
  document.write('WIDTH="350" ');
  document.write('HEIGHT="300" ');
  document.write('url="ftp://localhost" ');

  document.write('mode="ftp" ');
  document.write('scriptable=true ');
  

  document.write('resources="/lib/jfileupload/i18ru.properties" ');
  //  document.write('forward="http://upl.pp#ok" ');

  document.write('authentication="auto" ');
  
  document.write('resume="true" ');
  //document.write('overwrite="indexrename" ');
  
  document.writeln('pluginspage="http://java.sun.com/products/plugin/index.html#download">');

  document.write('param1="username" ');
  document.write('value1="usr" ');
  
  document.write('param2="password" ');
  document.write('value2="111111" ');
  
  document.write('param3="pasv" ');
  document.write('value3="true" ');
  
  document.write('param4="account" ');
  document.write('value4="ftpuser" ');  
  
  document.write('param4="accountcreation" ');
  document.write('value4="true" ');  
  
  document.write('param5="commandmonitor" ');
  document.write('value5="true" ');  
  

  
  document.writeln('<NOEMBED>');
  
  // END
}
else {
  document.write('<APPLET CODE="jfileupload.upload.client.MApplet.class" JAVA_CODEBASE="./" ARCHIVE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar" WIDTH="350" HEIGHT="300" NAME="fileupload">');
}
// BEGIN: Update parameters below for INTERNET EXPLORER, FIREFOX, SAFARI, OPERA, MOZILLA, NETSCAPE 6+ support.
document.writeln('<PARAM NAME=CODE VALUE="jfileupload.upload.client.MApplet.class">');
document.writeln('<PARAM NAME=CODEBASE VALUE="./">');
document.writeln('<PARAM NAME=ARCHIVE VALUE="/lib/jfileupload/lib/jfileupload.jar,/lib/jfileupload/lib/ftpimpl.jar,/lib/jfileupload/lib/cnet.jar,/lib/jfileupload/lib/clogging.jar">');
document.writeln('<PARAM NAME=NAME VALUE="fileupload">');
document.writeln('<PARAM NAME="type" VALUE="application/x-java-applet;version=1.4">');
document.writeln('<PARAM NAME="scriptable" VALUE="true">');
document.writeln('<PARAM NAME="url" VALUE="ftp://localhost">');

document.writeln('<PARAM NAME="param1" VALUE="username">');
document.writeln('<PARAM NAME="value1" VALUE="usr">');

document.writeln('<PARAM NAME="param2" VALUE="password">');
document.writeln('<PARAM NAME="value2" VALUE="111111">');

document.writeln('<PARAM NAME="param3" VALUE="pasv">');
document.writeln('<PARAM NAME="value3" VALUE="true">');

document.writeln('<PARAM NAME="authentication" VALUE="auto">');
document.writeln('<PARAM NAME="resume" VALUE="true">');
//document.writeln('<PARAM NAME="overwrite" VALUE="indexrename">');

document.writeln('<PARAM NAME="resources" VALUE="/lib/jfileupload/i18ru.properties">');
document.writeln('<PARAM NAME="mode" VALUE="ftp">');
//document.writeln('<PARAM NAME="forward" VALUE="http://upl.pp#ok">');

document.writeln('<PARAM NAME="param4" VALUE="account">');
document.writeln('<PARAM NAME="value4="ftpuser">');

document.writeln('<PARAM NAME="param5" VALUE="accountcreation">');
document.writeln('<PARAM NAME="value5="true">');

document.writeln('<PARAM NAME="param5" VALUE="commandmonitor">');
document.writeln('<PARAM NAME="value5="true">');



//document.writeln('<PARAM NAME="forward" VALUE="http://upl.pp">');
//document.writeln('<PARAM NAME="forwardparameters" VALUE="true">');



// END
if (_ie == true) {
  document.write('</OBJECT>');
}
else if (_ns == true && _ns6 == false) {
  document.write('</NOEMBED></EMBED>');
}
else {
  document.write('</APPLET>');
}
//-->
