<!-- jQuery -->
<script src="{{ asset('assets/vendor/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('assets/vendor/popper.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap.min.js') }}"></script>

<!-- Perfect Scrollbar -->
<script src="{{ asset('assets/vendor/perfect-scrollbar.min.js') }}"></script>

<!-- DOM Factory -->
<script src="{{ asset('assets/vendor/dom-factory.js') }}"></script>

<!-- MDK -->
<script src="{{ asset('assets/vendor/material-design-kit.js') }}"></script>

<!-- App -->
<script src="{{ asset('assets/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('assets/js/check-selected-row.js') }}"></script>
<script src="{{ asset('assets/js/dropdown.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-mini.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- App Settings (safe to remove) -->
<script src="{{ asset('assets/js/app-settings.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Global Settings -->
<script src="{{ asset('assets/js/settings.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('assets/vendor/Chart.min.js') }}"></script>

<!-- App Charts JS -->
<script src="{{ asset('assets/js/chartjs-rounded-bar.js') }}"></script>
<script src="{{ asset('assets/js/charts.js') }}"></script>

<!-- Chart Samples -->
<script src="{{ asset('assets/js/page.dashboard.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets/vendor/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.js') }}"></script>

<script>
    function openModal(modal) {
        $(modal).addClass("show")
            .css("display", "block")
            .attr("aria-hidden", "false")
            .attr("aria-modal", "true");
    }

    function closeModal(modal) {
        $(modal).removeClass("show")
            .css("display", "none")
            .attr("aria-hidden", "true")
            .removeAttr("aria-modal");
    }

    function showToastr(status = "info", message = "") {
        let type = status.toLowerCase();
        let title = "";
        let msg = "";

        switch (type) {
            case "success":
                title = "Berhasil!";
                msg = message;
                break;
            case "warning":
                title = "Terjadi Kesalahan!";
                msg = message;
                break;
            case "warning":
                title = "Peringatan!";
                msg = message;
                break;
            case "info":
            default:
                title = "Informasi";
                msg = message;
                break;
        }

        toastr[type](message, title);
    }

    function buttonDisabled(button) {
        $(button).attr('disabled', true);
        $(button).css('cursor', 'wait');
    }

    function buttonEnabled(button) {
        $(button).attr('disabled', false);
        $(button).css('cursor', 'default');
    }
</script>

@yield('own-script')
