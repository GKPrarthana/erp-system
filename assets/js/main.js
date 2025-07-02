document.addEventListener('DOMContentLoaded', function () {
  // Live Search Filter
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('keyup', () => {
      const filter = searchInput.value.toLowerCase();

      const table = document.querySelector('table.table');
      if (!table) return;

      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }

  // Confirm delete
  document.querySelectorAll('a.btn-danger').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to delete this record?')) {
        e.preventDefault();
      }
    });
  });

  // Auto focus first input
  const firstInput = document.querySelector('form input:not([type=hidden]), form select');
  if (firstInput) firstInput.focus();

  // Auto-dismiss alerts
  const alertBox = document.querySelector('.alert');
  if (alertBox) {
    setTimeout(() => alertBox.style.display = 'none', 4000);
  }

  //export button
  const exportBtn = document.getElementById("exportCsvBtn");
  if (exportBtn) {
    exportBtn.addEventListener("click", function () {
      const table = document.querySelector("table");
      if (!table) return;
  
      let csv = [];
      const rows = table.querySelectorAll("tr");
  
      rows.forEach((row) => {
        let cols = Array.from(row.querySelectorAll("th, td")).map((cell) =>
          `"${cell.textContent.trim()}"`
        );
        csv.push(cols.join(","));
      });
  
      // Create CSV file
      const csvContent = csv.join("\n");
      const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
      const url = URL.createObjectURL(blob);
  
      // Trigger download
      const link = document.createElement("a");
      link.setAttribute("href", url);
      link.setAttribute("download", "report.csv");
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  }
});