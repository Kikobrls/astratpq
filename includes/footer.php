</main>


    </div>
</footer>
</div>
</div>

<!-- jQuery (Required for previous Bootstrap 4 and Chart.js integrations) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<!-- Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>

<!-- Vendor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script src="<?php echo isset($base_url) ? $base_url : ''; ?>/assets/js/scripts.js"></script>

<!-- Simple DataTables -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('dataTable');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });

    function confirmDelete(url, message) {
        if (confirm(message || "Apakah Anda yakin ingin menghapus data ini? Aksi ini tidak dapat dibatalkan.")) {
            // Add a small delay to allow the UI to respond before navigating
            setTimeout(() => {
                window.location.href = url;
            }, 100);
        }
    }
</script>

<!-- Toastr Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Configure Toastr options globally
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

<?php
$flash = getFlash();
if ($flash):
    $type = $flash['type'];
    $message = addslashes($flash['message']);
    // Map bootstrap alert types to Toastr methods
    $toastrMethod = 'info';
    if ($type === 'success') {
        $toastrMethod = 'success';
    } elseif ($type === 'danger' || $type === 'error') {
        $toastrMethod = 'error';
    } elseif ($type === 'warning') {
        $toastrMethod = 'warning';
    }
?>
<script>
    $(document).ready(function() {
        toastr.<?php echo $toastrMethod; ?>("<?php echo $message; ?>");
    });
</script>
<?php endif; ?>

<?php if (isset($extra_js))
    echo $extra_js; ?>

</body>

</html>
