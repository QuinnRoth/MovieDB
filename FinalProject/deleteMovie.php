<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if movie_id is set and valid
    if (isset($_POST["movie_id"]) && !empty(trim($_POST["movie_id"]))) {
        $movie_id = trim($_POST["movie_id"]);

        // Prepare a delete statement
        $sql = "DELETE FROM Movies WHERE movie_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement
            mysqli_stmt_bind_param($stmt, "s", $movie_id);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the landing page
                header("location: index.php");
                exit();
            } else {
                echo "Error: Could not execute the delete statement.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: Could not prepare the delete statement.";
        }
    } else {
        echo "Error: Missing or invalid movie_id.";
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check for movie_id in the GET request
    if (isset($_GET["movie_id"]) && !empty(trim($_GET["movie_id"]))) {
        $movie_id = trim($_GET["movie_id"]);
    } else {
        // Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Movie</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
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
                        <h1>Delete Movie</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>"/>
                            <p>Are you sure you want to delete the movie with ID = <?php echo htmlspecialchars($movie_id); ?>?</p><br>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="index.php" class="btn btn-default">No</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
