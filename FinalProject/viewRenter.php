
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Renter</title>
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
                        <h2 class="pull-left">View Renter</h2>
						<!--<a href="addProject.php" class="btn btn-success pull-right">Add Project</a> DONT THINK WE NEED THIS BUTTON-->
                    </div>
                    <?php
session_start();
// Include config file
require_once "config.php";

// Check if movie_id parameter exists
if (isset($_GET["movie_id"]) && !empty(trim($_GET["movie_id"]))) {
    $_SESSION["movie_id"] = $_GET["movie_id"];
}

if (isset($_SESSION["movie_id"])) {
    // Prepare a select statement
    $sql = "SELECT R.renter_id, R.name, M.title 
            FROM Renter R 
            INNER JOIN Movies M ON R.movie_id = M.movie_id 
            WHERE R.movie_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_movie_id);

        // Set parameters
        $param_movie_id = $_SESSION["movie_id"];

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            // Check if rows were returned
            if (mysqli_num_rows($result) > 0) {
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th width='20%'>Renter ID</th>";
                echo "<th>Renter Name</th>";
                echo "<th>Movie Title</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                // Output data of each row
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['renter_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                mysqli_free_result($result);
            } else {
                echo "No records found.";
            }
        } else {
            echo "Error executing query.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
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