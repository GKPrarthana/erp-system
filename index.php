<?php
include("config/db.php");
include("partials/header.php");

// Dashboard Metrics
$customerCount = $conn->query("SELECT COUNT(*) AS total FROM customer")->fetch_assoc()['total'];
$itemCount = $conn->query("SELECT COUNT(*) AS total FROM item")->fetch_assoc()['total'];
$invoiceCount = $conn->query("SELECT COUNT(DISTINCT invoice_no) AS total FROM invoice")->fetch_assoc()['total'];
$latestInvoice = $conn->query("SELECT MAX(date) AS latest FROM invoice")->fetch_assoc()['latest'];
?>

<h2 class="mb-4">ERP System Dashboard</h2>

<div class="row g-4 mb-5">
  <!-- Customers Card -->
  <div class="col-md-3">
    <div class="card text-white bg-success shadow-sm h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Customers</h5>
        <p class="display-6"><?= $customerCount ?></p>
        <a href="customer/view.php" class="btn btn-light btn-sm">Manage Customers</a>
      </div>
    </div>
  </div>

  <!-- Items Card -->
  <div class="col-md-3">
    <div class="card text-white bg-primary shadow-sm h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Items</h5>
        <p class="display-6"><?= $itemCount ?></p>
        <a href="item/view.php" class="btn btn-light btn-sm">Manage Items</a>
      </div>
    </div>
  </div>

  <!-- Invoices Card -->
  <div class="col-md-3">
    <div class="card text-white bg-warning shadow-sm h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Invoices</h5>
        <p class="display-6"><?= $invoiceCount ?></p>
        <a href="reports/invoice_report.php" class="btn btn-light btn-sm">View Invoices</a>
      </div>
    </div>
  </div>

  <!-- Latest Invoice Date -->
  <div class="col-md-3">
    <div class="card text-white bg-dark shadow-sm h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Latest Invoice</h5>
        <p class="display-6"><?= $latestInvoice ? $latestInvoice : '—' ?></p>
        <a href="reports/invoice_report.php" class="btn btn-light btn-sm">Go to Reports</a>
      </div>
    </div>
  </div>
</div>

<hr>

<!-- Management Section -->
<h4 class="mb-3">Management</h4>
<div class="d-flex flex-wrap gap-3 mb-4">
  <a href="customer/view.php" class="btn btn-outline-success">Customer Management</a>
  <a href="item/view.php" class="btn btn-outline-primary">Item Management</a>
</div>

<!-- Reports Section -->
<h4 class="mb-3">Reports</h4>
<div class="d-flex flex-wrap gap-3">
  <a href="reports/invoice_report.php" class="btn btn-outline-dark">Invoice Report</a>
  <a href="reports/invoice_item_report.php" class="btn btn-outline-dark">Invoice Item Report</a>
  <a href="reports/item_report.php" class="btn btn-outline-dark">Item Report</a>
</div>

<!-- Analytics Section -->
<h4 class="mb-3">Analytics</h4>
<div class="d-flex flex-wrap gap-3">
  <a href="reports/dashboard_charts.php" class="btn btn-outline-dark">Dashboard Charts</a>
</div>

<!-- Advanced Analytics Section -->
<h4 class="mb-3">Advanced Analytics</h4>
<div class="d-flex flex-wrap gap-3">
  <a href="reports/advanced_dashboard.php" class="btn btn-outline-dark">Advanced Dashboard</a>
</div>

<?php include("partials/footer.php"); ?>
