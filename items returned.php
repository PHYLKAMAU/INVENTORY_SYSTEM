<?php
$page_title = 'Items Returned';
require_once('includes/load.php');

// Ensure that only admins can view this page
page_require_level(1); 

// Fetch all returned products with stock out information
$sql = "SELECT rp.id, rp.quantity, rp.returned_by, rp.date_returned, rp.reason, 
               si.product_name, so.issued_to, so.department 
        FROM returned_products rp 
        JOIN stock_out so ON rp.stock_out_id = so.id 
        JOIN stock_in si ON so.stock_in_id = si.id";

$result = $db->query($sql);
$returned_products = $result->fetch_all(MYSQLI_ASSOC);

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Returned Items</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Returned Quantity</th>
              <th>Returned By</th>
              <th>Date Returned</th>
              <th>Reason</th>
              <th>Issued To</th>
              <th>Department</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($returned_products): ?>
              <?php foreach ($returned_products as $returned): ?>
                <tr>
                  <td><?php echo remove_junk($returned['product_name']); ?></td>
                  <td><?php echo (int)$returned['quantity']; ?></td>
                  <td><?php echo remove_junk($returned['returned_by']); ?></td>
                  <td><?php echo read_date($returned['date_returned']); ?></td>
                  <td><?php echo remove_junk($returned['reason']); ?></td>
                  <td><?php echo remove_junk($returned['issued_to']); ?></td>
                  <td><?php echo remove_junk($returned['department']); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">No returned items found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
