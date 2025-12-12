<!-- product price update
query database and display product information and (original) price
when form is submitted (checkboxes), open a text box beside the old prices for user to input new prices
when form is submitted (textboxes), update database with new prices
repeat from step 1 -->


<?php

    // query database and display product options and corresponding prices
    function query_product_price($product_abbreviated_name) {

        // connect to db named javajam
        @ $db = new mysqli('localhost', 'f32ee', 'f32ee', 'javajam');

        if (mysqli_connect_errno()) {
            echo 'Error: Could not connect to database.  Please try again later.';
            exit;
        }

        // prepare the query: need to obtain all product options and corresponding prices for the associated product
        // $product_abbreviated_name: 'jj', 'cal', 'ic'
        $query = "SELECT product_option_name, product_option_price FROM products, product_prices
                    WHERE products.product_abbreviated_name = CONCAT(?)
                    AND products.product_id = product_prices.product_id";

        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $product_abbreviated_name); // bind to ? in CONCAT^ inside query (no need to concat with single quotes as product_abbreviated_name already contains single quotes)
        $stmt->execute();

        // store results in $result
        $result = $stmt->get_result();  
        $num_results = $result->num_rows;
        
        // display all prices for a certain product
        for ($i=0; $i <$num_results; $i++) {
            $row = $result->fetch_assoc();

            // display retrieved option name and price
            echo "<strong><em> "; 
            echo htmlspecialchars(($row['product_option_name']));
            echo ": $";
            echo htmlspecialchars(($row['product_option_price']));
            echo "</em></strong>";
            echo "<br>";

            // check if checkbox is set: if set, additionally display a textbox for input of the new price
            // if products to update is set and the product's abbreviated name is one of the products to be updated (checkbox is selected), then echo...
            if (isset($_POST['products_to_update'])) { // first, check if any products need to have their price updated (else, do nothing)
                $products_to_update = $_POST['products_to_update']; // if any products need to be updated, use short variable name

                if ( in_array($product_abbreviated_name, $products_to_update) ) { // next, check if the current product of interest is one of the products that need to be updated

                    // for each product option, save a variable name and print a textbox
                    $product_option = strtolower( str_replace(' ', '_', $row['product_option_name']) ); // replace spaces in product option name with underscores, and set to lowercase 

                    // save the textbox's name and id as a variable (eg. jj_endless_cup_new_price)
                    $input_name_id = $product_abbreviated_name . "_" . $product_option . "_new_price";

                    // display retrieved option name and price (just print normally, don't use label since the formatting messes up)
                    echo "<strong><em> New Price for ";
                    echo htmlspecialchars(($row['product_option_name']));
                    echo ": ";
                    echo "</em></strong>";
                    
                    // print a textbox for entering the new price
                    // reference: <input type="text" name="jj_new_price"  id="jj_new_price" size=6 placeholder = "$0.00" style="text-align: center;">
                    echo ' <input type="text" name="';
                    echo $input_name_id;
                    echo '"  id="';
                    echo $input_name_id;
                    echo '" size=6 placeholder = "$0.00" style="text-align: center;"> ';
                                    
                }

            }
            
            echo "<br><br>";

        }

        $result->free();
        $db->close();

    }

    
    function update_product_prices() {

        // connect to db named javajam
        @ $db = new mysqli('localhost', 'f32ee', 'f32ee', 'javajam');

        if (mysqli_connect_errno()) {
            echo 'Error: Could not connect to database.  Please try again later.';
            exit;
        }

        // run a query to find the combinations of product abbreviations and product options, so it can be used to check each textbox
        $query = "SELECT product_prices.product_id, products.product_abbreviated_name, product_prices.product_option_name  FROM products, product_prices
                    WHERE products.product_id = product_prices.product_id";

        $stmt = $db->prepare($query);
        $stmt->execute();

        // store results in $result
        $result = $stmt->get_result();  
        $num_results = $result->num_rows;

        for ($i=0; $i <$num_results; $i++) {
            $row = $result->fetch_assoc();

            // for each product option, save a variable name and print a textbox
            $product_option = strtolower( str_replace(' ', '_', $row['product_option_name']) ); // replace spaces in product option name with underscores, and set to lowercase 

            // save the textbox's name and id as a variable (eg. jj_endless_cup_new_price)
            $input_name_id = $row['product_abbreviated_name'] . "_" . $product_option . "_new_price";

            // check if textbox is set, update price if isset and valid
            if( isset($_POST[$input_name_id] )) {
                $updated_price = floatval($_POST[$input_name_id]);

                if($updated_price > 0) {

                    /* write query to update prices */
                    $query = "UPDATE product_prices
                                SET product_prices.product_option_price = (?)
                                WHERE (product_prices.product_id = ?) AND 
                                (product_prices.product_option_name = ?)";

                    $stmt = $db->prepare($query);
                    $stmt->bind_param('dis', $updated_price, $row['product_id'], $row['product_option_name']); // refer to https://www.php.net/manual/en/mysqli-stmt.bind-param.php
                    $stmt->execute();

                }

            }

        }

        $result->free();
        $db->close();

    }

?>



<! doctype html>
<html lang = "en">
<head>
	<title>Product Price Update</title>
	<meta charset = "utf-8">

    <link rel = "stylesheet" href = "color.css">

</head>

<body>

<div class = "wrapper">

<header>
    
</header>

<div id = "box">

<div id = "leftcolumn">

<nav>
    <b>
        <a href = "prod_price_update.php"><span class = "currentpage">Product Price Update</span></a>
    </b>
</nav>

</div>

<div id = "rightcolumn">
    <h2>Click to update product prices:</h2>

<!-- the action $_SERVER['PHP_SELF'] is used to submit the form data to the same page (instead of to a different page) -->
<!-- the method htmlspecialchars() is used to prevent scripting attacks by converting special characters to html instead -->

<form id = "price_update_form" 
action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
method="post">


<?php 
update_product_prices(); // update product prices first
?>


<table>


    <tr>
        <td>
            <!-- for value: use product id or abbreviated name? chose to use abbreviated names but product id might be easier -->
            <input type="checkbox" name="products_to_update[]" value="jj">
        </td>

        <td>
            <strong>Just Java</strong><br>
            <img src = "images/creamy_cocoa.jpg" alt = "Just Java";>
        </td>

        <td>
            Rich and creamy coffee served with <span class = "dairy">milk</span>.
            <br><br>

            <!-- (echo) query_product_price('jj'); works the same way with or without echo -->
            <?php query_product_price('jj'); ?>

            <br>

        </td>
        
    </tr>

    <tr>

        <td>
            <input type="checkbox" name="products_to_update[]" value="cal">
        </td>

        <td>
            <strong>Cafe au Lait</strong><br>
            <img src = "images/hazelnut_latte.jpg" alt = "Cafe au Lait";>
        </td>

        <td>
            <span class = "nuts">Nuts</span>, 
            <span class = "dairy">milk</span>, 
            and coffee meld together in this delightful latte.
            <br><br>

            <?php query_product_price('cal'); ?>

        </td>

    </tr>

    <tr>

        <td>
            <input type="checkbox" name="products_to_update[]" value="ic">
        </td>

        <td>
            <strong>Iced Cappucino</strong><br>
            <img src = "images/honey_french_toast.jpg" alt = "Iced Cappucino";>
        </td>

        <td>
            Enjoy a refreshing shot of coffee. Chilled and brewed with berries and chocolate.
            <br>*Iced Cappucino may contain traces of <span class = "gluten">gluten</span>.
            <br><br>

            <?php query_product_price('ic'); ?>

        </td>

    </tr>


</table>

<!-- hidden submit button -->
<input type="submit" name="update_prices" value="Update Prices" class="checkout-btn" hidden>

</form>


</div>

</div>

<footer>
    <small><i>Copyright &copy; 2025 JavaJam Coffee House 
        <br> <a href = "mailto:zandra@tong.com">zandra@tong.com</a>
    </i></small>
</footer>

</div>

</body>
</html>