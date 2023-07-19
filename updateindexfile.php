<?php

include 'settings.php';

// Read the contents of the existing HTML file
$htmlFile = $folder['path'].'/index.html';
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
@$dom->loadHTMLFile($htmlFile, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);





if($addbasetag === 1) {
  $baseElement = $dom->createElement('base');
  $baseElement->setAttribute('href', str_replace('\\', '/', dirname($folder['path'])));
  $baseElement->setAttribute('target', '_blank');
};





if($addstylesheet === 1) {
  $stylesheets = [
      ['href' => $stylesheet, 'type' => 'text/css', 'rel' => 'stylesheet'],
      ['href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', 'type' => 'text/css', 'rel' => 'stylesheet'] // for the info icon
  ];

  $headElement = $dom->getElementsByTagName('head')->item(0);

  if ($headElement) {
      foreach ($stylesheets as $stylesheet) {
          $linkElement = $dom->createElement('link');
          $linkElement->setAttribute('href', $stylesheet['href']);
          $linkElement->setAttribute('type', $stylesheet['type']);
          $linkElement->setAttribute('rel', $stylesheet['rel']);
          $headElement->appendChild($dom->createTextNode("\n"));
          $headElement->appendChild($linkElement);
          $headElement->appendChild($dom->createTextNode("\n"));
      }
      if($addbasetag === 1) {
      $headElement->insertBefore($baseElement, $headElement->firstChild);
    };
  };
};





// add comment, full-image and info. into info-container
$actionbar = [
    ['name' => 'div', 'attributes' => ['class' => 'actionbar'], 'content' => [
        ['name' => 'button', 'attributes' => ['id' => 'comment', 'class' => 'item left button', 'onclick' => 'toggleForm()'], 'content' => 'Comment'],
        ['name' => 'form', 'attributes' => ['onsubmit' => 'setFormFieldValue();', 'id' => 'form', 'action' => $commentpage, 'method' => 'post', 'enctype' => 'application/x-www-form-urlencoded'], 'content' => [
            ['name' => 'div', 'content' => [
                ['name' => 'label', 'attributes' => ['for' => 'page'], 'content' => [
                    ['name' => 'input', 'attributes' => ['type' => 'hidden', 'id' => 'page', 'name' => 'page']]
                ]]
            ]],
            ['name' => 'div', 'content' => [
                ['name' => 'label', 'attributes' => ['for' => 'comment'], 'content' => [
                    ['name' => 'input', 'attributes' => ['type' => 'text', 'name' => 'comment', 'id' => 'comment']]
                ]]
            ]],
            ['name' => 'div', 'content' => [
                ['name' => 'input', 'attributes' => ['class' => 'button', 'type' => 'submit', 'name' => 'submit', 'value' => 'Send']]
            ]]
        ]],
        ['name' => 'a', 'attributes' => ['id' => 'fullImage', 'class' => 'item', 'href' => '', 'target' => '_blank', 'onclick' => 'setImage()'], 'content' => 'Open Full-Size'],
        ['name' => 'span', 'attributes' => ['id' => 'info', 'class' => 'item right','onclick' => 'toggleInfo()'], 'content' => [
            ['name' => 'i', 'attributes' => ['class' => 'fa fa-info-circle']]
        ]]
    ]]
];

// Find the specific <div> based on its class name
$targetClassName = 'info-container'; // Replace with the class name of the <div> you want to target
$targetDivs = $dom->getElementsByTagName('div');

foreach ($targetDivs as $targetDiv) {
    if ($targetDiv->getAttribute('class') === $targetClassName) {
        // Create and append new DOM nodes for each element in $actionbar
        foreach ($actionbar as $element) {
            $node = createElement($dom, $element);
            $targetDiv->appendChild($node);
        }
    }
}







if($addscript === 1) {
  $scripts = [
      '<script src="'.$script.'"></script>'
  ];

  $bodyElement = $dom->getElementsByTagName('body')->item(0);

  if ($bodyElement) {
      foreach ($scripts as $script) {
          $scriptElement = $dom->createDocumentFragment();
          $scriptElement->appendXML($script);
          $bodyElement->appendChild($scriptElement);
      }
  }
};





if($editpaginationsize === 1) {

  $bodyElement = $dom->getElementsByTagName('body')->item(0);

  if ($bodyElement) {
      $bodyElement->setAttribute('data-pagination-size', $paginationsize);
  }
};





if($addtitle === 1) {

  // Get title from gallery name
  $title = basename(dirname($htmlFile));

  // Replace hyphens with spaces
  $title = str_replace("-", " ", $title);

  // Capitalize words
  $title = ucwords($title);

  // Get the <title> element
  $titleElement = $dom->getElementsByTagName('title')->item(0);

  if ($titleElement) {
          while ($titleElement->firstChild) {
              $titleElement->removeChild($titleElement->firstChild);
          }

          $newtitle = $dom->createDocumentFragment();
          $newtitle->appendXML($title);
          $titleElement->appendChild($dom->createTextNode("\n"));
          $titleElement->appendChild($newtitle);
          $titleElement->appendChild($dom->createTextNode("\n"));

  }
};





// add exif data to array
$scripts = $dom->getElementsByTagName('script');
foreach ($scripts as $script) {
    // Check if the script contains the desired array
    $scriptContent = $script->textContent;
    if (strpos($scriptContent, 'LR.images') !== false) {
        // Extract the array part from the script content
        $arrayStart = strpos($scriptContent, '[');
        $arrayEnd = strrpos($scriptContent, ']');
        $arrayContent = substr($scriptContent, $arrayStart, $arrayEnd - $arrayStart + 1);
        $arrayContent = preg_replace('/,\s*]/', ']', $arrayContent);
        // Parse the array content as JSON
        $images = json_decode($arrayContent, true, 512, JSON_BIGINT_AS_STRING);

        // Modify the desired array elements
        foreach ($images as &$image) {
            $imageFile = $folder['path'].'/images/full/'.$image['exportFilename'].'.jpg';
            $exifData = exif_read_data($imageFile);

            if ($exifData !== false) {
                // Add the EXIF data to the image element
                $image['DateTimeOriginal'] = date("j F Y  H:i:s", strtotime($exifData['DateTimeOriginal'])) ?? '';
                $image['Model'] = $exifData['Model'] ?? '';
                $image['FocalLength'] = convertToDecimal($exifData['FocalLength']).'mm' ?? '';
                $image['ExposureTime'] = $exifData['ExposureTime'].'s' ?? '';
                $image['ISOSpeedRatings'] = $exifData['ISOSpeedRatings'] ?? '';
                $image['FNumber'] = 'f/'.convertToDecimal($exifData['FNumber']) ?? '';
            }
        }

        // Convert the modified PHP array back to JSON
        $modifiedArrayContent = json_encode($images, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Replace the original LR.images array content in the script
        $modifiedScriptContent = substr_replace($scriptContent, $modifiedArrayContent, $arrayStart, $arrayEnd - $arrayStart + 1);
        $script->textContent = $modifiedScriptContent;

        // Break the loop since we found and modified the array
        break;
    }
}





// update javascript
  $filePath = $folder['path'].'/assets/js/main.js';  // Path to the JavaScript file
  $fileContent = file_get_contents($filePath);
  $textToInsert = 'if($thumbnail.attr("data-title") != "nil"){
      _metadata += \'<li class="title">\' + $thumbnail.attr("data-title") + \'</li>\';
  }
  if($thumbnail.attr("data-caption") != "nil"){
      _metadata += \'<li class="caption">\' + $thumbnail.attr("data-caption") + \'</li>\';
  }
  if($thumbnail.attr("data-date") != "nil"){
      _metadata += \'<li class="caption">\' + $thumbnail.attr("data-date") + \'</li>\';
  }
  if($thumbnail.attr("data-model") != "nil"){
      _metadata += \'<li class="caption">\' + $thumbnail.attr("data-model") + \'</li>\';
  }
  if($thumbnail.attr("data-focallength") != "nil"){
      _metadata += \'<li class="caption">Focal Length: \' + $thumbnail.attr("data-focallength") + \'</li>\';
  }
  if($thumbnail.attr("data-exposure") != "nil"){
      _metadata += \'<li class="caption">Exposure: \' + $thumbnail.attr("data-exposure") + \'</li>\';
  }
  if($thumbnail.attr("data-fnumber") != "nil"){
      _metadata += \'<li class="caption">F-Stop: \' + $thumbnail.attr("data-fnumber") + \'</li>\';
  }
  if($thumbnail.attr("data-iso") != "nil"){
      _metadata += \'<li class="caption">ISO: \' + $thumbnail.attr("data-iso") + \'</li>\';
  }';  // Text to insert
  $insertionCode = 'if($thumbnail.attr("data-title") != "nil"){
            _metadata += \'<p class="title">\' + $thumbnail.attr("data-title") + \'</p>\';
        }
        if($thumbnail.attr("data-caption") != "nil"){
            _metadata += \'<p class="caption">\' + $thumbnail.attr("data-caption") + \'</p>\';
        }';
  $insertionPoint = strpos($fileContent, $insertionCode);

  // Insert the text after the insertion code
  $modifiedContent = str_replace($insertionCode, $textToInsert, $fileContent);

  // Write the modified content back to the file
  file_put_contents($filePath, $modifiedContent);
  $filePath = $folder['path'].'/assets/js/main.js';  // Path to the JavaScript file
  $fileContent = file_get_contents($filePath);


  $textToInsert = ' data-model="\' + LR.images[i].Model + \'" data-date="\' + LR.images[i].DateTimeOriginal + \'" data-fnumber="\' + LR.images[i].FNumber + \'" data-iso="\' + LR.images[i].ISOSpeedRatings + \'" data-focallength="\' + LR.images[i].FocalLength + \'" data-exposure="\' + LR.images[i].ExposureTime + \'"';
  $insertionCode = 'data-caption="\' + LR.images[i].caption + \'"';
  $insertionPoint = strpos($fileContent, $insertionCode);
  $modifiedContent = substr_replace($fileContent, $textToInsert, $insertionPoint + strlen($insertionCode), 0);
  file_put_contents($filePath, $modifiedContent);





// copy over the CSS and JavaScript files
  $sourceFilePath = 'actions.css';
  $destinationFilePath = $folder['path'].'/assets/css/actions.css';

  if (copy($sourceFilePath, $destinationFilePath)) {
      echo "File copied successfully.";
  } else {
      echo "File copy failed.";
  }

  $sourceFilePath = 'actions.js';
  $destinationFilePath = $folder['path'].'/assets/js/actions.js';

  if (copy($sourceFilePath, $destinationFilePath)) {
      echo "File copied successfully.";
  } else {
      echo "File copy failed.";
  }





// Save the modified DOM as the updated HTML file
$modifiedContent = $dom->saveHTML();
file_put_contents($folder['path'].'/index.html', $modifiedContent);

?>
