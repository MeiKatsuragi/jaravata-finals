<?php
session_start();

$username = "";
$email = "";

$username_err = "";
$email_err = "";
$pass_err = "";
$Cpass_err = "";

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmed_pass = $_POST['Cpass'];

    if (empty($username)) {
        $username_err = "Username is required.";
        $error = true;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Email format invalid.";
        $error = true;
    }

    include "tools/db.php";
    $dbConnection = getDBConnection();

    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $email_err = "Email already used.";
        $error = true;
    }

    $statement->close();

    if (strlen($password) < 6) {
        $pass_err = "Password must be at least 6 characters.";
        $error = true;
    }

    if ($confirmed_pass != $password) {
        $Cpass_err = "Passwords do not match.";
        $error = true;
    }

    if (!$error) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $statement = $dbConnection->prepare(
            "INSERT INTO users (username, email, password, createdAt) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param('ssss', $username, $email, $password, $created_at);
        $statement->execute();
        $insert_id = $statement->insert_id;
        $statement->close();

        $_SESSION["id"] = $insert_id;
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;
        $_SESSION["created_at"] = $created_at;

        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - BookRec</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Register for BookRec 📚</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>">
            <p class="error"><?= $username_err ?></p>

            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            <p class="error"><?= $email_err ?></p>

            <input type="password" name="password" placeholder="Password">
            <p class="error"><?= $pass_err ?></p>

            <input type="password" name="Cpass" placeholder="Confirm Password">
            <p class="error"><?= $Cpass_err ?></p>

            <button type="submit">Register</button>
            <p class="switch">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
