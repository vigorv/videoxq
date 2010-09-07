<?php
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    header('HTTP/1.1 404 Not Found');
    exit('File Not Found');
}
/**
 * Enter description here...
 */
    uses('file');
/**
 * Enter description here...
 *
 * @param unknown_type $path
 * @param unknown_type $name
 * @return unknown
 */
    function make_clean_js($path, $name) {
        require(VENDORS . 'class.JavaScriptPacker.php');
        $data = file_get_contents($path);
        $packer = new JavaScriptPacker($data, 'Normal', true, false);
        $output = $packer->pack();

        $ratio = 100 - (round(strlen($output) / strlen($data), 3) * 100);
        //$output = " /* file: $name, ratio: $ratio% */ " . $output;
        return $output;
    }
/**
 * Enter description here...
 *
 * @param unknown_type $path
 * @param unknown_type $content
 * @return unknown
 */
    function write_js_cache($path, $content) {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path));
        }
        $cache = new File($path);
        return $cache->write($content);
    }

    if (preg_match('|\.\.|i', $url) || !preg_match('|^cjs/(.+)$|i', $url, $regs)) {
        die('Wrong file name.');
    }

    $filename = 'js/' . $regs[1];
    $filepath = JS . $regs[1];
    $cachepath = CACHE . 'js' . DS . str_replace(array('/','\\'), '-', $regs[1]);

    if (!file_exists($filepath)) {
        die('Wrong file name.');
    }

    if (file_exists($cachepath)) {
        $templateModified = filemtime($filepath);
        $cacheModified = filemtime($cachepath);

        if ($templateModified > $cacheModified) {
            $output = make_clean_js($filepath, $filename);
            write_js_cache($cachepath, $output);
        } else {
            $output = file_get_contents($cachepath);
        }
    } else {
        $output = make_clean_js($filepath, $filename);
        write_js_cache($cachepath, $output);
        $templateModified = time();
    }

    header("Date: " . date("D, j M Y G:i:s ", $templateModified) . 'GMT');
    header("Content-Type: text/javascript");
    header("Expires: " . gmdate("D, j M Y H:i:s", time() + DAY) . " GMT");
    header("Cache-Control: cache"); // HTTP/1.1
    header("Pragma: cache");        // HTTP/1.0
    print $output;
?>