<?php
$page_title = 'Return Product';
require_once('includes/load.php');
page_require_level(3); // User level

// Fetch available items that can be returned
$query = "SELECT so.id, si.product_name, so.quantity
          FROM stock_out so
          JOIN stock_in si ON so.stock_in_id = si.id
          WHERE so.quantity > 0";

$items = $db->query($query);

if (isset($_POST['return_product'])) {
    $stock_out_id = $db->escape((int)$_POST['stock_out_id']);
    $quantity = $db->escape((int)$_POST['quantity']);
    $returned_by = $db->escape($_POST['returned_by']);
    $reason = $db->escape($_POST['reason']);

    // Fetch the original quantity from stock_out
    $result = $db->query("SELECT quantity FROM stock_out WHERE id = '{$stock_out_id}'");
    $original_quantity = $db->fetch_assoc($result)['quantity'];

    if ($quantity <= 0 || $quantity > $original_quantity) {
        $session->msg("d", "Invalid quantity entered.");
        redirect('return_products.php', false);
    } else {
        $date_returned = date("Y-m-d H:i:s");
        $sql = "INSERT INTO returned_products (stock_out_id, quantity, returned_by, date_returned, reason)
                VALUES ('{$stock_out_id}', '{$quantity}', '{$returned_by}', '{$date_returned}', '{$reason}')";
        if ($db->query($sql)) {
            // Update the stock_out table to reflect the returned quantity
            $new_quantity = $original_quantity - $quantity;
            $db->query("UPDATE stock_out SET quantity = '{$new_quantity}' WHERE id = '{$stock_out_id}'");

            $session->msg("s", "Product returned successfully.");
            redirect('return_products.php', false);
        } else {
            $session->msg("d", "Sorry, product return failed.");
            redirect('return_products.php', false);
        }
    }
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Return Product</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="return_products.php">
                    <div class="form-group">
                        <label for="stock_out_id">Select Product</label>
                        <select class="form-control" name="stock_out_id" required>
                            <option value="">Select a product</option>
                            <?php while ($item = $db->fetch_assoc($items)): ?>
                                <option value="<?php echo (int)$item['id']; ?>">
                                    <?php echo remove_junk($item['product_name']); ?> (Quantity: <?php echo (int)$item['quantity']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" placeholder="Enter quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="returned_by">Returned By</label>
                        <input type="text" class="form-control" name="returned_by" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Return</label>
                        <textarea class="form-control" name="reason" placeholder="Enter reason for return" required></textarea>
                    </div>
                    <button type="submit" name="return_product" class="btn btn-primary">Return Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
