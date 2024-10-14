<?php
  $page_title = 'Edit Supplier';
  require_once('includes/load.php');
  page_require_level(1);

  $supplier = find_by_id('suppliers',(int)$_GET['id']);
  if(!$supplier){
    $session->msg("d","Missing supplier id.");
    redirect('view_suppliers.php');
  }

  if(isset($_POST['update_supplier'])){
    $req_fields = array('name','email','contact','address');
    validate_fields($req_fields);

    if(empty($errors)){
      $id      = (int)$supplier['id'];
      $name    = remove_junk($db->escape($_POST['name']));
      $email   = remove_junk($db->escape($_POST['email']));
      $contact = remove_junk($db->escape($_POST['contact']));
      $address = remove_junk($db->escape($_POST['address']));

      $query  = "UPDATE suppliers SET ";
      $query .= "name='{$name}', email='{$email}', contact='{$contact}', address='{$address}' ";
      $query .= "WHERE id='{$id}'";

      if($db->query($query)){
        $session->msg('s',"Supplier updated ");
        redirect('edit_supplier.php?id='.$id, false);
      } else {
        $session->msg('d',' Sorry failed to update supplier.');
        redirect('edit_supplier.php?id='.$id, false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_supplier.php?id='.$id, false);
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
          <span>Edit Supplier</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_supplier.php?id=<?php echo (int)$supplier['id'];?>" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk($supplier['name']); ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($supplier['email']); ?>">
          </div>
          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" value="<?php echo remove_junk($supplier['contact']); ?>">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo remove_junk($supplier['address']); ?>">
          </div>
          <button type="submit" name="update_supplier" class="btn btn-primary">Update Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
