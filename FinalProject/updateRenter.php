<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Define variables and initialize with empty values
// Note: You can not update SSN 
$name = "";
$name_err = "" ;
// Form default values

if(isset($_GET["renter_id"]) && !empty(trim($_GET["renter_id"]))){
	$_SESSION["renter_id"] = $_GET["renter_id"];

    // Prepare a select statement
    $sql1 = "SELECT * FROM Renter WHERE renter_id = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt1, "s", $param_renter_id);      
        // Set parameters
       $param_renter_id = trim($_GET["renter_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

				$name = $row['name'];
			}
		}
	}
}
 
// Post information about the renter when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // the id is hidden and can not be changed
    $renter_id = $_SESSION["renter_id"];
    // Validate form data this is similar to the create renter file
    // Validate name
    $name = trim($_POST["name"]);

    if(empty($name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } 
    
    // Check input errors before inserting into database
    if(empty($name_err)){
        // Prepare an update statement
        $sql = "UPDATE Renter SET name=? WHERE renter_id=?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssdis", $param_name, $param_renter_id);
            
            // Set parameters
            $param_name = $name;
            $param_renter_id = $renter_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {

    // Check existence of renter_id (said Sid) parameter before processing further
	// Form default values

	if(isset($_GET["renter_id"]) && !empty(trim($_GET["renter_id"]))){
		$_SESSION["renter_id"] = $_GET["renter_id"];

		// Prepare a select statement
		$sql1 = "SELECT * FROM Renter WHERE renter_id = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt1, "s", $param_renter_id);      
			// Set parameters
			$param_renter_id = trim($_GET["renter_id"]);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);

					$name = $row['name'];
				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}                
			} else{
				echo "Error in renter_id while updating";
			}		
		}
			// Close statement
			mysqli_stmt_close($stmt1);
        
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
    <title>Movie DB</title>
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
                        <h3>Update Record for renter with id =  <?php echo $_GET["renter_id"]; ?> </H3>
                    </div>
                    <p>Please edit the input value and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>					
                        <input type="hidden" name="renter_id" value="<?php echo $renter_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>