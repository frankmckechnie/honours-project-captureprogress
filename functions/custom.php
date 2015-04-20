<?php
function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

function ImageVal($files){
    $imageError = array();
    if ($_FILES[$files]) {
        $file_ary = reArrayFiles($_FILES['userfile']);
        if ($file_ary[0]['name'] == null){
            array_push($imageError ," you have to upload an image!");
        }
        foreach ($file_ary as $file) {
            if($file['tmp_name'] != null){
                $imageFileType = pathinfo($file['name'],PATHINFO_EXTENSION);
                $check = getimagesize($file['tmp_name']);
                if($check !== false) {
                } else {
                    array_push($imageError ,"File is not an image.");
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    array_push($imageError ,"$imageFileType:  Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }
                if ($file['size'] > 2004394) {
                    array_push($imageError ,"Sorry, your file is too large.");
                }
            }
        }
    }
    return $imageError;
}

