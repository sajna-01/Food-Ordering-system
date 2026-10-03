<?php

$conn = mysqli_connect("localhost", "root", "", "food_ordering_system");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>