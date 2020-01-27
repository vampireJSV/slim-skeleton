<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 25/01/2017
 * Time: 17:56
 */

/**
 * @param $string
 * @param  string  $delimiter
 *
 * @return array
 */
function argsToArray($string, $delimiter = '/')
{
    $parts = explode($delimiter, $string);

    return $parts;
}

/**
 * @param $string
 * @param  string  $delimiter
 *
 * @return array
 */
function argsToArrayAsoc($string, $delimiter = '/')
{
    $parts = explode($delimiter, $string);
    if ( ! (count($parts) % 2)) {
        for ($i = 0; $i<count($parts); $i = $i + 2) {
            $temp[$parts[$i]] = $parts[$i + 1];
        }
        $parts = $temp;
    }

    return $parts;
}

/**
 * @param $dir
 *
 * @return array
 */
function scan_dir_to_array($dir)
{
    $files = array_diff(scandir($dir), ["..", "."]);
    $raw   = [];
    foreach ($files as $file) {
        $raw[pathinfo($file, PATHINFO_FILENAME)] = require $dir.$file;
    }

    return $raw;
}

function mount_url($protocol = true)
{
    $output = getenv('URL');
    if ($protocol) {
        $output = getenv('PROTOCOL').$output;
    }
    if (getenv('PORT')!='') {
        $output = $output.":".getenv('PORT');
    }

    return $output;
}

function rrmdir($dir, $deletedir = true)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object!="." && $object!="..") {
                if (is_dir($dir."/".$object)) {
                    rrmdir($dir."/".$object);
                } else {
                    unlink($dir."/".$object);
                }
            }
        }
        if ($deletedir) {
            rmdir($dir);
        }
    }
}

function clean_old_sessions($path, $max_life_secons = 86400)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object!="." && $object!="..") {
                if ((filemtime($path.'/'.$object) + $max_life_secons)<time()) {
                    unlink($path.'/'.$object);
                }
            }
        }
    }
}