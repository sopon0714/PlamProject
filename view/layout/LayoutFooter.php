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
    start = 0;
    limit = 10;
    show_loading = $("#show_loading").html();
    start2 = 0;
    limit2 = 10;
    show_loading2 = $("#show_loading2").html();

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
        
        //second table in the same page---------------------------------

        $(".pagination_li2").click(function() {
            // console.log("pageItem");
            // console.log($(".pagination_li").html());
            $("#body2").html(show_loading2);
            $(".pagination_li2").removeClass("active");
            CurrentPage = parseInt($(this).attr("page"));
            setPageChange2(CurrentPage);
            getDataSetTable2();
        });
        $("#dataTable_next2").click(function(){
            // console.log("next");
            OwnPage = $("#CurrentPage2").attr("CurrentPage2");
            pages = $("#pages2").attr("pages2");
            // console.log("OwnPage=/"+OwnPage+"/");
            if(OwnPage != pages){
                $("#body2").html(show_loading2);
                $("#page2_"+OwnPage).removeClass("active");
                CurrentPage = parseInt(OwnPage)+1;
                setPageChange2(CurrentPage);
                getDataSetTable2();
            }
        });
        $("#dataTable_previous2").click(function(){
            // console.log("previous");
            OwnPage = parseInt($("#CurrentPage2").attr("CurrentPage2"));
            // console.log("OwnPage=/"+OwnPage+"/");
            if(OwnPage != 1){
                $("#body2").html(show_loading2);
                $("#page2_"+OwnPage).removeClass("active");
                CurrentPage = parseInt(OwnPage)-1;
                setPageChange2(CurrentPage);
                getDataSetTable2();
            }
        });
        $("#dataTable_length2").change(function(){
            $("#body2").html(show_loading2);
            CurrentPage2 = 1;
            size2 = parseInt($("#size2").attr("size2"));
            limit2 = parseInt($("#dataTable_length2").val());
            setPage2(size2,limit2);
            setPageChange2(CurrentPage2);
            getDataSetTable2();
        }); 
    });
    function setPage(size,limit){
        // console.log("set page");
        if(size == 0){
            pages = 1;
        }else{
            pages = Math.ceil(size/limit);
        }
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
        if(size == 0){
            start = -1;
            limit = 0;
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
    //second table in the same page---------------------------------
    function setPage2(size2,limit2){
        // console.log("set page");
        if(size2 == 0){
            pages = 1;
        }else{
            pages = Math.ceil(size2/limit2);
        }
        lastPage = parseInt($("#pages2").attr("pages2"));
        OwnPage = parseInt($("#CurrentPage2").attr("CurrentPage2"));
        $("#CurrentPage2").attr("CurrentPage2","1");
        $("#pages2").attr("pages2",pages);
        if(OwnPage == lastPage){
            $("#lastpage2").removeClass("active");
        }else{
            $("#page2_"+OwnPage).removeClass("active");
        }
        // console.log("last page = "+lastPage);
        // console.log("own page = "+OwnPage);
        if(pages != 1){
            for(i=2;i<lastPage;i++){
            $("#page2_"+i).attr("hidden",true);
            }
            $("#lastpage2").removeAttr("hidden");
            $("#lastpage2").attr("page",pages);
            $("#lastpage2").html(`<a href="#" aria-controls="dataTable" data-dt-idx="${pages}" tabindex="0" class="page-link">${pages}</a>`);
            // $("#page_"+lastPage).attr("id","page_"+pages);
        }else{
            $("#lastpage2").attr("hidden",true);
        }
    }
    function setPageChange2(CurrentPage){
        pages = $("#pages2").attr("pages2");
        if(CurrentPage == pages && CurrentPage != 1){
            $("#lastpage2").addClass("active");
        }else{
            $("#lastpage2").removeClass("active");
            $("#page2_"+CurrentPage).addClass("active");
        }
        $("#CurrentPage2").attr("CurrentPage2",CurrentPage);
        page = parseInt(CurrentPage);
        limit2 = parseInt($("#dataTable_length2").val());
        size2 = parseInt($("#size2").attr("size2"));
        pages = parseInt(pages);
        // pages = Math.ceil(size2/limit2);
        start2 = (page - 1) * limit2;
        if(page == pages || size2 < limit2){
            end = size2;
        }else{
            end = start2+limit2;
        }
        if(size2 == 0){
            start2 = -1;
            limit2 = 0;
        }
        // console.log("size2 = "+size2);
        // console.log("page = "+page);
        // console.log("limit2 = "+limit2);
        // console.log("start2 = "+start2);
        // console.log("end = "+end);
        // console.log("pages = "+pages);
        html = `Show ${start2+1} to ${end} of ${size2}`;
        $("#dataTable_info2").html(html);
        //page != 1 can click previous
        if(page != 1){
            $("#dataTable_previous2").removeClass("disabled");
        }else{
            $("#dataTable_previous2").addClass("disabled");
        }
        //pages != 1 and page != pages pages can click next
        if(pages != 1 && page != pages){
            $("#dataTable_next2").removeClass("disabled");
        }else{
            $("#dataTable_next2").addClass("disabled");
        }
        if(pages > 6){
            if(page >= 5 && page <= pages-4){
            $("#dataTable_ellipsis12").removeAttr("hidden");
            $("#dataTable_ellipsis22").removeAttr("hidden");
            for(i=2;i<pages;i++){
                if(i == page-1 || i == page+1 || i == page){
                // console.log("/////// i = "+i);
                $("#page2_"+i).removeAttr("hidden");
                }else{
                $("#page2_"+i).attr("hidden",true);
                }
            }
            }else if(page >= pages-3 && page <= pages){
            $("#dataTable_ellipsis12").removeAttr("hidden");
            $("#dataTable_ellipsis22").attr("hidden",true);
            for(i=2;i<pages;i++){
                if(i >= pages-4 && i <= pages){
                // console.log("page >= pages-4 /////// i = "+i);
                $("#page2_"+i).removeAttr("hidden");
                }else{
                $("#page2_"+i).attr("hidden",true);
                }
            }
            }else{
            $("#dataTable_ellipsis12").attr("hidden",true);
            $("#dataTable_ellipsis22").removeAttr("hidden");
            for(i=2;i<pages;i++){
                if(i<=5){
                // console.log("else /////// i = "+i);
                $("#page2_"+i).removeAttr("hidden");
                }else{
                $("#page2_"+i).attr("hidden",true);
                }
            }
            }
        }
    }
</script>
<script>
var fade = true;
    $(window).on("load", function() {
        if(fade){
            $(".loader-container").fadeOut(500);
        }
    });
</script>

}

</body>

</html>