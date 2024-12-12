<?php
session_start();
include('db_test.php'); // Include database connection

if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);
    $searchQuery = $conn->real_escape_string($searchQuery); // Sanitize input

    // Search for products that match the search query
    $query = "SELECT * FROM products WHERE 'name' LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<h2>Search Results for '$searchQuery'</h2>";
        echo "<div class='product-list'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product' style='border:1px solid #ddd; padding:15px; margin:10px;'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";

            // Add to Cart button
            echo "<form action='cart_action.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' class='btn btn-success'>Add to Cart</button>";
            echo "</form>";

            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<h2>No results found for '$searchQuery'</h2>";
    }
} else {
    echo "<h2>Please enter a search term.</h2>";
}
?>
