<?php
$page_title = 'Issue Item';
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(1);

$all_categories = find_all('categories');
$all_stock_in = find_all('stock_in');

if(isset($_POST['add_issue'])){
  $req_fields = array('stock_in_id', 'quantity', 'department', 'issued_to');
  validate_fields($req_fields);
  if(empty($errors)){
    $stock_in_id = $db->escape((int)$_POST['stock_in_id']);
    $quantity = $db->escape((int)$_POST['quantity']);
    $department = $db->escape($_POST['department']);
    $issued_to = $db->escape($_POST['issued_to']);
    $date = make_date();

    // Check available stock
    $stock_in = find_by_id('stock_in', $stock_in_id);
    if($stock_in['quantity'] < $quantity){
      $session->msg('d', 'Insufficient stock for the requested product.');
      redirect('issue_items.php', false);
    } else {
      // Insert into stock_out
      $query  = "INSERT INTO stock_out (";
      $query .=" stock_in_id,quantity,department,issued_to,date";
      $query .=") VALUES (";
      $query .=" '{$stock_in_id}', '{$quantity}', '{$department}', '{$issued_to}', '{$date}'";
      $query .=")";
      
      if($db->query($query)){
        // Decrease the quantity in stock_ins
        $update_qty = "UPDATE stock_in SET quantity = quantity - '{$quantity}' WHERE id = '{$stock_in_id}'";
        $db->query($update_qty);

        $session->msg('s',"Item issued successfully.");
        redirect('issue_items.php', false);
      } else {
        $session->msg('d',' Sorry failed to issue item.');
        redirect('issue_items.php', false);
      }
    }
  } else{
    $session->msg("d", $errors);
    redirect('issue_items.php',false);
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
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Issue Item</span>
       </strong>
      </div>
      <div class="panel-body">
       <form method="post" action="issue_items.php" class="clearfix">
         <div class="form-group">
           <label for="stock_in_id">Product</label>
           <select class="form-control" name="stock_in_id">
             <option value="">Select Product</option>
           <?php  foreach ($all_stock_in as $stock_in): ?>
             <?php if($stock_in['quantity'] > 0): ?>
               <option value="<?php echo (int)$stock_in['id'] ?>">
                 <?php echo remove_junk($stock_in['product_name']) ?> (Available: <?php echo (int)$stock_in['quantity']; ?>)</option>
             <?php endif; ?>
           <?php endforeach; ?>
           </select>
         </div>
         <div class="form-group">
           <label for="quantity">Quantity</label>
           <input type="number" class="form-control" name="quantity" placeholder="Quantity">
         </div>
         <div class="form-group">
           <label for="department">Department</label>
           <select class="form-control" name="department">
             <option value="">Select Department</option>
           <?php  foreach ($all_categories as $cat): ?>
             <option value="<?php echo remove_junk($cat['name']) ?>">
               <?php echo remove_junk($cat['name']) ?></option>
           <?php endforeach; ?>
           </select>
         </div>
         <div class="form-group">
           <label for="issued_to">Issued To</label>
           <input type="text" class="form-control" name="issued_to" placeholder="Issued To">
         </div>
         <div class="form-group clearfix">
           <button type="submit" name="add_issue" class="btn btn-primary">Issue Item</button>
         </div>
       </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
