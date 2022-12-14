<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = $useremail = $user_id = "";
$username_err = $password_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if username is empty
  if (empty(trim($_POST["username"]))) {
    $username_err = 'Please enter username.';
  } else {
    $username = trim($_POST["username"]);
  }

  // Check if password is empty
  if (empty(trim($_POST['password']))) {
    $password_err = 'Please enter your password.';
  } else {
    $password = trim($_POST['password']);
  }

  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT admin_id, admin_name, admin_password FROM staff WHERE admin_name = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $username;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind result variables
          mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {
              /* Password is correct, so start a new session and
              save the username to the session */



              session_start();
              $_SESSION['user_id'] = $user_id;
              $_SESSION['username'] = $username;
              $_SESSION['password'] = $password;
              $_SESSION['is_admin'] = true;

              header("location: dashboard.php");
            } else {
              // Display an error message if password is not valid
              $password_err = 'The password you entered was not valid.';
            }
          }
        } else {
          // Display an error message if username doesn't exist
          $username_err = 'No account found with that username.';
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Close connection
  mysqli_close($link);
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, sstaffink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <link rel="icon" href="http://localhost/CertificateManagementSystem/img/favicon.ico">

  <title> Admin Login </title>

  <!-- Bootstrap core CSS -->
  <link href="http://localhost/CertificateManagementSystem/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="http://localhost/CertificateManagementSystem/css/bootstrap.min.cssnarrow-jumbotron.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <?php include 'header.php'; ?>

    <main role="main">
      <div class="alert alert-primary" role="alert">
        Admin Login
      </div>


      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <label>Username:<sup>*</sup></label>
          <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
          <span class="help-block">
            <?php echo $username_err; ?>
          </span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <label>Password:<sup>*</sup></label>
          <input type="password" name="password" class="form-control">
          <span class="help-block">
            <?php echo $password_err; ?>
          </span>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <p>Don't have an account? <a href="admin_reg.php">Sign up now</a>.</p>

      </form>

    </main>


    <?php include 'footer.php'; ?>


  </div> <!-- /container -->
</body>

</html>