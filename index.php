<?php
    $image_url = '.\img\demo\example.jpg';
    $image = imagecreatefromjpeg($image_url);

    $width = imagesx($image);
    $height = imagesy($image);
    $colors = array();
    $r_all = 0;
    $g_all = 0; 
    $b_all = 0;
    $counter = 0;
    
    for ($y = 0; $y < $height; $y++) {
    $y_array = array() ;
    
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($image, $x, $y);
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
    $colors[] = $y_array ;
    }

    $average = [
        'r' => ($r_all / $counter),
        'g' => ($g_all / $counter),
        'b' => ($b_all / $counter),
    ];
?>


<!doctype html>
<html>
<head>
<title>Camo Picker</title>
<meta name="description" content="Camo Picker">
<meta name="keywords" content="Camo Picker Landing Page">
</head>
<body>
    <h1>Camo Picker</h1>

    <h3>Demo:</h3>
    <table>
        <tr>
            <th>Image</th>
            <th>Values</th>
        </tr>
        <tr>
            <td><img src=<?php echo $image_url;?>></td>
            <td id="average_rgb"><?php echo "r: ".$average['r']."<br> g: ".$average['g']."<br> b: ".$average['b']; ?></td>
        </tr>
    </table>

    <script>
        const r = <?php echo $average['r']; ?>;
        const g = <?php echo $average['g']; ?>;
        const b = <?php echo $average['b']; ?>;

        const div = document.getElementById("average_rgb");
        div.style.backgroundColor = 'rgb(' + r + ',' + g + ',' + b + ')';
    </script>
</body>
</html>