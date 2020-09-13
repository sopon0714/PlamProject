$(document).ready(function() {
    $(".maxmin").hide();
    $(".manyprovince").hide();
    hideone();
    hideone_time();
    $(".onefarmer").hide();

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
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("pro2").checked){
            hideone();
            $(".manyprovince").show();
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
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("dist2").checked){
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("dist3").checked){
            $(".onedist").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    
    $('[name="s_subdist"]').change(function() {
        array_set1 = ["#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("subdist1").checked){
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("subdist2").checked){
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("subdist3").checked){
            $(".onesubdist").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_farm"]').change(function() {
        array_set1 = ["#subfarm1","#subfarm2","#subfarm3"];
        if(document.getElementById("farm1").checked){
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("farm2").checked){
            hideone();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("farm3").checked){
            $(".onefarm").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_subfarm"]').change(function() {
        if(document.getElementById("subfarm1").checked){
            $(".onesubfarm").hide();
        }else if(document.getElementById("subfarm2").checked){
            $(".onesubfarm").hide();
        }else if(document.getElementById("subfarm3").checked){
            $(".onesubfarm").show();
        }
    });
    $('[name="s_farmer"]').change(function() {
        if(document.getElementById("farmer1").checked){
            $(".onefarmer").hide();
        }else if(document.getElementById("farmer2").checked){
            $(".onefarmer").hide();
        }else if(document.getElementById("farmer3").checked){
            $(".onefarmer").show();
        }
    });
    $('[name="s_year"]').change(function() {
        array_set1 = ["#month1","#month2","#month3","#day1","#day2","#day3"];
        if(document.getElementById("year1").checked){
            hideone_time();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("year2").checked){
            hideone_time();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("year3").checked){
            $(".oneyear").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_month"]').change(function() {
        array_set1 = ["#day1","#day2","#day3"];
        if(document.getElementById("month1").checked){
            hideone_time();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("month2").checked){
            hideone_time();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
        }else if(document.getElementById("month3").checked){
            $(".onemonth").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
        }
    });
    $('[name="s_day"]').change(function() {
        if(document.getElementById("day1").checked){
            $(".oneday").hide();
        }else if(document.getElementById("day2").checked){
            $(".oneday").hide();
        }else if(document.getElementById("day3").checked){
            $(".oneday").show();
        }
    });

    $('#selectprovince').change(function(){
        id_province = $('#selectprovince').val();
        // alert(province);
        $.post("dataForChart.php", {request: "dist" ,id: id_province}, function(result){
            result = JSON.parse(result);
            // console.log(result);
            html="<option selected value=0>เลือกอำเภอ</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["AD2ID"]}">${result[i]["Distrinct"]}</option>`;
            }
            $("#selectdist").html(html);
        });
    });
    $('#selectdist').change(function(){
        id_dist = $('#selectdist').val();
        // alert(province);
        $.post("dataForChart.php", {request: "subdist" ,id: id_dist}, function(result){
            result = JSON.parse(result);
            // console.log(result);
            html="<option selected value=0>เลือกตำบล</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["AD3ID"]}">${result[i]["subDistrinct"]}</option>`;
            }
            $("#selectsubdist").html(html);
        });
    });
    $('#selectsubdist').change(function(){
        id_subdist = $('#selectsubdist').val();
        // alert(id_subdist);
        $.post("dataForChart.php", {request: "farm" ,id: id_subdist}, function(result){
            result = JSON.parse(result);
            console.log(result);
            html="<option selected value=0>เลือกสวน</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["dbID"]}">${result[i]["Name"]}</option>`;
            }
            $("#selectfarm").html(html);
        });
    });
    $('#selectfarm').change(function(){
        // console.log('selectsubfarm');
        id_farm = $('#selectfarm').val();
        // alert(id_farm);
        $.post("dataForChart.php", {request: "subfarm" ,id: id_farm}, function(result){
            result = JSON.parse(result);
            console.log(result);
            html="<option selected value=0>เลือกแปลง</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["dbID"]}">${result[i]["Name"]}</option>`;
            }
            $("#selectsubfarm").html(html);
        });
    });
    $('#setsubmit').click(function(){
        html = "";
        $('#present').html();
    });
});
function hideone(){
    $(".oneprovince").hide();
    $(".onedist").hide();
    $(".onesubdist").hide();
    $(".onefarm").hide();
    $(".onesubfarm").hide();
    $(".onefarmer").hide();
}
function hideone_time(){
    $(".oneyear").hide();
    $(".onemonth").hide();
    $(".oneday").hide();
}