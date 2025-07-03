<?php
include("config/db.php");
include("partials/header.php");

// Dashboard Metrics
$customerCount = $conn->query("SELECT COUNT(*) AS total FROM customer")->fetch_assoc()['total'];
$itemCount = $conn->query("SELECT COUNT(*) AS total FROM item")->fetch_assoc()['total'];
$invoiceCount = $conn->query("SELECT COUNT(DISTINCT invoice_no) AS total FROM invoice")->fetch_assoc()['total'];
$latestInvoice = $conn->query("SELECT MAX(date) AS latest FROM invoice")->fetch_assoc()['latest'];
?>

<!-- Include AOS CSS for animation -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  .dashboard-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
  }

  .dashboard-section h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
  }
</style>

<div class="container py-4">
  <h2 class="mb-4 fw-bold text-center"><i class="bi bi-speedometer2 me-2"></i>ERP System Dashboard</h2>

  <div class="row g-4 mb-5">
    <!-- Customers -->
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
      <div class="card text-white bg-success shadow-sm h-100 dashboard-card">
        <div class="card-body text-center">
          <i class="bi bi-person-fill display-5"></i>
          <h5 class="card-title mt-2">Customers</h5>
          <p class="display-6 fw-bold"><?= $customerCount ?></p>
          <a href="customer/view.php" class="btn btn-light btn-sm">Manage</a>
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
      <div class="card text-white bg-primary shadow-sm h-100 dashboard-card">
        <div class="card-body text-center">
          <i class="bi bi-box-seam display-5"></i>
          <h5 class="card-title mt-2">Inventory</h5>
          <p class="display-6 fw-bold"><?= $itemCount ?></p>
          <a href="item/view.php" class="btn btn-light btn-sm">Manage</a>
        </div>
      </div>
    </div>

    <!-- Invoices -->
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
      <div class="card text-white bg-warning shadow-sm h-100 dashboard-card">
        <div class="card-body text-center">
          <i class="bi bi-receipt-cutoff display-5"></i>
          <h5 class="card-title mt-2">Invoices</h5>
          <p class="display-6 fw-bold"><?= $invoiceCount ?></p>
          <a href="reports/invoice_report.php" class="btn btn-light btn-sm">View</a>
        </div>
      </div>
    </div>

    <!-- Latest Invoice -->
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
      <div class="card text-white bg-dark shadow-sm h-100 dashboard-card">
        <div class="card-body text-center">
          <i class="bi bi-calendar-event display-5"></i>
          <h5 class="card-title mt-2">Latest Invoice</h5>
          <p class="display-6 fw-bold"><?= $latestInvoice ?: '—' ?></p>
          <a href="reports/invoice_report.php" class="btn btn-light btn-sm">Go</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Management -->
  <div class="dashboard-section" data-aos="fade-up">
    <h4><i class="bi bi-gear-wide-connected me-2"></i>Management</h4>
    <div class="d-flex flex-wrap gap-3">
      <a href="customer/view.php" class="btn btn-outline-success">
        <i class="bi bi-people-fill me-1"></i> Customers
      </a>
      <a href="item/view.php" class="btn btn-outline-primary">
        <i class="bi bi-box2-fill me-1"></i> Items
      </a>
    </div>
  </div>

  <!-- Reports -->
  <div class="dashboard-section" data-aos="fade-up">
    <h4><i class="bi bi-graph-up me-2"></i>Reports</h4>
    <div class="d-flex flex-wrap gap-3">
      <a href="reports/invoice_report.php" class="btn btn-outline-dark">
        <i class="bi bi-file-earmark-text me-1"></i> Invoice Report
      </a>
      <a href="reports/invoice_item_report.php" class="btn btn-outline-dark">
        <i class="bi bi-bag-check me-1"></i> Invoice Item Report
      </a>
      <a href="reports/item_report.php" class="btn btn-outline-dark">
        <i class="bi bi-box2 me-1"></i> Item Report
      </a>
    </div>
  </div>

  <!-- Analytics -->
  <div class="dashboard-section" data-aos="fade-up">
    <h4><i class="bi bi-bar-chart-line me-2"></i>Analytics</h4>
    <div class="d-flex flex-wrap gap-3">
      <a href="reports/dashboard_charts.php" class="btn btn-outline-dark">
        <i class="bi bi-pie-chart me-1"></i> Dashboard Charts
      </a>
    </div>
  </div>

  <!-- Advanced -->
  <div class="dashboard-section" data-aos="fade-up">
    <h4><i class="bi bi-lightning-charge me-2"></i>Advanced Analytics</h4>
    <div class="d-flex flex-wrap gap-3">
      <a href="reports/advanced_dashboard.php" class="btn btn-outline-dark">
        <i class="bi bi-bar-chart-steps me-1"></i> Advanced Dashboard
      </a>
    </div>
  </div>
</div>

<!-- Include AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
  });
</script>

<?php include("partials/footer.php"); ?>
