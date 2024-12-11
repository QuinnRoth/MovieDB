<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$movie_id = $title = $genre = $release_year = $availability = $rent_date = $return_date = $renter_id = "";
$movie_id_err = $title_err = $genre_err = $release_year_err = $availability_err = $rent_date_err = $return_date_err = $renter_id_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Title
    $title = trim($_POST["title"]);
    if(empty($title)){
        $title_err = "Please enter a Title.";
    } elseif(!filter_var($title, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $title_err = "Please enter a valid title.";
    }

    // Validate Genre
    $genre = trim($_POST["genre"]);
    if(empty($genre)){
        $genre_err = "Please enter a genre.";
    } elseif(!filter_var($genre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $genre_err = "Please enter a valid genre.";
    }

    // Validate Movie ID
    $movie_id = trim($_POST["movie_id"]);
    if(empty($movie_id)){
        $movie_id_err = "Please enter movie_id.";     
    } elseif(!ctype_digit($movie_id)){
        $movie_id_err = "Please enter a positive integer value for movie_id.";
    }

    // Validate Release Year
    $release_year = trim($_POST["release_year"]);
    if(empty($release_year)){
        $release_year_err = "Please enter the release year.";     
    } elseif(!ctype_digit($release_year)){
        $release_year_err = "Please enter a valid year.";
    }

    // Validate Availability
    $availability = trim($_POST["availability"]);
    if(!isset($availability)){
        $availability_err = "Please enter the availability.";
    }

    // Validate Rent Date
    $rent_date = trim($_POST["rent_date"]);
    if(empty($rent_date)){
        $rent_date = NULL;
    }

    // Validate Return Date
    $return_date = trim($_POST["return_date"]);
    if(empty($return_date)){
        $return_date = NULL;     
    }

    // Validate Renter ID
    $renter_id = trim($_POST["renter_id"]);
    if(empty($renter_id)){
        $renter_id = NULL;
    } elseif(!ctype_digit($renter_id)){
        $renter_id_err = "Please enter a positive integer value for renter_id.";
    }

    if(!empty($return_date) && empty($rent_date)){
        $rent_date_err = "Please enter the rent date if a return date is provided.";
    }

    if(!empty($return_date) && $availability != 0){
        $availability_err = "Availability must be 0 if a return date is provided.";
    }

    // Check input errors before inserting in database
    if(empty($title_err) && empty($genre_err) && empty($release_year_err) && empty($movie_id_err) && empty($availability_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Movies (movie_id, title, genre, release_year, rent_date, return_date, availability, renter_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issssssi", $param_movie_id, $param_title, $param_genre, $param_release_year, $param_rent_date, $param_return_date, $param_availability, $param_renter_id);
            
            // Set parameters
            $param_movie_id = $movie_id;
            $param_title = $title;
            $param_genre = $genre;
            $param_release_year = $release_year;
            $param_rent_date = $rent_date;
            $param_return_date = $return_date;
            $param_availability = $availability;
            $param_renter_id = $renter_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "<center><h4>Error while creating new movie</h4></center>";
                $movie_id_err = "Enter a unique movie id.";
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
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a movie record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($movie_id_err)) ? 'has-error' : ''; ?>">
                            <label>Movie ID</label>
                            <input type="number" min="1" max="30" name="movie_id" class="form-control" value="<?php echo $movie_id; ?>">
                            <span class="help-block"><?php echo $movie_id_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label>Movie Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                            <span class="help-block"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($genre_err)) ? 'has-error' : ''; ?>">
                            <label>Movie Genre</label>
                            <input type="text" name="genre" class="form-control" value="<?php echo $genre; ?>">
                            <span class="help-block"><?php echo $genre_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($release_year_err)) ? 'has-error' : ''; ?>">
                            <label>Release Year</label>
                            <input type="number" name="release_year" class="form-control" value="<?php echo $release_year; ?>">
                            <span class="help-block"><?php echo $release_year_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($rent_date_err)) ? 'has-error' : ''; ?>">
                            <label>Rent Date</label>
                            <input type="date" name="rent_date" class="form-control" value="<?php echo $rent_date; ?>">
                            <span class="help-block"><?php echo $rent_date_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($return_date_err)) ? 'has-error' : ''; ?>">
                            <label>Return Date</label>
                            <input type="date" name="return_date" class="form-control" value="<?php echo $return_date; ?>">
                            <span class="help-block"><?php echo $return_date_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($availability_err)) ? 'has-error' : ''; ?>">
                            <label>Availability</label>
                            <input type="number" min="0" max="1" name="availability" class="form-control" value="<?php echo $availability; ?>">
                            <span class="help-block"><?php echo $availability_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($renter_id_err)) ? 'has-error' : ''; ?>">
                            <label>Renter ID</label>
                            <input type="number" min="1" max="20" name="renter_id" class="form-control" value="<?php echo $renter_id; ?>">
                            <span class="help-block"><?php echo $renter_id_err;?></span>
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