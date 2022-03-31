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
    
    // sprawdzanie nazwy Testu
    $input_nazwaTestu= trim($_POST["nazwaTestu"]);
    if(empty($input_nazwaTestu)){
        $nazwaTestu_err = "wpisz nazwe testu !";
    }else{
        $nazwaTestu = $input_nazwaTestu;
    }
    
    // sprawdzanie wspX
    $input_wspX = trim($_POST["wspX"]);
    if(empty($input_wspX)){
        $wspX_err = "prosze podac odpowiedz !";     
    } else{
        $wspX = $input_wspX;
    }

    // sprawdzanie wspY
    $input_wspY = trim($_POST["wspY"]);
    if(empty($input_wspY)){
        $wspY_err = "prosze podac odpowiedz !";     
    } else{
        $wspY = $input_wspY;
    }
    
    
    
    // Check input errors before inserting in database
    if( empty($nazwaTestu_err) && empty($wspX_err)  && empty($wspY_err)){
        // Prepare an update statement
        $sql = "UPDATE Testy SET nazwaTestu=?, wspX=?, wspY=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_nazwaTestu, $param_wspX, $param_wspY);
            
            // Set parameters
            $param_nazwaTestu = $nazwaTestu;
            $param_wspX = $wspX;
            $param_wspY = $wspY;
            
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
        $sql = "SELECT * FROM Testy WHERE id = ?";
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
                    $nazwaTestu = $row["nazwaTestu"];
                    $wspX = $row["wspX"];
                    $wspY = $row["wspY"];
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
                    <h2 class="mt-5">zaktualizuj Testy</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>nazwa testu</label>
                            <input type="text" name="nazwaTestu" class="form-control <?php echo (!empty($nazwaTestu_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nazwaTestu; ?>">
                            <span class="invalid-feedback"><?php echo $nazwaTestu;?></span>
                        </div>
                        <div class="form-group">
                            <label>wspX</label>
                            <textarea name="wspX" class="form-control <?php echo (!empty($wspX_err)) ? 'is-invalid' : ''; ?>"><?php echo $wspX; ?></textarea>
                            <span class="invalid-feedback"><?php echo $wspX;?></span>
                        </div>
                        <div class="form-group">
                            <label>wspY</label>
                            <textarea name="wspY" class="form-control <?php echo (!empty($wspY_err)) ? 'is-invalid' : ''; ?>"><?php echo $wspY; ?></textarea>
                            <span class="invalid-feedback"><?php echo $wspY;?></span>
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