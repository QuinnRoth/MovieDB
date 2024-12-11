<?php
	session_start();
	if(isset($_GET["renter_id"]) && !empty(trim($_GET["renter_id"]))){
		$_SESSION["renter_id"] = $_GET["renter_id"];
	}

    require_once "config.php";
	// Delete a renters record after confirmation
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_SESSION["renter_id"]) && !empty($_SESSION["renter_id"])){ 
			$Ssn = $_SESSION['renter_id'];
			// Prepare a delete statement
			$sql = "DELETE FROM Renter WHERE renter_id = ?";
   
			if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_renter_id);
 
				// Set parameters
				$param_renter_id = $renter_id;
       
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Records deleted successfully. Redirect to landing page
					header("location: index.php");
					exit();
				} else{
					echo "Error deleting the employee";
				}
			}
		}
		// Close statement
		mysqli_stmt_close($stmt);
    
		// Close connection
		mysqli_close($link);
	} else{
		// Check existence of id parameter
		if(empty(trim($_GET["renter_id"]))){
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
    <title>Delete Renter</title>
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
                        <h1>Delete Renter</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="renter_id" value="<?php echo ($_SESSION["renter_id"]); ?>"/>
                            <p>Are you sure you want to delete the account for <?php echo ($_SESSION["renter_id"]); ?>?</p><br>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>