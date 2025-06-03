<?php include('connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="hotel-name">
    <p>MAISON VALENTE</p>
  </div>

<?php
// iniinitialize ukung valid ba ung mga pinaglalalagay mo
$usernameErr = $emailErr = $passwordErr = $cpasswordErr = "";
$username = $email = $password = $cpassword = "";
$emailExistAlert = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {//username dapat 5 or more
    if (empty($_POST["username"])) {
        $usernameErr = "Please enter a username";
    } else {
        $username = trim($_POST["username"]);
        if (strpos($username, ' ') !== false || strlen($username) < 5 || strlen($username) > 24) {
            $usernameErr = "Username must be 5-24 chars and no spaces";
        }
    }

    if (empty($_POST["email"])) {//bawal gumamit ng same email dapat iba na
        $emailErr = "Please enter your email address";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Please enter a valid email address.";
        }
    }

    if (empty($_POST["password"])) {//may requirements s password
        $passwordErr = "Please enter a password";
    } else {
        $password = trim($_POST["password"]);
        if (
            strlen($password) < 8 || strlen($password) > 20 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            $passwordErr = "Password must be 8-20 chars, with uppercase, lowercase, number & special char.";
        }
    }

    if (empty($_POST["cpassword"])) {
        $cpasswordErr = "Please confirm your password";
    } else {
        $cpassword = trim($_POST["cpassword"]);
        if ($cpassword !== $password) {
            $cpasswordErr = "Passwords do not match";
        }
    }

    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($cpasswordErr)) {
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {//e2 ung snasabi ko na bwal double n user ggmit sa isang email
            $emailExistAlert = "
            <div class='alert alert-warning alert-dismissible fade show text-center' role='alert'>
              Email is already in use. Please use a different one.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);//nakahash sya
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $emailExistAlert = "
                <div class='alert alert-danger alert-dismissible fade show text-center' role='alert'>
                  Error: " . $stmt->error . "
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            }

            $stmt->close();
        }

        $check_stmt->close();
    }

    $conn->close();
}
?>

<form class="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <div class="titlepage">CREATE ACCOUNT</div>

  <?php echo $emailExistAlert; ?>

  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>" />
    <span class="err"><?php echo $usernameErr; ?></span>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">Email Address</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" value="<?php echo htmlspecialchars($email); ?>" />
    <span class="err"><?php echo $emailErr; ?></span>
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" />
    <span class="err"><?php echo $passwordErr; ?></span>
  </div>

  <div class="mb-4">
    <label for="cpassword" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm password" />
    <span class="err"><?php echo $cpasswordErr; ?></span>
  </div>

  <div class="login-link mb-3 text-center">
    <p>Already have an account? <a href="login.php">LOGIN</a></p>
  </div>

  <div class="d-grid">
    <button type="submit" class="btn btn-custom">Register</button>
  </div>
</form>

<div class="botside">
  <p>&copy; 2025 Maison Valente Hotel. All rights reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>