<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mapa</title>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        #mapid {
            height: 600px; 
            width: 600px;
        }
    </style>    
</head>
<body>
  
  
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div class="form-group w-50">
            <label for="exampleFormControlSelect2">Wybierz pytania</label>
            <select multiple class="form-control">
            <?php
                require_once "config.php";
                $sql = "SELECT * FROM pytania";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<option value=".$row['id'].">" . $row['pytanie'] . "</option>";       
                                }
                                
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Brak pytań w bazie !</em></div>';
                        }
                    } else{
                        echo "cos sie wydupilo";
                    }
                

            ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nazwaTestu">nazwa testu</label>
            <input type="text" name="pytanie" class="form-control w-25" placeholder="nazwa testu">
        </div>
        <button type="submit" name="dodajpkt" id="dodajPunkt" class="btn btn-primary">dodaj punkt</button>
    </form>
        <button class="btn btn-warning" name="zaznaczPunkt" id="zaznaczPunkt" >zaznacz punkt</button>

    <?php
        $nazwatestu =  "";
        $nazwatestu_err = "";

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            // sprawdzanie pytania
            $input_nazwatestu = trim($_POST["pytanie"]);
            if(empty($input_nazwatestu)){
                $nazwatestu_err = "podaj pytanie !";
            }else{
                $nazwatestu = $input_nazwatestu;
            }
            

            
            // Check input errors before inserting in database
            if(empty($nazwatestu_err) ){
               
                $wspX = $_GET['wspX'];
                $wspY = $_GET['wspY'];

                $sql = "INSERT INTO Testy (nazwaTestu, wspX, wspY) VALUES (?, ?, ?)";
                 
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sss", $param_pytanie,$wspX,$wspY);
                    
                    // Set parameters
                    $param_pytanie = $nazwatestu;
                    
                    
                    if(mysqli_stmt_execute($stmt)){
                        echo "good job";
                        //exit();
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
                
                mysqli_stmt_close($stmt);

                /* 
                $wybranePytania = filter_input(
                    INPUT_POST,
                    'pytania',
                    FILTER_SANITIZE_STRING,
                    FILTER_REQUIRE_ARRAY
                );
    
                if($wybranePytania){
                    
                    //petla od wyciagania zaznaczonych pytan
                    foreach ($wybranePytania as $pytanie){
                        $sqldwa = "UPDATE pytania SET idTestu = $pytanie WHERE id = $pytanie ";

                        if ($link->query($sqldwa) === TRUE) {
                            echo "Record updated successfully";
                          } else {
                            echo "Error updating record: " . $link->error;
                          }
                        //echo('<li>');
                        //echo($pytanie);
                        //echo('</li>');
                    }
                
                }
                */
            }
           
            
            // Close connection
            mysqli_close($link);
        }
    ?>




    <div id="mapid"></div>
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>

    <script>
        
    
        var mymap = L.map('mapid').setView([50.47, 17.33], 15);
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(mymap);
        
      
        
    //var marker = L.marker([50.47,17.33]).addTo(mymap);
    //marker.bindPopup("bello thre").openPopup();

    var lat ;
    var lng ;
    mymap.on('click', function(e){
    
    var coord = e.latlng;
    lat = coord.lat;
    lng = coord.lng;
   
    console.log("You clicked the map at latitude: " + lat + " and longitude: " + lng);

    });
    document.getElementById("zaznaczPunkt").addEventListener("click", function() {

        var marker2 = L.marker([lat,lng]).addTo(mymap);
        marker2.bindPopup("współrzędne: <br>"+lat+"<br>"+lng).openPopup();

        //const urlParams = new URLSearchParams(window.location.search);

        //urlParams.set('wspX', lat);
        //urlParams.set('wspY', lng);

        //window.location.search = urlParams;
        
        var refresh = window.location.protocol + "//" + window.location.host + window.location.pathname + '?wspX='+lat+'&wspY='+ lng;    
        window.history.pushState({ path: refresh }, '', refresh);
        
        
    });
  

  
   
    </script>
</body>
</html>
