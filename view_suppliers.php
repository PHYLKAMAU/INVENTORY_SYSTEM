<?php
  $page_title = 'View Suppliers';
  require_once('includes/load.php');
  page_require_level(1);

  $all_suppliers = find_all('suppliers');
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
          <span>All Suppliers</span>
       </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>Address</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_suppliers as $supplier): ?>
              <tr>
                <td><?php echo remove_junk($supplier['name']); ?></td>
                <td><?php echo remove_junk($supplier['email']); ?></td>
                <td><?php echo remove_junk($supplier['contact']); ?></td>
                <td><?php echo remove_junk($supplier['address']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_supplier.php?id=<?php echo (int)$supplier['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_supplier.php?id=<?php echo (int)$supplier['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
