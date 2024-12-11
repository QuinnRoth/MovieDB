<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["movie_id"]) && !empty(trim($_POST["movie_id"]))) {
        $movie_id = trim($_POST["movie_id"]);

        // Delete movie and update Renter table
        $delete_movie_sql = "DELETE FROM Movies WHERE movie_id = ?";
        $update_renter_sql = "UPDATE Renter SET movie_id = NULL WHERE movie_id = ?";

        // Prepare and execute the DELETE query
        if ($stmt1 = mysqli_prepare($link, $delete_movie_sql)) {
            mysqli_stmt_bind_param($stmt1, "s", $movie_id);
            if (mysqli_stmt_execute($stmt1)) {
                // Prepare and execute the UPDATE query
                if ($stmt2 = mysqli_prepare($link, $update_renter_sql)) {
                    mysqli_stmt_bind_param($stmt2, "s", $movie_id);
                    if (mysqli_stmt_execute($stmt2)) {
                        // Redirect to index page
                        header("location: index.php");
                        exit();
                    } else {
                        echo "Error: Could not execute the UPDATE statement.";
                    }
                    mysqli_stmt_close($stmt2);
                } else {
                    echo "Error: Could not prepare the UPDATE statement.";
                }
            } else {
                echo "Error: Could not execute the DELETE statement.";
            }
            mysqli_stmt_close($stmt1);
        } else {
            echo "Error: Could not prepare the DELETE statement.";
        }
    } else {
        echo "Error: Missing or invalid movie_id.";
    }
    mysqli_close($link);
} else {
    if (isset($_GET["movie_id"]) && !empty(trim($_GET["movie_id"]))) {
        $movie_id = trim($_GET["movie_id"]);
    } else {
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
