<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $c_categorie     = count_by_id('categories');
  $c_user          = count_by_id('users');
  $c_supplier      = count_by_id('suppliers');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
    <a href="users.php" style="color:black;">
        <div class="col-md-3">
           <div class="panel panel-box clearfix">
             <div class="panel-icon pull-left bg-secondary1">
              <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="panel-value pull-right">
              <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
              <p class="text-muted">Users</p>
            </div>
           </div>
        </div>
    </a>

    <a href="categorie.php" style="color:black;">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-th-large"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Departments</p>
        </div>
       </div>
    </div>
    </a>

    <a href="view_suppliers.php" style="color:black;">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-list-alt"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_supplier['total']; ?> </h2>
          <p class="text-muted">Suppliers</p>
        </div>
       </div>
    </div>
    </a>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recently Added Stock</span>
        </strong>
      </div>
      <div class="panel-body">
        <a href="view_stock_in.php" class="btn btn-primary">View All Stock In Records</a>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recently Issued Stock</span>
        </strong>
      </div>
      <div class="panel-body">
        <a href="view_items_taken.php" class="btn btn-primary">View All Stock Out Records</a>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
