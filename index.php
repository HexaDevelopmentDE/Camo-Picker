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
                <img src=<?php echo $image_url;?>>
            </td>
            <td id="average_rgb">
                <?php echo "
                r: ".$average['r']."<br> 
                g: ".$average['g']."<br> 
                b: ".$average['b']."<br> 
                combined: ".$average['combined']; ?>
            </td>
            <td>
                <img id='camo'>
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
            console.log(arr);

            arr = sort(arr);
            
            console.log(arr);


            for(let i = 0; i < camos.length; i++) {
                let camo = camos[i][4] * mult;
                let difference = Math.abs(combined - camo);
                if(difference == arr[0])
               
                img_url = "./img/camos/" + camos[i][0] + ".jpg";
                console.log(img_url);
            }

            if(img_url != "") {
                let img = document.getElementById("camo");
                img.src = img_url;
            } else {
                const text = document.getElementById("text");
                text.innerText = "no matching camo was found.";
            }
        }

        function sort (arr) {
            let tmp = [];
            let new_arr = [];

            for(let i = 1; i < arr.length; i++) {
                r_1 = arr[i-1][1];
                r_2 = arr[i][1];
                g_1 = arr[i-1][2];
                g_2 = arr[i][2];
                b_1 = arr[i-1][3];
                b_2 = arr[i][3];

                if(
                    (r_2 < r_1 && g_2 < g_1 && b_2 < b_1) ||
                    (r_2 < r_1 && g_2 < g_1 && b_2 < b_1)
                ) {
                    new_arr.push(arr[i]);
                    new_arr.push(arr[i-1]);
                } else {
                    new_arr.push(arr[i-1]);
                    new_arr.push(arr[i]);
                }
            }

            return new_arr;
        }
    </script>
</body>
</html>