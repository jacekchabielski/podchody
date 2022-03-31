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



        <!-- WYBIERANIE GRY -->
        <div class="form-group w-50">
            <label for="wyborOsobyDoTestu">Wybierz gre</label>
            <select class="form-control w-25" name="graKlucz" id="graKlucz">
                <?php
                require_once "config.php";

               

            
                    $sql = "SELECT * FROM Gra";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<option value=".$row['id'].">" . $row['nazwaGry'] . "</option>";       
                                    }
                                    
                                mysqli_free_result($result);
                            } else{
                                echo '<div class="alert alert-danger"><em>Brak pytań w bazie !</em></div>';
                            }
                            } else{
                            echo "cos sie wydupilo";
                        }


                        $sq = "SELECT pseudonim,wspX,wspY FROM gracze";
                        if($result = mysqli_query($link, $sq)){
                            if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_array($result)){
                                        $array[] = $row;  
                                    }
                                    
                                mysqli_free_result($result);
                            } else{
                               
                            }
                            } else{
                            echo "cos sie wydupilo";
                        }

                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nazwaTestu">nazwa testu</label>
            <input type="text" name="nazwaTestu" class="form-control w-25" placeholder="nazwa testu">
        </div>

        <!-- WYBOR PYTAN Z LISTY-->
        <div class="form-group w-50">
            <label for="exampleFormControlSelect2">Wybierz pytania</label>
            <select multiple class="form-control" name="colors[]" id="colors">
            <?php
                
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
            <label for="Podpowiedz">podpowiedz dla testu</label>
            <textarea class="form-control w-25" name="podpowiedz" placeholder="podpowiedz do testu" rows="3"></textarea>
        </div>
        <input type="hidden" name="ukrytyX" id="ukrytyX" value="1234" />
        <input type="hidden" name="ukrytyY" id="ukrytyY" value="1234" />
        <button type="submit" name="dodajpkt" id="dodajPunkt" class="btn btn-primary">Dodaj test do gry</button>
    </form>

        <button class="btn btn-warning" name="zaznaczPunkt" id="zaznaczPunkt">zaznacz punkt</button>
    

        
        

    <?php
        //error_reporting(0);
        $nazwatestu =  "";
        $nazwatestu_err = "";
        //to dodalem
        $podpowiedz = "";
        $podpowiedz_err = "";
        
            
        

        if($_SERVER["REQUEST_METHOD"] == "POST"){
          
           
            
            $input_nazwatestu = trim($_POST["nazwaTestu"]);
            if(empty($input_nazwatestu)){
                $nazwatestu_err = "podaj nazwe testu !";
            }else{
                $nazwatestu = $input_nazwatestu;
            }

            //to dodalem
            $input_podpowiedz = trim($_POST["podpowiedz"]);
            if(empty($input_podpowiedz)){
                $podpowiedz_err = "Bez podpowiedzi bedzie troche ciezko !!";
            }else{
                $podpowiedz = $input_podpowiedz;
            }

            

            
            // Check input errors before inserting in database
            if(empty($nazwatestu_err) ){
            
            //
            
            $wX = $_POST['ukrytyX'];
            $wY = $_POST['ukrytyY'];
            
             

                $sql = "INSERT INTO Testy (nazwaTestu, wspX, wspY) VALUES (?, ?, ?)";
                 
                if($stmt = mysqli_prepare($link, $sql)){
                    
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sss", $param_pytanie,$wspX,$wspY);
                    
                    // Set parameters
                    $param_pytanie = $nazwatestu;
                    $wspX = $wX;
                    $wspY = $wY;
                    
                    
                    if(mysqli_stmt_execute($stmt)){
                        $lastid = mysqli_insert_id($link);
                        echo "good job";
                        //echo $lastid;
                        //exit();
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                   
                }
               
                mysqli_stmt_close($stmt);

                $selectedColors = filter_input(
                    INPUT_POST,
                    'colors',
                    FILTER_SANITIZE_STRING,
                    FILTER_REQUIRE_ARRAY
                );
    
                if($selectedColors){
                    
                    //petla od wyciagania zaznaczonych pytan
                    if ($link->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                      }
                    foreach ($selectedColors as $color){
                        $sqll = "UPDATE pytania SET idTestu = $lastid WHERE id = $color";
                        if($link->query($sqll) === TRUE) {
                            echo "Record updated successfully";
                          } else {
                            echo "Error updating record: " . $conn->error;
                         }
                        //echo('<li>');
                        //echo($color);
                        //echo $lastid;
                        //echo('</li>');
                    }
                
                }

               
                //dodanie testu do klucza
                $graId = filter_input(INPUT_POST, 'graKlucz', FILTER_SANITIZE_STRING);
                if ($graId) {
                    //echo $osobaKlucz
                    if ($link->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                      }

                      $sqlk = "UPDATE Testy SET idGry = $graId WHERE id = $lastid";
                      if($link->query($sqlk) === TRUE) {
                            echo "done";
                      } else {
                            echo "Error updating record: " . $conn->error;
                        }

                }else{
                    echo "nie wybrano klucza";
                }
                   


                    //to dodalem
                if(empty($podpowiedz_err)){
                    $sqlp = "INSERT INTO podpowiedzi (trescPodpowiedzi, idTesty) VALUES (?, ?)";
                    if($stmt = mysqli_prepare($link, $sqlp)){
                    
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ss", $param_podpowiedz,$lastid);
                        
                        // Set parameters
                        $param_podpowiedz = $podpowiedz;
                        
                        
                        if(mysqli_stmt_execute($stmt)){
                            
                            echo "good job dodano podpowiedz";
                         
                        } else{
                            echo "lipa z podpowiedzia";
                        }
                       
                    }
                   
                    mysqli_stmt_close($stmt);
                }
                
                
            }
            
            
            // Close connection
            mysqli_close($link);
        }
    ?>




    <div id="mapid"></div>
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>

    <script>
        
        //console.log(x);
        var tempArray = <?php echo json_encode($array); ?>;

        var wsX;
        var wsY;
        var gracz;


        var mymap = L.map('mapid').setView([50.43, 16.66], 15);
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(mymap);
        
      //console.log(tempArray[0].wspX);
      for(let index of tempArray){
        
        //wsX = tempArray.;
        wsX = index.wspX;
        wsY = index.wspY;
        gracz = index.pseudonim;

    
        //wsY = tempArray[i].wspY;
        //var gracz = tempArray[i].pseudonim ;
        var marker = L.marker([wsX,wsY]).addTo(mymap);
        marker.bindPopup("gracz: "+gracz+" <br>"+wsX+"<br>"+wsY).openPopup();
        
 
       }
      console.log(tempArray.length);
      

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
        btn = document.getElementById("ukrytyX");
        btn2 = document.getElementById("ukrytyY");

        btn.value=lat;
        btn2.value=lng;
    
        
       var refresh = window.location.protocol + "//" + window.location.host + window.location.pathname + '?wspX='+lat+'&wspY='+ lng;    
        window.history.pushState({ path: refresh }, '', refresh);
    });

      
        
 
  
   
    </script>
</body>
</html>
