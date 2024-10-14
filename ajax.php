<?php
require_once('includes/load.php');

// Function to handle JSON responses
function send_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Check if user is logged in
if (!$session->isUserLoggedIn(true)) {
    send_response(['error' => 'User not logged in']);
}

// Handle auto suggestion for stock_in items
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
    $product_name = $db->escape($_POST['product_name']);
    $products = find_product_by_title($product_name);

    if ($products) {
        $html = '';
        foreach ($products as $product) {
            $html .= "<li class=\"list-group-item\">{$product['name']}</li>";
        }
        send_response(['html' => $html]);
    } else {
        send_response(['html' => '<li class="list-group-item">Not found</li>']);
    }
}

// Find all stock_in info by product name
if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
    $product_name = $db->escape($_POST['p_name']);
    $product_info = find_all_product_info_by_title($product_name);

    if ($product_info) {
        $html = '';
        foreach ($product_info as $info) {
            $html .= "<tr>";
            $html .= "<td>{$info['name']}</td>";
            $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$info['id']}\">";
            $html .= "<td><input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$info['sale_price']}\"></td>";
            $html .= "<td><input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\"></td>";
            $html .= "<td><input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$info['sale_price']}\"></td>";
            $html .= "<td><input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\"></td>";
            $html .= "<td><button type=\"submit\" name=\"add_stock_in\" class=\"btn btn-primary\">Add Stock In</button></td>";
            $html .= "</tr>";
        }
        send_response(['html' => $html]);
    } else {
        send_response(['error' => 'Product not found in database']);
    }
}

// If no valid action is specified
send_response(['error' => 'Invalid request']);
?>
