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
        'combined' => (($r_all / $counter) + ($g_all / $counter) + ($b_all / $counter)) / 3,
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
            <th>Camo<th>
        </tr>
        <tr>
            <td>
                <img src=<?php echo $image_url;?> id="compare_img" style="height: 300px;">
            </td>
            <td id="average_rgb">
                <?php echo "
                r: ".$average['r']."<br> 
                g: ".$average['g']."<br> 
                b: ".$average['b']."<br> 
                combined: ".$average['combined']; ?>
            </td>
            <td>
                <img id='camo1'>
                <img id='camo2'>
                <img id='camo3'>
                <label id='text'></label>
            </td>
        </tr>
    </table>

    <script>
        const r = <?php echo $average['r']; ?>;
        const g = <?php echo $average['g']; ?>;
        const b = <?php echo $average['b']; ?>;
        const combined = <?php echo $average['combined']; ?>;

        const camos = <?php echo json_encode(array_map('str_getcsv', file('./patterns/camos.csv')));?>;

        const div = document.getElementById("average_rgb");
        div.style.backgroundColor = 'rgb(' + r + ',' + g + ',' + b + ')';
        checkCamo();


        function checkCamo() {
            let img_url = "";
            let arr = [];
            const mult = 1;

            for(let i = 0; i < camos.length; i++) {
                let r_camo = camos[i][1] * mult;
                let g_camo = camos[i][2] * mult;
                let b_camo = camos[i][3] * mult;
                let r_diff = Math.abs(r - r_camo);
                let g_diff = Math.abs(g - g_camo);
                let b_diff = Math.abs(b - b_camo);
               
                arr.push([camos[i][0], r_diff, g_diff, b_diff]);
            }            
            // arr => array mit den Camos und den Unterschieden im rgb
            camo = sort(arr);
            if(Array.isArray(camo)) {
                img_url_1 = "./img/camos/" + camo[0] + ".jpg";
                //console.log(img_url_1);
                img_url_2 = "./img/camos/" + camo[1] + ".jpg";
                //console.log(img_url_2);
                img_url_3 = "./img/camos/" + camo[2] + ".jpg";
                //console.log(img_url_3);
            } else {
                img_url = "./img/camos/" + camo + ".jpg";
                //console.log(img_url);
            }

            if(img_url != "") {
                let img = document.getElementById("camo");
                img.src = img_url;
                img.style.height = document.getElementById("compare_img").style.height;
            } else {
                let img1 = document.getElementById("camo1");
                img1.src = img_url_1;
                img1.style.height = document.getElementById("compare_img").style.height;
           
                let img2 = document.getElementById("camo2");
                img2.src = img_url_2;
                img2.style.height = document.getElementById("compare_img").style.height;
           
                let img3 = document.getElementById("camo3");
                img3.src = img_url_3;
                img3.style.height = document.getElementById("compare_img").style.height;
           
            }
        }

        function sort (arr) {

            //console.log("Sortiert nach 'R':");
            const r_arr = arr.sort((a, b) => a[1] - b[1])[0][0];
            //console.log(r_arr);

            //console.log("Sortiert nach 'G':");
            const g_arr = arr.sort((a, b) => a[2] - b[2])[0][0];
            //console.log(g_arr);

            //console.log("Sortiert nach 'B':");
            const b_arr = arr.sort((a, b) => a[3] - b[3])[0][0];
            //console.log(b_arr);
            
            if((r_arr[0][0] == g_arr[0][0]) && (r_arr[0][0] == b_arr[0][0])) {
                //alle drei identzisch
                //console.log("trigger 1");
                return r_arr;
            } else if((r_arr[0][0] != g_arr[0][0]) && (r_arr[0][0] != b_arr[0][0]) && (g_arr[0][0] != b_arr[0][0])) {
                //alle drei verschieden
                //console.log("trigger 2");
                return [r_arr, g_arr, b_arr];
            } else if((r_arr[0][0] == g_arr[0][0]) && (r_arr[0][0] != b_arr[0][0])){
                //r & g identisch
                //console.log("trigger 3");
                return r_arr;
            } else if((r_arr[0][0] != g_arr[0][0]) && (r_arr[0][0] == b_arr[0][0])){
                //r & b identisch
                //console.log("trigger 4");
                return r_arr;
            } else if((b_arr[0][0] == g_arr[0][0]) && (r_arr[0][0] != b_arr[0][0])){
                //b & g identisch
                //console.log("trigger 5");
                return b_arr;
            }
        }
    </script>
</body>
</html>