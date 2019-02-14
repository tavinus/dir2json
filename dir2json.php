#!/usr/bin/env php
<?php
// ------------------------------------------------------
// dir2json
//
// by Ryan, 2015
// http://www.ryadel.com/
// Gustavo Arnosti Neves - 2018
// https://github.com/tavinus
// ------------------------------------------------------
// Use -h or --help for help, eg:
//   > ./dir2json.php -h
//   > php dir2json.php --help
// ------------------------------------------------------

$DIR2JSON = '0.3.0';

function dir2json($dir, $sortOrder = SORT_ASC, $sortFlag = SORT_LOCALE_STRING)
{
    $dirList = [];
    $tmpList = []; // used to sort files and dirs together
    
    $excludes = [
        ".",
        "..",
        "Thumb.db",
        "Thumbs.db",
        ".DS_Store",
        ".DS_Store?",
        ".Spotlight-V100",
        ".Trashes",
        "ehthumbs.db",
    ];

    if($handler = opendir($dir))
    {
        while (($content = readdir($handler)) !== FALSE)
        {
            if (!in_array($content, $excludes))
            {
                $tmpList[] = strtolower($content);
                //$tmpList[] = $content;
                if(is_file($dir."/".$content))
                    $dirList[] = $content;
                else if(is_dir($dir."/".$content)) 
                    $dirList[$content] = dir2json($dir."/".$content, $sortOrder, $sortFlag); 
            } 
        }    
        closedir($handler); 
    }

    // sort based on file/folder name
    array_multisort($tmpList, $sortOrder, $sortFlag, $dirList);
    return $dirList;
}

function usage($str='', $ret=2) {
    if (!empty($str)) echo $str."\n";
    echo "For help, try:\n  ./dir2json.php -h\n";
    exit($ret);
}

// Long and short help opts
$param = isset($argv[1]) ? $argv[1] : '';
if ($param === "-h" || $param === "--help")
{
    echo <<<EOT
------------------------------------------------------
dir2json - v$DIR2JSON

by Ryan & Tavinus, 2015-2018
http://www.ryadel.com/
https://github.com/Darkseal/dir2json
------------------------------------------------------
        
USAGE (from CLI):
 > ./dir2json.php <targetFolder> <outputFile> [JSON_OPTIONS] [SORT_ORDER] [SORT_FLAG]

EXAMPLE:
 > ./dir2json.php ./ ./cache.json JSON_PRETTY_PRINT SORT_DESC SORT_NATURAL

HELP:
 > ./dir2json.php -h

------------------------------------------------------

JSON_OPTIONS is a bitmask consisting of: (default: 0)
  JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, 
  JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION, 
  JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR

The behaviour of these constants is described on the JSON constants page:
  http://php.net/manual/en/json.constants.php

for further info on PHP's json_encode function, read here:
  http://php.net/manual/en/function.json-encode.php

------------------------------------------------------

Sorting is done by the PHP funcion array_multisort()
http://php.net/manual/en/function.array-multisort.php

SORT_ORDER can be either:
  SORT_ASC  - ascendingly (default) 
  SORT_DESC - descendingly

SORT_FLAGS defaults to SORT_LOCALE_STRING, always case insensitive:
  SORT_REGULAR       - compare items normally (don't change types)
  SORT_NUMERIC       - compare items numerically
  SORT_STRING        - compare items as strings
  SORT_LOCALE_STRING - compare items as strings, based on the current locale.
  SORT_NATURAL       - compare items as strings using "natural ordering" like natsort()

------------------------------------------------------

EOT;
    exit(0);
}

// Parse parameters
$targetFolder = isset($argv[1]) ? $argv[1] : null;
$outputFile   = isset($argv[2]) ? $argv[2] : null;
$jsonOptions  = isset($argv[3]) ? $argv[3] : null;
$jsonOptions  = empty($jsonOptions) ? 0 : constant($jsonOptions);

$sortOrder  = isset($argv[4]) ? $argv[4] : null;
$sortOrder  = empty($sortOrder) ? SORT_ASC : constant($sortOrder);

$sortFlag  = isset($argv[5]) ? $argv[5] : null;
$sortFlag  = empty($sortFlag) ? SORT_LOCALE_STRING : constant($sortFlag);

// If we have a folder to read
if (!is_dir($targetFolder)) {
    if (empty($targetFolder)) $targetFolder = '(empty)';
    usage("Cannot open folder $targetFolder", 2);
}

// If we have an output file name
if (empty($outputFile)) {
    usage("Need a valid output file name (empty)", 3);
}

$arr  = dir2json($targetFolder, $sortOrder, $sortFlag);
//var_dump($arr);

$json = json_encode($arr, $jsonOptions);
if (!file_put_contents($outputFile, $json)) {
    usage("Could not save output file: $outputFile", 4);
}

exit(0);
?>