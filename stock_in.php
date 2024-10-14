<?php
  $page_title = 'Stock In';
  require_once('includes/load.php');
  page_require_level(1);

  if(isset($_POST['add_stock'])){
    $req_fields = array('supplier_id', 'product_name', 'quantity', 'lpo_number');
    validate_fields($req_fields);

    if(empty($errors)){
      $supplier_id  = (int)$_POST['supplier_id'];
      $product_name = remove_junk($db->escape($_POST['product_name']));
      $quantity     = (int)$_POST['quantity'];
      $lpo_number   = remove_junk($db->escape($_POST['lpo_number']));
      $date         = make_date();
      
      // Insert into stock-ins table
      $query  = "INSERT INTO stock_in (";
      $query .= "product_name, quantity, supplier_id, lpo_number, date_received";
      $query .= ") VALUES (";
      $query .= " '{$product_name}', '{$quantity}', '{$supplier_id}', '{$lpo_number}', '{$date}'";
      $query .= ")";
      
      if($db->query($query)){
        $session->msg('s',"Stock added ");
        redirect('stock_in.php', false);
      } else {
        $session->msg('d',' Sorry failed to add stock.');
        redirect('stock_in.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('stock_in.php',false);
    }
  }

  $all_suppliers = find_all('suppliers');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Stock In</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="stock_in.php" class="clearfix">
          <div class="form-group">
            <label for="supplier_id" class="control-label">Supplier Name</label>
            <select class="form-control" name="supplier_id">
              <?php foreach ($all_suppliers as $supplier): ?>
                <option value="<?php echo (int)$supplier['id'] ?>">
                  <?php echo $supplier['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" name="product_name" placeholder="Name of the product">
          </div>
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" name="quantity" placeholder="Quantity">
          </div>
          <div class="form-group">
            <label for="lpo_number" class="control-label">LPO Number</label>
            <input type="text" class="form-control" name="lpo_number" placeholder="LPO Number">
          </div>
          <button type="submit" name="add_stock" class="btn btn-success">Add Stock</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
