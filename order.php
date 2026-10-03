
<?php

session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['cart'])) {
    die("Cart data not received.");
}

$cart = json_decode($_POST['cart'], true);

if (empty($cart)) {
    die("Cart is empty.");
}

$total_amount = 0;

foreach ($cart as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

$sql = "INSERT INTO orders 
        (user_id, order_date, total_amount, status)
        VALUES 
        ('$user_id', NOW(), '$total_amount', 'Pending')";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Order Error: " . mysqli_error($conn));
}

$order_id = mysqli_insert_id($conn);

foreach ($cart as $item) {

    $food_name = mysqli_real_escape_string($conn, $item['name']);
    $quantity = $item['quantity'];
    $price = $item['price'];
    $subtotal = $price * $quantity;

    $food_query = "SELECT food_id 
                   FROM food_items 
                   WHERE food_name='$food_name'
                   LIMIT 1";

    $food_result = mysqli_query($conn, $food_query);

    if (!$food_result) {
        die("Food Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($food_result) > 0) {

        $food = mysqli_fetch_assoc($food_result);
        $food_id = $food['food_id'];

        $item_query = "INSERT INTO order_items
                       (order_id, food_id, quantity, subtotal)
                       VALUES
                       ('$order_id', '$food_id', '$quantity', '$subtotal')";

        if (!mysqli_query($conn, $item_query)) {
            die("Order Item Error: " . mysqli_error($conn));
        }
    }
}

echo "<script>
        alert('Order placed successfully! 🎉');
        window.location.href='CSS/menu.html';
      </script>";

?>

