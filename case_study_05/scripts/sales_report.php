<! DOCTYPE html>
<html lang="en">
<head>
    <title>Total Sales Report</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../color.css">
</head>

<body>
<div class="wrapper">
    <header>
        <!--
        <a href="index.html">
            <img src="images/javajamlogo.gif" width="auto" height="100%" alt="JavaJam Coffee House">
        </a>
        -->
    </header>

    <div id="box">
        <div id="leftcolumn">
            <nav>
                <b>
                    <a href="../admin.html">Daily Sales Report</a><br>
                </b>
            </nav>
        </div>

        <div id="rightcolumn">
            <?php
            // Check if page was submitted using POST and a report type chosen
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_type'])) {

                // Connect to the 'javajam' MySQL database
                $conn = new mysqli('localhost', 'f32ee', 'f32ee', 'javajam');

                if ($conn->connect_error) {
                    die("<p style='color:red;text-align:center;'>Connection failed: " . $conn->connect_error . "</p>");
                }

                // Store selected report types
                $selected_reports = $_POST['report_type'];

                // --------- SALES BY PRODUCT ---------
                if (in_array('product', $selected_reports)) {
                    echo "<h2>Sales by Product</h2>";

                    $sql1 = "SELECT product, SUM(quantity) AS total_qty, SUM(total) AS total_sales
                             FROM sales 
                             WHERE sale_date = CURDATE()
                             GROUP BY product";

                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        echo "<table><tr><th>Product</th><th>Quantity</th><th>Total Sales ($)</th></tr>";
                        while ($row = $result1->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['product']}</td>
                                    <td>{$row['total_qty']}</td>
                                    <td>" . number_format($row['total_sales'], 2) . "</td>
                                  </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p style='text-align:center;'>No product sales data for today.</p>";
                    }
                }

                // --------- SALES BY CATEGORY ---------
                if (in_array('category', $selected_reports)) {
                    echo "<h2>Sales by Category</h2>";

                    $sql2 = "SELECT category, SUM(quantity) AS total_qty, SUM(total) AS total_sales
                             FROM sales 
                             WHERE sale_date = CURDATE()
                             GROUP BY category";

                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        echo "<table><tr><th>Category</th><th>Quantity</th><th>Total Sales ($)</th></tr>";
                        while ($row = $result2->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['category']}</td>
                                    <td>{$row['total_qty']}</td>
                                    <td>" . number_format($row['total_sales'], 2) . "</td>
                                  </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p style='text-align:center;'>No sales data for today.</p>";
                    }
                }

                // --------- BEST SELLING PRODUCT ---------
                if (in_array('product', $selected_reports) || in_array('category', $selected_reports)) {

                    // 1) Find the best-selling product(s) by revenue
                    $sqlTopProducts = "
                        SELECT product, SUM(total) AS total_sales
                        FROM sales
                        WHERE sale_date = CURDATE()
                        GROUP BY product
                        ORDER BY total_sales DESC
                    ";
                    $resTopProducts = $conn->query($sqlTopProducts);

                    if ($resTopProducts && $resTopProducts->num_rows > 0) {
                        $bestProducts = [];
                        $maxSales = null;

                        while ($row = $resTopProducts->fetch_assoc()) {
                            $sales = (float)$row['total_sales'];

                            if ($maxSales === null) $maxSales = $sales;

                            if ($sales == $maxSales) {
                                $bestProducts[] = [
                                    'product'     => $row['product'],
                                    'total_sales' => $sales
                                ];
                            } else {
                                break;
                            }
                        }

                        // 2) For each best-selling product, find its top category by REVENUE
                        echo '<h3>Popular option of best-selling product by revenue :</h3>';
                        echo '<ul>';

                        $stmt = $conn->prepare("
                            SELECT 
                                category, 
                                SUM(quantity) AS qty, 
                                SUM(total)    AS cat_sales
                            FROM sales
                            WHERE sale_date = CURDATE() AND product = ?
                            GROUP BY category
                            ORDER BY cat_sales DESC, qty DESC, category ASC
                            LIMIT 1
                        ");

                        foreach ($bestProducts as $bp) {
                            $pname = $bp['product'];
                            $stmt->bind_param('s', $pname);
                            $stmt->execute();
                            $resCats = $stmt->get_result();

                            if ($resCats && ($row = $resCats->fetch_assoc())) {
                                echo '<p>'
                                   . '<b>' . htmlspecialchars($pname) . '</b> '
                                   . '- Popular Option: <span class="tag"><b>'
                                   . htmlspecialchars($row['category'])
                                   . '</b></span> '
                                   . '(Quantity: ' . (int)$row['qty'] . ', Revenue: $'
                                   . number_format((float)$row['cat_sales'], 2)
                                   . ')</p>';
                            } else {
                                echo '<li><b>' . htmlspecialchars($pname) . '</b> — no sales data.</li>';
                            }
                        }

                        $stmt->close();
                        echo '</ul>';
                    }
                }

                // Close connection
                $conn->close();
            }
            ?>
        </div>
    </div>

    <footer>
        <small><i>
            Copyright &copy; 2025 JavaJam Coffee House<br>
            <a href="mailto:zandra@tong.com">zandra@tong.com</a>
        </i></small>
    </footer>
</div>
</body>
</html>


