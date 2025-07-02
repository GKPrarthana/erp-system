document.addEventListener('DOMContentLoaded', function () {
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

//console.log("JS is loaded!");
