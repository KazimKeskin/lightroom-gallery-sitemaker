<?php

include 'settings.php';


function scanDirectory($dir, &$result = array(), $depth = 0) {
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $result[] = array(
                    'name' => $file,
                    'depth' => $depth,
                    'parent' => $path ? basename(dirname($path)) : null,
                    'path' => $path
                );

                scanDirectory($path, $result, $depth + 1);
            }
        }
    }
}

function convertToDecimal ($fraction) {
    $numbers=explode("/",$fraction);
    return round($numbers[0]/$numbers[1],1);
}

function createElement($dom, $element) {
    $node = $dom->createElement($element['name']);

    if (isset($element['attributes'])) {
        foreach ($element['attributes'] as $name => $value) {
            $node->setAttribute($name, $value);
        }
    }

    if (isset($element['content'])) {
        if (is_array($element['content'])) {
            foreach ($element['content'] as $childElement) {
                $childNode = createElement($dom, $childElement);
                $node->appendChild($childNode);
            }
        } else {
            $node->nodeValue = $element['content'];
        }
    }

    return $node;
}


function updateIndexFiles($folders) {
  foreach ($folders as $folder) {
    if ($folder['parent'] === "galleries") {
      include 'updateindexfile.php';
    }
  }
}




$folderStructure = array();

scanDirectory($dir, $folderStructure);
updateIndexFiles($folderStructure);


?>
