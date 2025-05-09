<?php
session_start();

$username = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and/or Password is required.";
    } else {
        include "tools/db.php";
        $dbConnection = getDBConnection();

        $statement = $dbConnection->prepare(
            "SELECT id, email, password, createdAt FROM users WHERE username = ?"
        );
        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->bind_result($id, $email, $stored_password, $created_at);

        if ($statement->fetch()) {
            if (password_verify($password, $stored_password)) {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION["created_at"] = $created_at;

                header("location: ./home.php");
                exit;
            }
        }

        $statement->close();
        $error = "Username or Password Invalid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BookRec</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login to BookRec 📚</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>">
            <input type="password" name="password" placeholder="Password">
            <p class="error"><?= $error ?></p>
            <button type="submit">Login</button>
            <p class="switch">Don't have an account? <a href="index.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
