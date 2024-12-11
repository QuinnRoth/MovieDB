<?php
session_start(); 
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$availability = $rent_date = $return_date = $renter_id = "";
$availability_err = $rent_date_err = $return_date_err = $renter_id_err = "";

// Check if movie_id is set
if (isset($_GET["movie_id"]) && !empty(trim($_GET["movie_id"]))) {
    $_SESSION["movie_id"] = $_GET["movie_id"];

    // Prepare a select statement
    $sql1 = "SELECT * FROM Movies WHERE movie_id = ?";
    if ($stmt1 = mysqli_prepare($link, $sql1)) {
        mysqli_stmt_bind_param($stmt1, "s", $param_movie_id);
        $param_movie_id = trim($_GET["movie_id"]);

        if (mysqli_stmt_execute($stmt1)) {
            $result1 = mysqli_stmt_get_result($stmt1);
            if (mysqli_num_rows($result1) > 0) {
                $row = mysqli_fetch_array($result1);

                $availability = $row['availability'];
                $rent_date = $row['rent_date'];
                $return_date = $row['return_date'];
                $renter_id = $row['renter_id'];
            }
        }
    }
}

// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_SESSION["movie_id"];

    // Validate availability
    $availability = trim($_POST["availability"]);
    if ($availability !== "0" && $availability !== "1") {
        $availability_err = "Please select a valid availability (0 or 1).";
    }

    // Validate rent date
    $rent_date = trim($_POST["rent_date"]);
    if (!empty($rent_date) && !strtotime($rent_date)) {
        $rent_date_err = "Please enter a valid rent date.";
    }

    // Validate return date
    $return_date = trim($_POST["return_date"]);
    if (!empty($return_date) && !strtotime($return_date)) {
        $return_date_err = "Please enter a valid return date.";
    }

    // Validate renter ID
    $renter_id = trim($_POST["renter_id"]);
    if (!ctype_digit($renter_id) && !empty($renter_id)) {
        $renter_id_err = "Please enter a valid renter ID.";
    }

    // Check input errors before updating
    if (empty($availability_err) && empty($rent_date_err) && empty($return_date_err) && empty($renter_id_err)) {
        $sql = "UPDATE Movies SET availability=?, rent_date=?, return_date=?, renter_id=? WHERE movie_id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "isssi", $param_availability, $param_rent_date, $param_return_date, $param_renter_id, $param_movie_id);

            $param_availability = $availability;
            $param_rent_date = !empty($rent_date) ? $rent_date : NULL;
            $param_return_date = !empty($return_date) ? $return_date : NULL;
            $param_renter_id = !empty($renter_id) ? $renter_id : NULL;
            $param_movie_id = $movie_id;

            if (mysqli_stmt_execute($stmt)) {
                // Update the Renter table with the movie_id if a renter_id is provided
                if (!empty($renter_id)) {
                    $sql2 = "UPDATE Renter SET movie_id=? WHERE renter_id=?";
                    if ($stmt2 = mysqli_prepare($link, $sql2)) {
                        mysqli_stmt_bind_param($stmt2, "is", $param_movie_id, $param_renter_id);

                        $param_movie_id = $movie_id;
                        $param_renter_id = $renter_id;

                        if (!mysqli_stmt_execute($stmt2)) {
                            echo "Error updating Renter table.";
                        }
                    }
                }
                header("location: index.php");
                exit();
            } else {
                echo "Error updating record. Please try again.";
            }
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Movie</title>
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
                        <h2>Update Movie</h2>
                    </div>
                    <p>Edit the fields below and submit to update the movie details.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($availability_err)) ? 'has-error' : ''; ?>">
                            <label>Availability (0 for No, 1 for Yes)</label>
                            <input type="text" name="availability" class="form-control" value="<?php echo $availability; ?>">
                            <span class="help-block"><?php echo $availability_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($rent_date_err)) ? 'has-error' : ''; ?>">
                            <label>Rent Date (YYYY-MM-DD)</label>
                            <input type="text" name="rent_date" class="form-control" value="<?php echo $rent_date; ?>">
                            <span class="help-block"><?php echo $rent_date_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($return_date_err)) ? 'has-error' : ''; ?>">
                            <label>Return Date (YYYY-MM-DD)</label>
                            <input type="text" name="return_date" class="form-control" value="<?php echo $return_date; ?>">
                            <span class="help-block"><?php echo $return_date_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($renter_id_err)) ? 'has-error' : ''; ?>">
                            <label>Renter ID</label>
                            <input type="text" name="renter_id" class="form-control" value="<?php echo $renter_id; ?>">
                            <span class="help-block"><?php echo $renter_id_err; ?></span>
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
