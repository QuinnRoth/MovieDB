<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		     <h2> Project CS 340 Movie Rental</h2> 
                       <p>In this website you can:
				<ol> 	<li> CREATE new renters </li>
					<li> RETRIEVE movies and who's renting them, movies that have actors in it born after 1989, many movies of each genre there are.</li>
                                        <li> UPDATE a renter's name</li>
					<li> DELETE renters </li>
				</ol>
		       <h2 class="pull-left">Movie Details</h2>
               <a href="createMovie.php" class="btn btn-success pull-right">Add New Movie</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select all renter query execution
					// *****
					// Insert your function for Salary Level
					/*
						$sql = "SELECT Ssn,Fname,Lname,Salary, Address, Bdate, PayLevel(Ssn) as Level, Super_ssn, Dno
							FROM EMPLOYEE";
					*/
                    $sql = "SELECT movie_id, title, genre, release_year, availability, rent_date, return_date, renter_id 
                            FROM Movies";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=8%>ID</th>";
                                        echo "<th width=10%>Title</th>";
                                        echo "<th width=10%>Genre</th>";
                                        echo "<th width=8%>Release year</th>";
                                        echo "<th width=10%>Availability</th>";
                                        echo "<th width=10%>Date Rented</th>";
                                        echo "<th width=8%>Return Date</th>";
                                        echo "<th width=8%>Renter ID</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['movie_id'] . "</td>";
                                        echo "<td>" . $row['title'] . "</td>";
                                        echo "<td>" . $row['genre'] . "</td>";
                                        echo "<td>" . $row['release_year'] . "</td>";
                                        echo "<td>" . ($row['availability'] == 1 ? "Yes" : "No") . "</td>";
                                        echo "<td>" . $row['rent_date'] . "</td>";
                                        echo "<td>" . $row['return_date'] . "</td>";
                                        echo "<td>" . $row['renter_id'] . "</td>";
                                        echo "<td>";
                                        //if a button is commented then it needs to be worked on
                                           echo "<a href='viewRenter.php?movie_id=". $row['movie_id']."' title='View Renter' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                           echo "<a href='viewCast.php?movie_id=". $row['movie_id']."' title='View Cast' data-toggle='tooltip'><span class='glyphicon glyphicon-user'></span></a>";
                                           echo "<a href='deleteMovie.php?movie_id=". $row['movie_id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                           echo "<a href='updateMovie.php?movie_id=". $row['movie_id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";	
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                    }
					echo "<br> <h2> Renter Info </h2> <br>";
					
                    // Select renter info
					// You will need to Create a DEPT_STATS table
					
                    $sql2 = "SELECT * FROM Renter";
                    if($result2 = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result2) > 0){
                            echo "<div class='col-md-4'>";
							echo "<table width=30% class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=20%>ID</th>";
                                        echo "<th width = 20%>Name</th>";
                                        echo "<th width = 40%>ID of rented movie</th>";
	
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result2)){
                                    echo "<tr>";
                                        echo "<td>" . $row['renter_id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['movie_id'] . "</td>";
                                        echo "<td>";
                                        //updated these to work for renter (hopefully)
                                       echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result2);
                        } else{
                            echo "<p class='lead'><em>No records were found for renters.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql2. <br>" . mysqli_error($link);
                    }
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
                <a href="createRenter.php" class="btn btn-success pull-right">Add New Renter</a>

</body>
</html>
