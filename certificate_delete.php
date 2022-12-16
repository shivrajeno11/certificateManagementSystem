<?php

// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: index.php");
  exit;
}


// Process delete operation after confirmation
if(isset($_POST["certificate_id"]) && !empty($_POST["certificate_id"])){
// Include config file
  require_once 'config.php';

// Prepare a delete statement
  $sql = "DELETE FROM certificate WHERE certificate_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
// Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

// Set parameters
    $param_id = trim($_POST["certificate_id"]);

// Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
// Records deleted successfully. Redirect to landing page
      header("location: allcertificate.php");
      exit();
    } else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

// Close statement
  mysqli_stmt_close($stmt);

// Close connection
  mysqli_close($link);
} else{
// Check existence of id parameter
  if(empty(trim($_GET["certificate_id"]))){
// URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
  }
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

  <title> Delete Certificate</title>

  <!-- Bootstrap core CSS -->
  <link href="http://localhost/certificatemanagementsystem/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="http://localhost/certificatemanagementsystem/css/bootstrap.min.cssnarrow-jumbotron.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <?php include 'header.php';?>

    <main role="main">

      <div class="row">
        <div class="col-md-12">


          <div class="card">
            <div class="card-header">
              Delete certificate
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="card-body">
                <input type="hidden" name="certificate_id" value="<?php echo trim($_GET["certificate_id"]); ?>"/>
                <h5 class="card-title">Delete Certificate  </h5>
                <p class="card-text">Are you sure you want to delete this record?</p>
                <input type="submit" value="Yes" class="btn btn-danger">
                <a href="allcertificate.php" class="btn btn-default">No</a>
              </div>
            </form>
          </div>


        </div>
      </div> 

    </main>


    <?php include 'footer.php';?>


  </div> <!-- /container -->
</body>
</html>
