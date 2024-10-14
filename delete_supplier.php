<?php
  require_once('includes/load.php');
  page_require_level(1);

  $supplier = find_by_id('suppliers',(int)$_GET['id']);
  if(!$supplier){
    $session->msg("d","Missing supplier id.");
    redirect('view_suppliers.php');
  }

  $delete_id = delete_by_id('suppliers',(int)$supplier['id']);
  if($delete_id){
      $session->msg("s","Supplier deleted.");
      redirect('view_suppliers.php');
  } else {
      $session->msg("d","Supplier deletion failed.");
      redirect('view_suppliers.php');
  }
?>
