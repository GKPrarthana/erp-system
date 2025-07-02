<?php
include("../config/db.php");
include("../partials/header.php");

// Handle date range filter inputs (default last 30 days)
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Sanitize dates and format for SQL
$startDate = date('Y-m-d', strtotime($startDate));
$endDate = date('Y-m-d', strtotime($endDate));

// Fetch data for each chart with date filtering

// 1. Monthly Sales Trend (Line)
$salesTrend = [];
$res = $conn->query("
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total
    FROM invoice
    WHERE date BETWEEN '$startDate' AND '$endDate'
    GROUP BY month ORDER BY month ASC
");
while ($row = $res->fetch_assoc()) $salesTrend[] = $row;

// Top 5 Districts by Sales (Bar)
$topDistricts = [];
$res = $conn->query("
    SELECT d.district, SUM(i.amount) AS total
    FROM invoice i
    JOIN customer c ON i.customer = c.id
    JOIN district d ON c.district = d.id
    WHERE i.date BETWEEN '$startDate' AND '$endDate'
    GROUP BY d.district
    ORDER BY total DESC
    LIMIT 5
");
while ($row = $res->fetch_assoc()) $topDistricts[] = $row;

// 3. Item Category Revenue Share (Doughnut)
$categoryRevenue = [];
$res = $conn->query("
    SELECT c.category, SUM(im.amount) AS total_revenue
    FROM invoice_master im
    JOIN item i ON im.item_id = i.id
    JOIN item_category c ON i.item_category = c.id
    JOIN invoice inv ON im.invoice_no = inv.invoice_no
    WHERE inv.date BETWEEN '$startDate' AND '$endDate'
    GROUP BY c.category ORDER BY total_revenue DESC
");
while ($row = $res->fetch_assoc()) $categoryRevenue[] = $row;

// 4. Average Invoice Value Over Time (Line)
$avgInvoice = [];
$res = $conn->query("
    SELECT date, AVG(amount) AS avg_invoice_value
    FROM invoice
    WHERE date BETWEEN '$startDate' AND '$endDate'
    GROUP BY date ORDER BY date
");
while ($row = $res->fetch_assoc()) $avgInvoice[] = $row;

// 5. Quantity Sold by Item Subcategory (Horizontal Bar)
$subCategoryQty = [];
$res = $conn->query("
    SELECT s.sub_category, SUM(im.quantity) AS total_quantity
    FROM invoice_master im
    JOIN item i ON im.item_id = i.id
    JOIN item_subcategory s ON i.item_subcategory = s.id
    JOIN invoice inv ON im.invoice_no = inv.invoice_no
    WHERE inv.date BETWEEN '$startDate' AND '$endDate'
    GROUP BY s.sub_category ORDER BY total_quantity DESC
");
while ($row = $res->fetch_assoc()) $subCategoryQty[] = $row;
?>

<div class="container my-4">
  <h2>Advanced ERP Analytics Dashboard</h2>

  <!-- Date Range Filter -->
  <form method="get" class="row g-3 mb-4 align-items-center">
    <div class="col-auto">
      <label for="start_date" class="form-label">Start Date</label>
      <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
    </div>
    <div class="col-auto">
      <label for="end_date" class="form-label">End Date</label>
      <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
    </div>
    <div class="col-auto mt-4">
      <button type="submit" class="btn btn-primary">Filter</button>
      <button type="button" id="resetDates" class="btn btn-secondary">Reset</button>
    </div>
  </form>

  <div class="row gy-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Monthly Sales Trend</span>
          <button class="btn btn-sm btn-outline-secondary export-btn" data-chart="monthlySalesChart">Export PNG</button>
        </div>
        <div class="card-body">
          <canvas id="monthlySalesChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Top 5 Districts by Sales</span>
          <button class="btn btn-sm btn-outline-secondary export-btn" data-chart="topDistrictsChart">Export PNG</button>
        </div>
        <div class="card-body">
          <canvas id="topDistrictsChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mt-4">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Item Category Revenue Share</span>
          <button class="btn btn-sm btn-outline-secondary export-btn" data-chart="categoryRevenueChart">Export PNG</button>
        </div>
        <div class="card-body">
          <canvas id="categoryRevenueChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mt-4">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Average Invoice Value Over Time</span>
          <button class="btn btn-sm btn-outline-secondary export-btn" data-chart="avgInvoiceChart">Export PNG</button>
        </div>
        <div class="card-body">
          <canvas id="avgInvoiceChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-12 mt-4">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Quantity Sold by Item Subcategory</span>
          <button class="btn btn-sm btn-outline-secondary export-btn" data-chart="subCategoryQtyChart">Export PNG</button>
        </div>
        <div class="card-body">
          <canvas id="subCategoryQtyChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Dark Mode Toggle -->
  <div class="form-check form-switch fixed-bottom m-4">
    <input class="form-check-input" type="checkbox" id="darkModeToggle">
    <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Parsing PHP data to JS
const salesTrendLabels = <?= json_encode(array_column($salesTrend, 'month')) ?>;
const salesTrendData = <?= json_encode(array_column($salesTrend, 'total')) ?>;

const topDistrictsLabels = <?= json_encode(array_column($topDistricts, 'district')) ?>;
const topDistrictsData = <?= json_encode(array_column($topDistricts, 'total')) ?>;

const categoryRevenueLabels = <?= json_encode(array_column($categoryRevenue, 'category')) ?>;
const categoryRevenueData = <?= json_encode(array_column($categoryRevenue, 'total_revenue')) ?>;

const avgInvoiceLabels = <?= json_encode(array_column($avgInvoice, 'date')) ?>;
const avgInvoiceData = <?= json_encode(array_column($avgInvoice, 'avg_invoice_value')) ?>;

const subCategoryQtyLabels = <?= json_encode(array_column($subCategoryQty, 'sub_category')) ?>;
const subCategoryQtyData = <?= json_encode(array_column($subCategoryQty, 'total_quantity')) ?>;

// Chart configs
const monthlySalesChart = new Chart(
  document.getElementById('monthlySalesChart'),
  {
    type: 'line',
    data: {
      labels: salesTrendLabels,
      datasets: [{
        label: 'Sales',
        data: salesTrendData,
        fill: false,
        borderColor: '#007bff',
        tension: 0.1
      }]
    },
    options: { responsive: true }
  }
);

const topDistrictsChart = new Chart(
  document.getElementById('topDistrictsChart'),
  {
    type: 'bar',
    data: {
      labels: topDistrictsLabels,
      datasets: [{
        label: 'Sales',
        data: topDistrictsData,
        backgroundColor: '#28a745'
      }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  }
);

const categoryRevenueChart = new Chart(
  document.getElementById('categoryRevenueChart'),
  {
    type: 'doughnut',
    data: {
      labels: categoryRevenueLabels,
      datasets: [{
        label: 'Revenue',
        data: categoryRevenueData,
        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1']
      }]
    },
    options: { responsive: true }
  }
);

const avgInvoiceChart = new Chart(
  document.getElementById('avgInvoiceChart'),
  {
    type: 'line',
    data: {
      labels: avgInvoiceLabels,
      datasets: [{
        label: 'Avg Invoice Value',
        data: avgInvoiceData,
        fill: true,
        backgroundColor: 'rgba(40,167,69,0.2)',
        borderColor: '#28a745',
        tension: 0.1
      }]
    },
    options: { responsive: true }
  }
);

const subCategoryQtyChart = new Chart(
  document.getElementById('subCategoryQtyChart'),
  {
    type: 'bar',
    data: {
      labels: subCategoryQtyLabels,
      datasets: [{
        label: 'Quantity Sold',
        data: subCategoryQtyData,
        backgroundColor: '#fd7e14'
      }]
    },
    options: { responsive: true, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
  }
);

// Export PNG buttons
document.querySelectorAll('.export-btn').forEach(button => {
  button.addEventListener('click', () => {
    const chartId = button.dataset.chart;
    const chartInstance = window[chartId];
    if (chartInstance) {
      const url = chartInstance.toBase64Image();
      const a = document.createElement('a');
      a.href = url;
      a.download = `${chartId}.png`;
      a.click();
    }
  });
});

// Reset date inputs
document.getElementById('resetDates').addEventListener('click', () => {
  document.getElementById('start_date').value = '';
  document.getElementById('end_date').value = '';
});

// Dark Mode toggle + persist
const toggle = document.getElementById('darkModeToggle');
const body = document.body;
const darkModeKey = 'erpDarkMode';

function applyDarkMode(enabled) {
  if (enabled) body.classList.add('dark-mode');
  else body.classList.remove('dark-mode');
}

toggle.checked = localStorage.getItem(darkModeKey) === 'true';
applyDarkMode(toggle.checked);

toggle.addEventListener('change', () => {
  localStorage.setItem(darkModeKey, toggle.checked);
  applyDarkMode(toggle.checked);
});
</script>

<style>
/* Simple dark mode styles */
body.dark-mode {
  background-color: #121212;
  color: #eee;
}
body.dark-mode .card {
  background-color: #1e1e1e;
  color: #eee;
}
body.dark-mode .btn-outline-secondary {
  color: #ddd;
  border-color: #555;
}
body.dark-mode .form-control {
  background-color: #2b2b2b;
  color: #eee;
  border-color: #555;
}
</style>

<?php include("../partials/footer.php"); ?>
