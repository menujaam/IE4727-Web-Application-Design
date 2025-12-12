<?php
// Create a new MySQLi connection to the 'javajam' database using local credentials.
$conn = new mysqli('localhost', 'f32ee', 'f32ee', 'javajam');

// If the connection failed, stop the script and show the error message.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------- Read POSTed form values and store them in shorter variables ----------
// Prices selected in the form and quantities entered for each product.
$jj_cost = $_POST['jj_cost'];
$jj_qty  = $_POST['jj_quantity'];
$cal_cost = $_POST['cal_cost'];
$cal_qty  = $_POST['cal_quantity'];
$ic_cost  = $_POST['ic_cost'];
$ic_qty   = $_POST['ic_quantity'];

// ---------- Helper function to insert a sale row into the 'sales' table ----------
/**
 * Inserts a single sale record if quantity > 0.
 * @param mysqli $conn     Active DB connection
 * @param string $product  Product name (e.g., "Just Java")
 * @param string $category Category/variant label (e.g., "Single")
 * @param int    $quantity Units sold
 * @param float  $price    Unit price
 */
function insert_sale($conn, $product, $category, $quantity, $price) {
    // Skip inserting if nothing was ordered
    if ($quantity > 0) {
        // Compute total amount for this line item
        $total = $quantity * $price;

        // Prepare a parameterized INSERT to avoid SQL injection
        $stmt = $conn->prepare(
            "INSERT INTO sales (product, category, quantity, total, sale_date)
             VALUES (?, ?, ?, ?, CURDATE())"
        );

        // Bind values to placeholders: s=string, s=string, i=int, d=double
        $stmt->bind_param("ssid", $product, $category, $quantity, $total);

        // Execute the prepared statement
        $stmt->execute();

    }
}

// ---------- Conditionally insert each drink based on submitted quantities ----------

// Just Java: map the posted price to a category label, then insert
if ($jj_qty > 0 && isset($_POST['jj_cost'])) {
    // If the posted cost equals 2, call it "Endless Cup"; otherwise "Other"
    $category = ($_POST['jj_cost'] == 2) ? "Endless Cup" : "Other";
    insert_sale($conn, "Just Java", $category, $jj_qty, $jj_cost);
}

// Café au Lait: use cost to decide "Single" vs "Double"
if ($cal_qty > 0 && isset($_POST['cal_cost'])) {
    $category = ($_POST['cal_cost'] == 2) ? "Single" : "Double";
    insert_sale($conn, "Cafe au Lait", $category, $cal_qty, $cal_cost);
}

// Iced Cappuccino: use cost to decide "Single" vs "Double"
if ($ic_qty > 0 && isset($_POST['ic_cost'])) {
    $category = ($_POST['ic_cost'] == 4.75) ? "Single" : "Double";
    insert_sale($conn, "Iced Cappuccino", $category, $ic_qty, $ic_cost);
}

// Redirect the user to a success page after processing the order
header("Location: ../order_success.html");
exit(); // Stop the script immediately after sending the header

// NOTE: The code below will not run because of exit() above.
// $conn->close(); // Unreachable here
?>
