<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$pytanie = $odpowiedz = "";
$pytanie_err = $odpowiedz_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // sprawdzanie pytania
    $input_pytanie = trim($_POST["pytanie"]);
    if(empty($input_pytanie)){
        $pytanie_err = "podaj pytanie !";
    }else{
        $pytanie = $input_pytanie;
    }
    
    // sprawdzanie odpowiedzi
    $input_odpowiedz = trim($_POST["odpowiedz"]);
    if(empty($input_odpowiedz)){
        $odpowiedz_err = "prosze podac odpowiedz !";     
    } else{
        $odpowiedz = $input_odpowiedz;
    }
    
    
    // Check input errors before inserting in database
    if(empty($pytanie_err) && empty($odpowiedz_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO pytania (pytanie, odpowiedz) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_pytanie, $param_odpowiedz);
            
            // Set parameters
            $param_pytanie = $pytanie;
            $param_odpowiedz = $odpowiedz;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>utworz pytanie</title>
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
                    <h2 class="mt-5">dodaj pytanie</h2>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>pytanie</label>
                            <input type="text" name="pytanie" class="form-control <?php echo (!empty($pytanie_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pytanie; ?>">
                            <span class="invalid-feedback"><?php echo $pytanie;?></span>
                        </div>
                        <div class="form-group">
                            <label>odpowiedz</label>
                            <textarea name="odpowiedz" class="form-control <?php echo (!empty($odpowiedz_err)) ? 'is-invalid' : ''; ?>"><?php echo $odpowiedz; ?></textarea>
                            <span class="invalid-feedback"><?php echo $odpowiedz;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>