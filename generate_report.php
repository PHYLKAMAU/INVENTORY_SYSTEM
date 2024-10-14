<?php
require_once('includes/load.php');
page_require_level(1);

if (isset($_POST['report-type'])) {
    $report_type = $_POST['report-type'];
    $report_data = generate_report($report_type);
} else {
    $session->msg("d", "No report type selected.");
    redirect('admin.php', false);
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span><?php echo ucfirst($report_type); ?> Report</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $data): ?>
                            <tr>
                                <td><?php echo (int)$data['id']; ?></td>
                                <td><?php echo remove_junk($data['product_name']); ?></td>
                                <td><?php echo (int)$data['quantity']; ?></td>
                                <td><?php echo remove_junk($data['categorie']); ?></td>
                                <td><?php echo remove_junk($data['supplier']); ?></td>
                                <td><?php echo remove_junk($data['date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
