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
});
