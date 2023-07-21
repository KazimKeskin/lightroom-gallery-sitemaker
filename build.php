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

function generateNav($folders, $activePage) {

  $pages = array();
    foreach ($folders as $folder) {

      if ($folder['parent'] === "galleries") {

        $htmlFile = $folder['path'].'/index.html';

        if ($folder['name'] === $activePage) {
          array_push($pages,
            ['name' => 'li',  'attributes' => ['class' => 'link'], 'content' => [
              ['name' => 'a', 'attributes' => ['class' => 'active', 'href' => $webgallerypath . $folder['name']], 'content' => $folder['name']
            ]]
          ]);
        }
        else {
          array_push($pages,
            ['name' => 'li', 'attributes' => ['class' => 'link'], 'content' => [
              ['name' => 'a', 'attributes' => ['href' => $webgallerypath . $folder['name']], 'content' => $folder['name']]
            ]])
          ;
        }}



      $nav = [
                  [
                      'name' => 'div',
                      'attributes' => ['class' => 'nav hide-small hide-medium'],
                      'content' => [
                          [
                                'name' => 'ul',
                                'attributes' => ['class' => 'menu'],
                                'content' => $pages
                          ]
                      ]

                  ]
            ];



    }
return $nav;
}

function generateSmallNav($folders, $activePage) {

  $pages = array();
    foreach ($folders as $folder) {

      if ($folder['parent'] === "galleries") {

        $htmlFile = $folder['path'].'/index.html';

        if ($folder['name'] === $activePage) {
          array_push($pages,
              ['name' => 'a', 'attributes' => ['class' => 'active', 'href' => $webgallerypath . $folder['name']], 'content' => $folder['name']]
          ]);
        }
        else {
          array_push($pages,
              ['name' => 'a', 'attributes' => ['href' => $webgallerypath . $folder['name']], 'content' => $folder['name']]
            )
          ;
        }}





      $smallnav = [
                        [
                            'name' => 'div',
                            'attributes' => ['class' => 'nav hide-large'],
                            'content' => [
                              [
                                'name' => 'a',
                                'attributes' => ['href' => 'javascript:void(0);', 'id' => 'menu-icon', 'onclick' => 'openMenu()'],
                                'content' =>
                                [
                                  [
                                      'name' => 'i',
                                      'attributes' => ['class' => 'fa fa-bars']
                                  ]
                                ]
                              ],
                              [
                                'name' => 'div',
                                'attributes' => ['id' => 'smallnav'],
                                'content' => $pages
                              ]
                            ]
                        ]
                  ];

    }
return $smallnav;
}


function updateIndexFiles($folders) {
  foreach ($folders as $folder) {
    if ($folder['parent'] === "galleries") {
      $nav = generateNav($folders, $folder);
      $smallnav = generateSmallNav($folders, $folder);
      include 'updateindexfile.php';
    }
  }
}




$folderStructure = array();

scanDirectory($dir, $folderStructure);
updateIndexFiles($folderStructure);


?>
