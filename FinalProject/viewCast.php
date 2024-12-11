<?php
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cast</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
	   <script type="text/javascript">
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
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View cast</h2>
						<!-- DONT THINK WE NEED THIS <a href="createDependents.php" class="btn btn-success pull-right">Add Dependent</a>-->
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["movie_id"]) && !empty(trim($_GET["movie_id"]))){
	$_SESSION["movie_id"] = $_GET["movie_id"];
}

if(isset($_SESSION["movie_id"]) ){
	
	
    // Prepare a select statement
    $sql = "SELECT m.title, d.name AS director, a.name AS actor 
            FROM Movies m 
            LEFT JOIN Directs di ON m.movie_id = di.movie_id 
            LEFT JOIN Director d ON di.director_id = d.director_id 
            LEFT JOIN `Acts in` ai ON m.movie_id = ai.movie_id 
            LEFT JOIN Actors a ON ai.actor_id = a.actor_id 
            WHERE m.movie_id = ?";
  
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_movie_id);      
        // Set parameters
       $param_movie_id = $_SESSION["movie_id"];

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			echo"<h4> Cast for Movie_ID =".$param_movie_id."</h4><p>";
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Title</th>";
                            echo "<th>Director</th>";
							echo "<th>Actor</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                            echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . $row['director'] . "</td>";
						    echo "<td>" . $row['actor']."</td>";
						echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "<p class='lead'><em>No records were found.</em></p>";
			}
//				mysqli_free_result($result);
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
        }
    }     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>					                 					
	<p><a href="index.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>