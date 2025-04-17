<?php
session_start();
include('server/connection.php');

// Agar user login hai toh direct account page par redirect karo
if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['register'])) {
    //  **Sanitize Inputs**
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    //  **Validation Start** 

    // Sabhi fields empty toh nahi hain?
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        header('location: register.php?error=Please fill in all fields');
        exit;
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: register.php?error=Invalid email format');
        exit;
    }

    // Password length check (min 6 characters)
    if (strlen($password) < 6) {
        header('location: register.php?error=Password must be at least 6 characters');
        exit;
    }

    // Confirm password check
    if ($password !== $confirmPassword) {
        header('location: register.php?error=Passwords do not match');
        exit;
    }

    //  **Check if email is already registered**
    $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->fetch();
    $stmt1->close();

    if ($num_rows > 0) {
        header('location: register.php?error=User with this email already exists');
        exit;
    }

    //  **Secure Password Hashing**
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //  **Insert New User**
    $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        $_SESSION['logged_in'] = true;
        header('location: account.php?register_success=You registered successfully');
        exit;
    } else {
        header('location: register.php?error=Could not create an account at the moment');
        exit;
    }
}
?>



<?php include("layout/header.php");?>

    <!-- register -->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="register-form" method="POST" action="register.php">
                <p style="color:red;"><?php if (isset($_GET['error'])) {
                                            echo $_GET['error'];
                                        } ?></p>
                <div class="from-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required />
                </div>
                <div class="from-group">
                    <label>Email</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required />
                </div>
                <div class="from-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="password" required />
                </div>
                <div class="from-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="confirm-password" required />
                </div>

                <div class="from-group">
                    <input type="Submit" class="btn" id="register-btn" name="register" value="Register" />
                </div>

                <div class="from-group">
                    <a id="register-url" href="login.php" class="btn">Do you have an account? Login</a>
                </div>
            </form>
        </div>
    </section>





    <?php include("layout/footer.php");?>