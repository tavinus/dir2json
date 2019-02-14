# dir2json
A PHP CLI script to ouput the contents of a whole directory tree to a JSON object.

*by Ryan, 2015*  
http://www.ryadel.com/
* [Official Page](http://www.ryadel.com/en/portfolio/dir2json-en/)
* [Documentation & info](http://www.ryadel.com/dir2json-a-php-cli-script-to-output-the-contents-of-a-folder-tree-into-a-json-object/)

## What does it do
It fetches a directory tree structure like this:

```
/images/
    /gif/
        man.gif
        woman.gif
    /jpg/
        photo1.jpg
        photo2.jpg
    /png/
        avatar.png
    howto.txt,
    readme.md
```

and outputs its contents into a json-formatted file like this:

```
{
    "gif": [
      "man.gif",
      "woman.gif"
      ],
    "jpg": [
      "photo1.jpg",
      "photo2.jpg"
      ],
    "png": [
      "avatar.png"
      ],
    "0": "howto.txt",
    "1": "readme.md"
}
```

It can be very useful when working with Javascript frameworks and/or similar scenarios where you need to load/browse/show a directory structure without being allowed to access the system IO.

## Conversion rules
The json conversion is handled by the native php `json_encode` function (available in PHP 5 >= 5.2.0, PECL json >= 1.2.0, PHP 7). For further info on PHP's json_encode function, read here:
http://php.net/manual/en/function.json-encode.php

The generated JSON object will adopt the following conventions:
* If a folder contains only files (without subfolders), they will be listed as items of a single array.
* If a folder contains one or more subfolders, each one will be listed as a key/value array.
* If a folder contains files and subfolders, both will be listed as a key/value array: each file will have an auto-generated numeric key starting from 0 (numbers already used by a subfolder's name will be skipped).


## Usage
The code it's meant to be used as a dedicated CLI script, but you can also execute it from a standard, web-hosted PHP page by populating the $argv[] array directly from code. If you need further help to implement it into a PHP page, contact me and I'll update the docs accordingly.

### From CLI

```
 > php dir2json <targetFolder> <outputFile> [JSON_OPTIONS]
```

JSON_OPTIONS is a bitmask consisting of:
```
  JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, 
  JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION, 
  JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR
```

The behaviour of these constants is described on the JSON constants page:
http://php.net/manual/en/json.constants.php

#### Help Info
```
$ ./dir2json.php -h
------------------------------------------------------
dir2json - v0.3.0

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
```

#### Example
```
 > php dir2json ./images out.json JSON_PRETTY_PRINT
```

## Useful Links
* [dir2json project page on Ryadel.com](http://www.ryadel.com/dir2json-a-php-cli-script-to-output-the-contents-of-a-folder-tree-into-a-json-object/)
* [dir2json article, explaining how it works](http://www.ryadel.com/en/portfolio/dir2json-en/)
