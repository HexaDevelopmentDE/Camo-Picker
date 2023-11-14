<?php
    $dir    = '../img/camos';
    $images = scandir($dir);
    $images = array_slice($images, 2, count($images));
    $patterns = array();
    
    //var_dump($images);

    foreach ($images as $key => $image) {
        $image_url = '..\img\camos\\'.$image;
        $loaded_image = imagecreatefromjpeg($image_url);

        $width = imagesx($loaded_image);
        $height = imagesy($loaded_image);
        $r_all = 0;
        $g_all = 0; 
        $b_all = 0;
        $counter = 0;
    
        for ($y = 0; $y < $height; $y++) {
            $y_array = array() ;
    
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($loaded_image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
    
                $x_array = array($r, $g, $b) ;
                $y_array[] = $x_array ;
        
                //echo "Position: ".$y."(y) ".$x."(x)<br>rgb: ".$r." ".$g." ".$g."<br>-------------------<br>";
                $r_all = $r_all + $r;
                $g_all = $g_all + $g;
                $b_all = $b_all + $b;
                $counter++;
            }
        }

        $average = [
            'camo' => substr($image, 0, strpos($image, '.')),
            'r' => ($r_all / $counter),
            'g' => ($g_all / $counter),
            'b' => ($b_all / $counter),
            'combined' => (($r_all / $counter) + ($g_all / $counter) + ($b_all / $counter)) / 3,
        ];

        $patterns[] = $average;
        //print_r($average);

        //unset all varibales
        unset($image_url);
        unset($loaded_image);
        unset($width);
        unset($height);
        unset($y_array);
        unset($x_array);
        unset($rgb);
        unset($r);
        unset($g);
        unset($b);
        unset($r_all);
        unset($g_all);
        unset($b_all);
        unset($average);
    }    

    $fp = fopen('camos.csv', 'w');
    foreach ($patterns as $fields) {
        fputcsv($fp, $fields);
    }
    
    fclose($fp);
?>