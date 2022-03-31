<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values



// Processing form data when form is submitted
if(isset($_POST['zatwierdz'])){

    // sprawdzanie nazwy gry
    $nazwaGry = $_POST["nazwaGry"];
   
    //sprawdzanie czasu rozp
    $czasRozpoczecia = $_POST["czasRozpoczecia"];
   

    //sprawdzanie czasu zak
    $czasZakonczenia = $_POST["czasZakonczenia"];
    


    function getName($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
      
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
      }

      
    
    // Check input errors before inserting in database
    if(!empty($nazwaGry) && !empty($czasRozpoczecia) && !empty($czasZakonczenia) ){
        $random = getname(5);
        // Prepare an insert statement
        $sql = "INSERT INTO Gra (nazwaGry, kluczDostepu, czasRozpoczecia, czasZakonczenia) VALUES ('$nazwaGry','$random','$czasRozpoczecia','$czasZakonczenia')";
       
        if($link->query($sql) === TRUE) {
            echo "git gut";
            header("Location: index.php");
          } else {
            echo "Error updating record: " . $conn->error;
         }
    }else{
        echo("uzupelnij wszystkie pola !");
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>nowa gra</title>
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
                    <h2 class="mt-5">Dodaj nową grę</h2>

                    <form action="" method="post">
                        <div class="form-group">
                            <label>nazwa gry</label>
                            <input type="text" name="nazwaGry" class="form-control" value="">
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="md-form md-outline w-25">
                            <label for="default-picker">czas rozpoczecia</label>
                            <input type="time" name="czasRozpoczecia" id="czasRozpoczecia" class="form-control" placeholder="Select time">
                            <hr>
                            <label for="default-picker">czas zakonczenia</label>
                            <input type="time" name="czasZakonczenia" id="czasZakonczenia" class="form-control" placeholder="Select time">
                        </div>
                        <hr>
                        <input type="submit" class="btn btn-primary" name="zatwierdz" id="zatwierdz" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>