<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$pytanie = $odpowiedz = "";
$pytanie_err = $odpowiedz_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
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
    if( empty($odpowiedz_err) && empty($pytanie_err) ){
        // Prepare an update statement
        $sql = "UPDATE pytania SET pytanie=?, odpowiedz=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_pytanie, $param_odpowiedz,$param_id);
            
            // Set parameters
            $param_pytanie = $pytanie;
            $param_odpowiedz = $odpowiedz;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM pytania WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $pytanie = $row["pytanie"];
                    $odpowiedz = $row["odpowiedz"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>zaktualizuj pytania</title>
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
                    <h2 class="mt-5">zaktualizuj pytania</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Pytanie</label>
                            <input type="text" name="pytanie" class="form-control <?php echo (!empty($pytanie_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pytanie; ?>">
                            <span class="invalid-feedback"><?php echo $pytanie_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Odpowiedz</label>
                            <textarea name="odpowiedz" class="form-control <?php echo (!empty($odpowiedz_err)) ? 'is-invalid' : ''; ?>"><?php echo $odpowiedz; ?></textarea>
                            <span class="invalid-feedback"><?php echo $odpowiedz_err;?></span>
                        </div>
                      
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>