<?php
include("../config/db.php");
include("../partials/header.php");

// Fetch data for charts

// 1. Item Count by Category
$itemCategoryData = [];
$res = $conn->query("SELECT c.category, COUNT(i.id) AS count FROM item i JOIN item_category c ON i.item_category = c.id GROUP BY c.category");
while ($row = $res->fetch_assoc()) {
    $itemCategoryData[] = $row;
}

// 2. Sales Per Day
$salesData = [];
$res = $conn->query("SELECT date, SUM(amount) AS total FROM invoice GROUP BY date ORDER BY date ASC");
while ($row = $res->fetch_assoc()) {
    $salesData[] = $row;
}

// 3. Top 5 Most Sold Items
$topItems = [];
$res = $conn->query("SELECT it.item_name, SUM(im.quantity) AS total_quantity FROM invoice_master im JOIN item it ON im.item_id = it.id GROUP BY it.item_name ORDER BY total_quantity DESC LIMIT 5");
while ($row = $res->fetch_assoc()) {
    $topItems[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-pie-chart me-2"></i>ERP Analytics Dashboard</h2>
  <div>
    <a href="../index.php" class="btn btn-outline-primary">
      <i class="bi bi-house-door me-1"></i>Home
    </a>
  </div>
</div>

<style>
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}

.card-body {
  padding: 1rem;
}

@media (max-width: 768px) {
  .chart-container {
    height: 250px;
  }
}
</style>

<div class="row gy-4">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        Item Count by Category
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="pieChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        Sales Per Day
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 mt-4">
    <div class="card shadow-sm">
      <div class="card-header bg-warning text-dark">
        Top 5 Most Sold Items
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="doughnutChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const pieCtx = document.getElementById('pieChart').getContext('2d');
const barCtx = document.getElementById('barChart').getContext('2d');
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');

// Pie chart data
const pieData = {
  labels: <?= json_encode(array_column($itemCategoryData, 'category')) ?>,
  datasets: [{
    label: 'Item Count',
    data: <?= json_encode(array_column($itemCategoryData, 'count')) ?>,
    backgroundColor: [
      '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#17a2b8', '#fd7e14'
    ],
    hoverOffset: 30
  }]
};

// Bar chart data
const barData = {
  labels: <?= json_encode(array_column($salesData, 'date')) ?>,
  datasets: [{
    label: 'Sales (Amount)',
    data: <?= json_encode(array_column($salesData, 'total')) ?>,
    backgroundColor: '#28a745'
  }]
};

// Doughnut chart data
const doughnutData = {
  labels: <?= json_encode(array_column($topItems, 'item_name')) ?>,
  datasets: [{
    label: 'Quantity Sold',
    data: <?= json_encode(array_column($topItems, 'total_quantity')) ?>,
    backgroundColor: [
      '#ffc107', '#007bff', '#dc3545', '#28a745', '#6f42c1'
    ],
    hoverOffset: 30
  }]
};

new Chart(pieCtx, {
  type: 'pie',
  data: pieData,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 10,
          font: {
            size: 12
          }
        }
      }
    }
  }
});

new Chart(barCtx, {
  type: 'bar',
  data: barData,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: { 
        beginAtZero: true,
        ticks: {
          font: {
            size: 11
          }
        }
      },
      x: {
        ticks: {
          font: {
            size: 11
          }
        }
      }
    },
    plugins: {
      legend: {
        labels: {
          font: {
            size: 12
          }
        }
      }
    }
  }
});

new Chart(doughnutCtx, {
  type: 'doughnut',
  data: doughnutData,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 10,
          font: {
            size: 12
          }
        }
      }
    }
  }
});
</script>

<?php include("../partials/footer.php"); ?>
