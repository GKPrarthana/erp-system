<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ERP System</title>

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body class="container mt-4">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
      ERP System
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">
            Home
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Management
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="customer/view.php">
              Customers
            </a></li>
            <li><a class="dropdown-item" href="item/view.php">
              Items
            </a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Reports
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="reports/invoice_report.php">
              Invoice Report
            </a></li>
            <li><a class="dropdown-item" href="reports/invoice_item_report.php">
              Invoice Item Report
            </a></li>
            <li><a class="dropdown-item" href="reports/item_report.php">
              Item Report
            </a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Analytics
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="reports/dashboard_charts.php">
              Dashboard Charts
            </a></li>
            <li><a class="dropdown-item" href="reports/advanced_dashboard.php">
              Advanced Dashboard
            </a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</body>
</html> 