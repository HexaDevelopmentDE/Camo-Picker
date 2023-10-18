<?php
    $dir    = '../img/camos';
    $images = scandir($dir);
    $images = array_slice($images, 2, count($images));
    $patterns = array();
    
    var_dump($images);

    foreach ($images as $key => $image) {
        $image_url = '../img/camos/'.$image;
        $loaded_image = imagecreatefromjpeg($image_url);

        $width = imagesx($loaded_image);
        $height = imagesy($loaded_image);
        $colors = array();
        $r_all = 0;
        $g_all = 0; 
        $b_all = 0;
        $counter = 0;
    
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($loaded_image, $x, $y);
                $r_all = $r_all + ($rgb >> 16) & 0xFF;
                $g_all = $g_all + ($rgb >> 8) & 0xFF;
                $b_all = $b_all + $rgb & 0xFF;
                $counter++;
            } 
        }

        $average = [
            'camo' => substr($image, 0, strpos($image, '.')),
            'r' => ($r_all / $counter),
            'g' => ($g_all / $counter),
            'b' => ($b_all / $counter),
        ];
        $patterns[] = $average;
        var_dump($average);
    }

    $fp = fopen('camos.csv', 'w');
    foreach ($patterns as $fields) {
        fputcsv($fp, $fields);
    }
    
    fclose($fp);
?>