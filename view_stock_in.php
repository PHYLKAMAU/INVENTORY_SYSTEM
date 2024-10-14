<?php
  $page_title = 'View Stock In';
  require_once('includes/load.php');
  page_require_level(1);

  // Retrieve all stock-in records
  $all_stock_in = find_all('stock_in');
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
          <span>All Stock In Records</span>
       </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th> Product Name </th>
              <th class="text-center"> Quantity </th>
              <th class="text-center"> Supplier Name </th>
              <th class="text-center"> LPO Number </th>
              <th class="text-center"> Date Received </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_stock_in as $stock_in): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td><?php echo remove_junk($stock_in['product_name']); ?></td>
              <td class="text-center"><?php echo (int)$stock_in['quantity']; ?></td>
              <td class="text-center"><?php echo remove_junk(find_by_id('suppliers', $stock_in['supplier_id'])['name']); ?></td>
              <td class="text-center"><?php echo remove_junk($stock_in['lpo_number']); ?></td>
              <td class="text-center"><?php echo read_date($stock_in['date_received']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
