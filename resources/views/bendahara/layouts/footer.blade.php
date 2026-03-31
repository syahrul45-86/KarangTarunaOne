{{-- <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('template/vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('template/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('template/js/demo/chart-pie-demo.js') }}"></script> --}}
<script>
    document.getElementById('searchInput').addEventListener("keyup", function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dendaTable tbody tr");

        rows.forEach(row => {
            let nama = row.querySelector(".kolom-nama").innerText.toLowerCase();

            if (nama.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
