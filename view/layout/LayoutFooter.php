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
        $('.tableSearch1').DataTable({
            "paging": false,
            "info" : false,
            "ordering": false,
            "searching": false
        });
        $(".preloadding").click(function() {
            $(".loader-container").fadeIn(0);
            $(".loader").fadeIn(0);
        });
        show_loading = $("#show_loading").html();
        start = 0;
        limit = 10;
        $(".pagination_li").click(function() {
            // console.log("pageItem");
            // console.log($(".pagination_li").html());
            $("#body").html(show_loading);
            $(".pagination_li").removeClass("active");
            CurrentPage = parseInt($(this).attr("page"));
            setPageChange(CurrentPage);
            getDataSetTable();
        });
        $("#dataTable_next").click(function(){
            // console.log("next");
            OwnPage = $("#CurrentPage").attr("CurrentPage");
            pages = $("#pages").attr("pages");
            // console.log("OwnPage=/"+OwnPage+"/");
            if(OwnPage != pages){
                $("#body").html(show_loading);
                $("#page_"+OwnPage).removeClass("active");
                CurrentPage = parseInt(OwnPage)+1;
                setPageChange(CurrentPage);
                getDataSetTable();
            }
        });
        $("#dataTable_previous").click(function(){
            // console.log("previous");
            OwnPage = parseInt($("#CurrentPage").attr("CurrentPage"));
            // console.log("OwnPage=/"+OwnPage+"/");
            if(OwnPage != 1){
                $("#body").html(show_loading);
                $("#page_"+OwnPage).removeClass("active");
                CurrentPage = parseInt(OwnPage)-1;
                setPageChange(CurrentPage);
                getDataSetTable();
            }
        });
        $("#dataTable_length").change(function(){
            $("#body").html(show_loading);
            CurrentPage = 1;
            size = parseInt($("#size").attr("size"));
            limit = parseInt($("#dataTable_length").val());
            setPage(size,limit);
            setPageChange(CurrentPage);
            getDataSetTable();
        }); 
    });
    function setPage(size,limit){
        // console.log("set page");
        pages = Math.ceil(size/limit);
        lastPage = parseInt($("#pages").attr("pages"));
        OwnPage = parseInt($("#CurrentPage").attr("CurrentPage"));
        $("#CurrentPage").attr("CurrentPage","1");
        $("#pages").attr("pages",pages);
        if(OwnPage == lastPage){
            $("#lastpage").removeClass("active");
        }else{
            $("#page_"+OwnPage).removeClass("active");
        }
        // console.log("last page = "+lastPage);
        // console.log("own page = "+OwnPage);
        if(pages != 1){
            for(i=2;i<lastPage;i++){
            $("#page_"+i).attr("hidden",true);
            }
            $("#lastpage").removeAttr("hidden");
            $("#lastpage").attr("page",pages);
            $("#lastpage").html(`<a href="#" aria-controls="dataTable" data-dt-idx="${pages}" tabindex="0" class="page-link">${pages}</a>`);
            // $("#page_"+lastPage).attr("id","page_"+pages);
        }else{
            $("#lastpage").attr("hidden",true);
        }
    }
    function setPageChange(CurrentPage){
        pages = $("#pages").attr("pages");
        if(CurrentPage == pages && CurrentPage != 1){
            $("#lastpage").addClass("active");
        }else{
            $("#lastpage").removeClass("active");
            $("#page_"+CurrentPage).addClass("active");
        }
        $("#CurrentPage").attr("CurrentPage",CurrentPage);
        page = parseInt(CurrentPage);
        limit = parseInt($("#dataTable_length").val());
        size = parseInt($("#size").attr("size"));
        pages = parseInt(pages);
        // pages = Math.ceil(size/limit);
        start = (page - 1) * limit;
        if(page == pages || size < limit){
            end = size;
        }else{
            end = start+limit;
        }
        // console.log("size = "+size);
        // console.log("page = "+page);
        // console.log("limit = "+limit);
        // console.log("start = "+start);
        // console.log("end = "+end);
        // console.log("pages = "+pages);
        html = `Show ${start+1} to ${end} of ${size}`;
        $("#dataTable_info").html(html);
        //page != 1 can click previous
        if(page != 1){
            $("#dataTable_previous").removeClass("disabled");
        }else{
            $("#dataTable_previous").addClass("disabled");
        }
        //pages != 1 and page != pages pages can click next
        if(pages != 1 && page != pages){
            $("#dataTable_next").removeClass("disabled");
        }else{
            $("#dataTable_next").addClass("disabled");
        }
        if(pages > 6){
            if(page >= 5 && page <= pages-4){
            $("#dataTable_ellipsis1").removeAttr("hidden");
            $("#dataTable_ellipsis2").removeAttr("hidden");
            for(i=2;i<pages;i++){
                if(i == page-1 || i == page+1 || i == page){
                // console.log("/////// i = "+i);
                $("#page_"+i).removeAttr("hidden");
                }else{
                $("#page_"+i).attr("hidden",true);
                }
            }
            }else if(page >= pages-3 && page <= pages){
            $("#dataTable_ellipsis1").removeAttr("hidden");
            $("#dataTable_ellipsis2").attr("hidden",true);
            for(i=2;i<pages;i++){
                if(i >= pages-4 && i <= pages){
                // console.log("page >= pages-4 /////// i = "+i);
                $("#page_"+i).removeAttr("hidden");
                }else{
                $("#page_"+i).attr("hidden",true);
                }
            }
            }else{
            $("#dataTable_ellipsis1").attr("hidden",true);
            $("#dataTable_ellipsis2").removeAttr("hidden");
            for(i=2;i<pages;i++){
                if(i<=5){
                // console.log("else /////// i = "+i);
                $("#page_"+i).removeAttr("hidden");
                }else{
                $("#page_"+i).attr("hidden",true);
                }
            }
            }
        }
    }
</script>
<script>
var fade = true;
    $(window).on("load", function() {
        console.log("load fade out");
        if(fade){
            $(".loader-container").fadeOut(500);
        }
    });
</script>

}

</body>

</html>