<?php
  $page_title = 'Add Supplier';
  require_once('includes/load.php');
  page_require_level(1);

  if(isset($_POST['add_supplier'])){
    $req_fields = array('name','email','contact','address');
    validate_fields($req_fields);

    if(empty($errors)){
      $name    = remove_junk($db->escape($_POST['name']));
      $email   = remove_junk($db->escape($_POST['email']));
      $contact = remove_junk($db->escape($_POST['contact']));
      $address = remove_junk($db->escape($_POST['address']));
      
      $query  = "INSERT INTO suppliers (";
      $query .= " name, email, contact, address";
      $query .= ") VALUES (";
      $query .= " '{$name}', '{$email}', '{$contact}', '{$address}'";
      $query .= ")";
      
      if($db->query($query)){
        $session->msg('s',"Supplier added ");
        redirect('add_supplier.php', false);
      } else {
        $session->msg('d',' Sorry failed to add supplier.');
        redirect('add_supplier.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_supplier.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-9">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Supplier</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_supplier.php" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Supplier Name">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Supplier Email">
          </div>
          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" placeholder="Supplier Contact">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" name="address" placeholder="Supplier Address">
          </div>
          <button type="submit" name="add_supplier" class="btn btn-primary">Add Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
