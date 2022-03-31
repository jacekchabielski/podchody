<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$wspX = $nazwaTestu =$wspY= "";
$nazwaTestu_err= $wspX_err = $wspY_err="";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
}
    
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>szczegoly gry</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Szczegoly gry</h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $idgry = $_GET['id'];
                    $sql = "SELECT * FROM Gra WHERE id = '$idgry'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        
                                        echo "<th>nazwa gry</th>";
                                        echo "<th>klucz dostepu do gry</th>";
                                        echo "<th>czas rozpoczecia gry</th>";
                                        echo "<th>czas zakonczenia gry</th>";
                                       
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        
                                        echo "<td>" . $row['nazwaGry'] . "</td>";
                                        echo "<td>" . $row['kluczDostepu'] . "</td>";
                                        echo "<td>" . $row['czasRozpoczecia'] . "</td>";
                                        echo "<td>" . $row['czasZakonczenia'] . "</td>";
                                        
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>brak danych</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    ?>

                    <?php

                    // Attempt select query execution
                    
                    $idtestu;
                    $sql2 = "SELECT * FROM Testy WHERE idGry = '$idgry'";
                    if($result = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                       
                                        echo "<th>nazwa Testu</th>";
                                        echo "<th>wsplrzedna geo. X</th>";
                                        echo "<th>wspolrzedna geo. Y</th>";
                                        
                                       
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                            $idtestu = $row['id'];
                                        echo "<td>" . $row['nazwaTestu'] . "</td>";
                                        echo "<td>" . $row['wspX'] . "</td>";
                                        echo "<td>" . $row['wspY'] . "</td>";
                                        
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>brak danych</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    
                    ?>

                        <?php

                        // Attempt select query execution
                        $idgry = $_GET['id'];
                        
                        $sql2 = "SELECT * FROM pytania WHERE idTestu = '$idtestu'";
                        if($result = mysqli_query($link, $sql2)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                        
                                            echo "<th>pytanie do testu</th>";
                                            echo "<th>odpowiedz</th>";
                                            
                                            
                                        
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        
                                            echo "<td>" . $row['pytanie'] . "</td>";
                                            echo "<td>" . $row['odpowiedz'] . "</td>";
                                            
                                            
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else{
                                echo '<div class="alert alert-danger"><em>brak danych</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                         ?>

                        <?php

                        $sql2 = "SELECT * FROM podpowiedzi WHERE idTesty = '$idtestu'";
                        if($result = mysqli_query($link, $sql2)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                        
                                            echo "<th>tresc podpowiedzi do testu</th>";
                                            
                                        
                                        
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        
                                            echo "<td>" . $row['trescPodpowiedzi'] . "</td>";
                                        
                                            
                                            
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else{
                                echo '<div class="alert alert-danger"><em>brak danych</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        ?>
                </div>
            </div>        
            <a href="index.php" class="btn btn-danger pull-right mr-2">powrot</a>
        </div>
       
    </div>

</body>
</html>