<?php
session_start();
include('connect.php');

// dito lalabas yung error message kung may mali sa login
$emailErr = $passwordErr = $loginErr = "";
$email = $password = "";

// para sa validation sa email field
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Please enter your email address";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // para sa validation sa password field
    if (empty($_POST["password"])) {
        $passwordErr = "Please enter your password";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // I-verify kung tama ang password, gamit ang hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['userid'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: admin.php");// kung si admin gagamit, eto yung mag-ri-redirect sakanya sa admin panel
                    exit();
                } else {
                    header("Location: index.php"); // kung regular user, sa homepage lang
                    exit();
                }
            } else {
                $loginErr = "Incorrect password";
            }
        } else {
            $loginErr = "No account found with that email";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="hotel-name">
        <p>MAISON VALENTE</p>
    </div>

    <form class="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="titlepage">
            <p>LOGIN</p>
        </div>

        <!-- Kung may error sa login credentials, ipapakita ito bilang alert -->
        <?php if ($loginErr): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($loginErr) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input
                type="email"
                class="form-control <?= $emailErr ? 'is-invalid' : '' ?>"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="<?= htmlspecialchars($email) ?>"
            />
            <div class="invalid-feedback"><?= $emailErr ?></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                class="form-control <?= $passwordErr ? 'is-invalid' : '' ?>"
                id="password"
                name="password"
                placeholder="Enter your password"
            />
            <div class="invalid-feedback"><?= $passwordErr ?></div>
        </div>

        <div class="login-link text-center mb-3">
            <p>Don't have an Account? <a href="register.php">REGISTER</a></p>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn" style="background-color:  #7b5c38; color: white;">Login</button>
        </div>
    </form>

    <div class="botside">
        <p>&copy; 2025 Maison Valente Hotel. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>