<?php
session_start();
include("server/connection.php");

// Agar user login hai toh account page par redirect karo
if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['login_btn'])) {
    // **Sanitize Inputs**
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);

    //  **Validation Start** 

    // Email aur password empty toh nahi hain?
    if (empty($email) || empty($password)) {
        header('location: login.php?error=Please fill in all fields');
        exit;
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: login.php?error=Invalid email format');
        exit;
    }

    // **Check if user exists**
    $stmt = $conn->prepare('SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password);
    $stmt->store_result();

    if ($stmt->num_rows() == 1) {
        $stmt->fetch();

        // **Verify Password Securely**
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
            $_SESSION['logged_in'] = true;

            header('location: account.php?login_success=Logged in successfully');
            exit;
        } else {
            header('location: login.php?error=Incorrect password');
            exit;
        }
    } else {
        header('location: login.php?error=User does not exist');
        exit;
    }
}
?>



<?php include("layout/header.php");?>

    <!-- login -->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Login</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <p style="color:red;" class="text-center"><?php if (isset($_GET['error'])) {
                                                            echo $_GET['error'];
                                                        } ?></p>
            <form id="Login-form" method="POST" action="login.php">
                <div class="from-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required />
                </div>
                <div class="from-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="login-password" name="password" placeholder="password" required />
                </div>

                <div class="from-group">
                    <input type="Submit" class="btn" id="login-btn" name="login_btn" value="Login" />
                </div>

                <div class="from-group">
                    <a id="register-url" href="register.php" class="btn">Don't have account? Register</a>
                </div>
            </form>
        </div>
    </section>





    <?php include("layout/footer.php");?>