$(document).ready(function() {
    $('.tt').tooltip();
    $(document).on("change", "#s_province", function() {

        var e = document.getElementById("s_province");
        var select_id = e.options[e.selectedIndex].value;
        // console.log(select_id);
        data_show(select_id, "s_distrinct", '');


    });

    function data_show(select_id, result, point_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                // console.log(result);
                document.getElementById(result).innerHTML = xhttp.responseText;

            };
        }
        xhttp.open("POST", "data.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`select_id=${select_id}&result=${result}&point_id=${point_id}`);
    }
    $('.total_check').change(function() {

        $('.checkmark').each(function() {
            $(this).prop("checked", $('.total_check').prop("checked"));
        })
    })
    $('.checkmark').change(function() {
        i = 0;
        if (!$(this).is(':checked')) {
            $('.total_check').prop("checked", false);
        } else {
            $('.checkmark').each(function() {
                if ($(this).is(':checked')) {
                    i++;
                }
            })
            if (i == 6) {
                $('.total_check').prop("checked", true);
            }
        }
    })




});