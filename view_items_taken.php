<?php
$page_title = 'View Items Taken';
require_once('includes/load.php');
page_require_level(1);

$all_items_taken = find_all('stock_out');
?>
<?php include_once('layouts/header.php'); ?>

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
          <span>Items Taken</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Issued To</th>
              <th>Department</th>
              <th>Date Issued</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_items_taken as $item): ?>
              <tr>
                <td><?php echo remove_junk(find_by_id('stock_in', $item['stock_in_id'])['product_name']); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td><?php echo remove_junk($item['issued_to']); ?></td>
                <td><?php echo remove_junk($item['department']); ?></td>
                <td><?php echo read_date($item['date']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
