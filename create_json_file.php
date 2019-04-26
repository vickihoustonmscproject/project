<!DOCTYPE html>
<html>
     <head>
          <title>Create Menu JSON File</title>
     </head>
     <body>
          <?php
          function get_data() {
          $servername = "10.16.16.18";
          $username = "Vicki-rsd-u-224364";
          $password = "Century1980";
          $dbname = "Vicki-rsd-u-224364";


          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);
          $sql = "SELECT * FROM gallery";
          $result = mysqli_query($conn, $sql);
          $json_array = array();
          while($row = mysqli_fetch_assoc($result))
          {
               $json_array[] = array(
                 'dishID' => $row["ImageID"],
                 'dish' => $row["MenuTitle"],
                 'price' => $row["MenuPrice"],
                 'image' => $row["ImageFile"],
                 'category' => $row["MenuCategory"],
                 'options' => $row["OptionsCategory"],
                 'veg' => $row["Veg"],
                 'gluten' => $row["AlGluten"],                   
                 'description' => $row["ImageDescription"]
               );
          }
          /*echo '<pre>';
          print_r(json_encode($json_array));
          echo '</pre>';*/
          return json_encode($json_array);

        }
        //get_data();

          $file_name = 'menu.json';

          if (file_put_contents($file_name, get_data())) {
            echo $file_name.' file created';
          }
          else {
            echo 'There is some error';
          }
          ?>
     </body>
</html>
