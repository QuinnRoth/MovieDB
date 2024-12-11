<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$renter_id = $name = $movie_id = "";
$renter_id_err = $name_err = $movie_id_err = "" ;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $name = trim($_POST["name"]);
    if(empty($name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    }
 
    // Validate renter_id
    $renter_id = trim($_POST["renter_id"]);
    if(empty($renter_id)){
        $renter_id_err = "Please enter renter_id.";     
    } elseif(!ctype_digit($renter_id)){
        $renter_id_err = "Please enter a positive integer value of renter_id.";
    } 

    // Check input errors before inserting in database
    if(empty($renter_id_err) && empty($name_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Renter (renter_id, name) 
		        VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "is", $param_renter_id, $param_name);
            
            // Set parameters
			$param_renter_id = $renter_id;
            $param_name = $name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
				    header("location: index.php");
					exit();
            } else{
                echo "<center><h4>Error while creating new renter</h4></center>";
				$renter_id_err = "Enter a unique renter_id.";
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
    <title>Add Renter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Renter</h2>
                    </div>
                    <p>Please fill this form and submit to add a renter to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($renter_id_err)) ? 'has-error' : ''; ?>">
                            <label>Renter ID</label>
                            <input type="text" name="renter_id" class="form-control" value="<?php echo $renter_id; ?>">
                            <span class="help-block"><?php echo $renter_id_err;?></span>
                        </div>
                 
						<div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>