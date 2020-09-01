<?php

if(isset($_GET['link'])){

$link_to_check  = $_GET['link'];
$broken_link = array();
$valid_link = array();
$badStatusCodes = array('301','308', '404');
$domDoc = new DOMDocument;
$domDoc->preserveWhiteSpace = false;

if(@$domDoc->loadHTMLFile($link_to_check)) { 
     $pageLinks = $domDoc->getElementsByTagName('a');
     foreach($pageLinks as $currLink) {
          foreach($currLink->attributes as $attributeName=>$attributeValue) {
               if($attributeName == 'href') {
                    $ch = curl_init($attributeValue->value);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_exec($ch);
                    $returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if(in_array($returnCode, $badStatusCodes)) {
                         $broken_link[]  = array('name'=>$currLink->nodeValue, 'link'=>$attributeValue->value);
                    } else {
                         $valid_link[] = array('name'=>$currLink->nodeValue, 'link'=>$attributeValue->value);
                    }
               }
          }
     }

     print '<p>List of brocken link in www.greencanvasonline.com </p>';
     print '<pre>' . print_r($broken_link, true) . '</pre>';
     print '<p>List of Valid Link in www.greencanvasonline.com</p>';
     print '<pre>' . print_r($valid_link, true) . '</pre>';
     
}}
?>
<!DOCTYPE html>
<html>
<head>
     <title>link</title>
</head>
<body>
     <a href="broken_link_checker.php?link=https://www.greencanvasonline.com/">
          Click here to check broken link in www.greencanvasonline.com 
     </a>
</body>
</html>


