<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://localhost/certificatemanagementsystem/img/favicon.ico">

    <title>All Certificates </title>

    <!-- Bootstrap core CSS -->
    <link href="http://localhost/certificatemanagementsystem/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://localhost/certificatemanagementsystem/css/bootstrap.min.cssnarrow-jumbotron.css"
        rel="stylesheet">
</head>

<body>

    <div class="container">
        <?php include 'header.php'; ?>

        <main role="main">

            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Certificate Details</h2>

                    </div>

                    <a href="add_certificate.php" class="btn btn-success pull-right">Add New Certificate</a>
                    <?php
                    // Include config file
                    require_once 'config.php';

                    $sql = "SELECT * FROM certificate WHERE student_id=" . $_SESSION['user_id'];

                    // Attempt select query execution
                    if ($_SESSION['is_admin']) {
                        $sql = "SELECT * FROM certificate ORDER BY student_id";
                    } 
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table class='table table-bordered table-striped'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Student Roll No.</th>";
                            echo "<th> Certificate Name</th>";
                            echo "<th> Certificate Description</th>";
                            echo "<th> Certificate Type</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['certificate_name'] . "</td>";
                                echo "<td>" . $row['certificate_desc'] . "</td>";
                                echo "<td>" . $row['certificate_type'] . "</td>";
                                echo "<td>";
                                echo "<a href='certificate.php?certificate_id=" . $row['certificate_id'] . "' title='View Record' data-toggle='tooltip'>View | </a>";
                                echo "<a href='certificate_edit.php?certificate_id=" . $row['certificate_id'] . "' title='Update Record' data-toggle='tooltip'> Edit |</a>";
                                echo "<a href='certificate_delete.php?certificate_id=" . $row['certificate_id'] . "' title='Delete Record' data-toggle='tooltip'> Delete </a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else {
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>

        </main>


        <?php include 'footer.php'; ?>


    </div> <!-- /container -->

</body>

</html>