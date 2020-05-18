$(document).ready(function() {

    $("#level").change(function(e) {
        var level = $("#level").val();
        $("#Infolevel").empty();
        if (level == 2) {
            document.getElementById("Infolevel2").style.display = '';
            document.getElementById("Infolevel3").style.display = 'none ';

        } else if (level == 3) {

            $("#province").val("กรุงเทพมหานคร");
            document.getElementById("Infolevel2").style.display = 'none ';
            document.getElementById("Infolevel3").style.display = '';
        } else {
            document.getElementById("Infolevel2").style.display = 'none ';
            document.getElementById("Infolevel3").style.display = 'none ';
        }
    });
    $(function() {
        $(".list1, .list2").sortable({
            connectWith: ".sortable"
        });
    });

    $("#submitSend").click(function(e) {
        var levelP = $('#level').val();
        var provinceP = $('#province').val();
        var optradioP = $('input[name="optradio"]:checked').val();
        var optradio2P = $('input[name="optradio2"]:checked').val();
        var textotherP = $('#txt2').val();
        var children = $('#list2').children();
        var ArrayNamefarmerP = [];
        var currentChild;
        for (var i = 0; i < children.length; i++) {
            currentChild = children.eq(i);
            ArrayNamefarmerP[i] = currentChild.attr('NameF');
        }
        var jsonArrayNamefarmer = JSON.stringify(ArrayNamefarmerP);
        $.ajax({
            url: "./line.php",
            method: "POST",
            data: {
                level: levelP,
                province: provinceP,
                optradio: optradioP,
                optradio2: optradio2P,
                textother: textotherP,
                ArrayNamefarmer: jsonArrayNamefarmer
            },
            async: false,
            success: function(data) {
                swal({
                    title: "",
                    text: "การส่งข้อความเรียบร้อย",
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false

                });
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        });
    });


});

function hiddenn(pvar) {
    if (pvar == 0) {
        document.getElementById("txt1").style.display = 'none ';
        document.getElementById("txt2").style.display = 'none ';
    } else {
        document.getElementById("txt1").style.display = '';
        document.getElementById("txt2").style.display = '';
    }

}