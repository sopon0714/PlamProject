$(document).ready(function() {
    $(".maxmin").hide();
    $(".oneprovince").hide();
    $(".manyprovince").hide();

    $("#condition").change(function() {
        condition = $("#condition").val();
        if(condition == ""){
            $(".maxmin").hide();
        }else{
            $(".maxmin").show();
        }
    });
    $(function() {
        $(".list1, .list2").sortable({
            connectWith: ".sortable"
        });
    });
    $('[name="s_pro"]').change(function() {
        array_set1 = ["#dist1","#dist2","#dist3","#subdist1","#subdist2","#subdist3","#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("pro1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("pro2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("pro3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_dist"]').change(function() {
        array_set1 = ["#subdist1","#subdist2","#subdist3","#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("dist1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("dist2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("dist3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_subdist"]').change(function() {
        array_set1 = ["#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("subdist1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("subdist2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("subdist3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_farm"]').change(function() {
        array_set1 = ["#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("farm1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("farm2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("farm3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_year"]').change(function() {
        array_set1 = ["#month1","#month2","#month3","#day1","#day2","#day3"];
        if(document.getElementById("year1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("year2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("year3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_month"]').change(function() {
        array_set1 = ["#day1","#day2","#day3"];
        if(document.getElementById("month1").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("month2").checked){
            $(".oneprovince").hide();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }

        }else if(document.getElementById("month3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });

});
