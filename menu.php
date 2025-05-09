<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Recommendation Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
    <div class="nav-left">📚 BookRec</div>
    <div class="nav-right">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
        <a href="logout.php">Logout</a>
    </div>
</nav>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Welcome to BookRec, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <p>Explore hand-picked book recommendations just for you.</p>
        <div class="book-grid">
            <?php
            $books = [
                ["The Martian", "Sci-Fi", "Andy Weir"],
                ["To Kill a Mockingbird", "Classic", "Harper Lee"],
                ["1984", "Dystopian", "George Orwell"],
                ["Educated", "Memoir", "Tara Westover"],
                ["Dune", "Sci-Fi", "Frank Herbert"],
                ["The Hobbit", "Fantasy", "J.R.R. Tolkien"]
            ];
            shuffle($books);
            foreach (array_slice($books, 0, 4) as $book) {
                echo "<div class='book-card'>
                        <h2>{$book[0]}</h2>
                        <p><strong>Genre:</strong> {$book[1]}</p>
                        <p><strong>Author:</strong> {$book[2]}</p>
                    </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
