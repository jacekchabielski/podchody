<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>podchody</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Aktualne pytania</h2>
                        <a href="createPytanie.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Dodaj nowe pytanie</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM pytania";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>pytanie</th>";
                                        echo "<th>odpowiedz</th>";
                                       
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['pytanie'] . "</td>";
                                        echo "<td>" . $row['odpowiedz'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="updatePytanie.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deletePytanie.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Brak pytań w bazie !</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    ?>
                </div>
            </div>        
        </div>
    </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Obecne gry</h2>
                        <a href="createGra.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Stworz nową grę</a> 
                    </div>

                    <?php
                    // Include config file
                    require_once "config.php";
                   

                    // Attempt select query execution
                    $sql = "SELECT * FROM Gra";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>nazwa gry</th>"; 
                                        echo "<th>klucz dostepu</th>"; 
                                        echo "<th>Rozpoczecie</th>"; 
                                        echo "<th>Koniec</th>"; 
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['nazwaGry'] . "</td>";
                                        echo "<td>" . $row['kluczDostepu'] . "</td>";
                                        echo "<td>" . $row['czasRozpoczecia'] . "</td>";
                                        echo "<td>" . $row['czasZakonczenia'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="deleteGra.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo '<a href="SzczegolyGry.php?id='. $row['id'] .'" title="Edit Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Obecnie nie ma zadnej gry!</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>      
            <a href="wyswietlWiecej.php" class="btn btn-success pull-right">wiecej info</a>  
        </div>
        <a href="ustawPunkt.php" class="btn btn-success pull-right mr-2">ustaw punkty</a>
        
    </div>

   
        
</body>
</html>