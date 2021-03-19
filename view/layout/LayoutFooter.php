</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<?php include_once("MainJS.php"); ?>
{
<script>
    $(document).ready(function() {
        $('.tableSearch').DataTable({
            "ordering": false,
            "lengthMenu": [10, 50, 100, 500, 1000]
        });
        $(".preloadding").click(function() {
            $(".loader-container").fadeIn(0);
            $(".loader").fadeIn(0);
        });
    });
</script>
<script>
    $(window).on("load", function() {
        $(".loader-container").fadeOut(500);
    });
</script>

}

</body>

</html>