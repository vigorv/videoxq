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
  document.write('ARCHIVE="/lib/jfileupload/lib/jfileupload.jar,/lib/jfileupload/lib/httpimpl.jar,/lib/jfileupload/lib/chttpclient.jar,/lib/jfileupload/lib/clogging.jar" ');
  document.write('NAME="fileupload" ');
  document.write('WIDTH="350" ');
  document.write('HEIGHT="300" ');
  document.write('url="http://videoxq-wc2.anka.ws/admin/copyrightholders/import/process" ');
  document.write('paramfile="uploadfile" ');
  document.write('param1="todo" ');
  document.write('value1="upload" ');
  document.write('param2="errorheader" ');
  document.write('value2="custommessage" ');
  document.write('param3="relativefilename" ');
  document.write('value3="true" ');
  document.write('param4="httpmethod" ');
  document.write('value4="put" ');
  document.write('resume="true" ');
  document.write('folderdepth="-1" ');
  document.write('mode="http" ');
  document.write('scriptable=true ');
  document.write('resources="/lib/jfileupload/i18ru.properties" ');
  //  document.write('forward="http://upl.pp#ok" ');
  document.writeln('pluginspage="http://java.sun.com/products/plugin/index.html#download"><NOEMBED>');

  // END
}
else {
  document.writeln('<APPLET CODE="jfileupload.upload.client.MApplet.class" JAVA_CODEBASE="./" ARCHIVE="lib/jfileupload.jar,lib/httpimpl.jar,lib/chttpclient.jar,lib/clogging.jar" WIDTH="350" HEIGHT="300" NAME="fileupload">');
}
// BEGIN: Update parameters below for INTERNET EXPLORER, FIREFOX, SAFARI, OPERA, MOZILLA, NETSCAPE 6+ support.
document.writeln('<PARAM NAME=CODE VALUE="jfileupload.upload.client.MApplet.class">');
document.writeln('<PARAM NAME=CODEBASE VALUE="./">');
document.writeln('<PARAM NAME=ARCHIVE VALUE="/lib/jfileupload/lib/jfileupload.jar,/lib/jfileupload/lib/httpimpl.jar,/lib/jfileupload/lib/chttpclient.jar,/lib/jfileupload/lib/clogging.jar">');
document.writeln('<PARAM NAME=NAME VALUE="fileupload">');
document.writeln('<PARAM NAME="type" VALUE="application/x-java-applet;version=1.4">');
document.writeln('<PARAM NAME="scriptable" VALUE="true">');
document.writeln('<PARAM NAME="url" VALUE="http://videoxq-wc2.anka.ws/admin/copyrightholders/import/process">');
document.writeln('<PARAM NAME="paramfile" VALUE="uploadfile">');
document.writeln('<PARAM NAME="param1" VALUE="todo">');
document.writeln('<PARAM NAME="value1" VALUE="upload">');
document.writeln('<PARAM NAME="param2" VALUE="errorheader">');
document.writeln('<PARAM NAME="value2" VALUE="custommessage">');
document.writeln('<PARAM NAME="param3" VALUE="relativefilename">');
document.writeln('<PARAM NAME="value3" VALUE="true">');
document.writeln('<PARAM NAME="param4" VALUE="httpmethod">');
document.writeln('<PARAM NAME="value4" VALUE="put">');
document.writeln('<PARAM NAME="resume" VALUE="true">');
document.writeln('<PARAM NAME="folderdepth" VALUE="-1">');
document.writeln('<PARAM NAME="mode" VALUE="http">');
document.writeln('<PARAM NAME="resources" VALUE="/lib/jfileupload/i18ru.properties">');
//document.writeln('<PARAM NAME="forward" VALUE="http://upl.pp#ok">');
// END
if (_ie == true) {
  document.writeln('</OBJECT>');
}
else if (_ns == true && _ns6 == false) {
  document.writeln('</NOEMBED></EMBED>');
}
else {
  document.writeln('</APPLET>');
}
//-->
