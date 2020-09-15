$(document).ready(function() {
    $(".maxmin").hide();
    $(".manyprovince").hide();
    $(".manydist").hide();
    $(".manysubdist").hide();
    $(".manyfarm").hide();
    $(".manysubfarm").hide();
    $(".manyfarmer").hide();
    $(".manyyear").hide();
    $(".manymonth").hide();
    $(".manyday").hide();

    hide1();    
    $(".onefarmer").hide();
    hideone_time();

    $(".set_pro").attr("disabled","disabled");
    $(".set_year").attr("disabled","disabled");

    $("#condition").change(function() {
        condition = $("#condition").val();
        if(condition == ""){
            $(".maxmin").hide();
        }else{
            $(".maxmin").show();
        }
    });
    $(function() {
        $(".province_list1, .province_list2, .dist_list1, .dist_list2, .subdist_list1, .subdist_list2, .farm_list1, .farm_list2,.subfarm_list1, .subfarm_list2, .farmer_list1, .farmer_list2, .year_list1, .year_list2, .month_list1, .month_list2, .day_list1, .day_list2").sortable({
            connectWith: ".sortable"
        });
    });
    $('[name="s_pro"]').change(function() {
        array_set1 = ["#dist1","#dist2","#dist3","#subdist1","#subdist2","#subdist3","#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        array_set2 = ["#dist1","#dist2","#dist3"];
        array_many1 = [".manyprovince",".manydist",".manysubdist",".manyfarm",".manysubfarm"];
        array_many2 = [".manydist",".manysubdist",".manyfarm",".manysubfarm"];

        if(document.getElementById("pro1").checked){
            hide1();
            $(".set_pro").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("pro2").checked){
            hide1();
            $(".set_pro").prop('checked', false);
            $(".manyprovince").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("pro3").checked){
            $(".oneprovince").show();
            for(i=0;i<array_set2.length;i++){
                $(array_set2[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    $('[name="s_dist"]').change(function() {
        array_set1 = ["#subdist1","#subdist2","#subdist3","#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        array_set2 = ["#subdist1","#subdist2","#subdist3"];
        array_many1 = [".manydist",".manysubdist",".manyfarm",".manysubfarm"];
        array_many2 = [".manysubdist",".manyfarm",".manysubfarm"];

        if(document.getElementById("dist1").checked){
            hide2();
            $(".set_dist").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("dist2").checked){
            hide2();
            $(".set_dist").prop('checked', false);
            $(".manydist").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("dist3").checked){
            $(".onedist").show();
            for(i=0;i<array_set2.length;i++){
                $(array_set2[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    
    $('[name="s_subdist"]').change(function() {
        array_set1 = ["#farm1","#farm2","#farm3","#subfarm1","#subfarm2","#subfarm3"];
        array_set2 = ["#farm1","#farm2","#farm3"];
        array_many1 = [".manysubdist",".manyfarm",".manysubfarm"];
        array_many2 = [".manyfarm",".manysubfarm"];
        
        if(document.getElementById("subdist1").checked){
            hide3();
            $(".set_subdist").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("subdist2").checked){
            hide3();
            $(".set_subdist").prop('checked', false);
            $(".manysubdist").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("subdist3").checked){
            $(".onesubdist").show();
            for(i=0;i<array_set2.length;i++){
                $(array_set2[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    $('[name="s_farm"]').change(function() {
        array_set1 = ["#subfarm1","#subfarm2","#subfarm3"];
        array_many1 = [".manyfarm",".manysubfarm"];
        array_many2 = [".manysubfarm"];

        if(document.getElementById("farm1").checked){
            hide4();
            $(".set_farm").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("farm2").checked){
            hide4();
            $(".set_farm").prop('checked', false);
            $(".manyfarm").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("farm3").checked){
            $(".onefarm").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    $('[name="s_subfarm"]').change(function() {
        if(document.getElementById("subfarm1").checked){
            $(".onesubfarm").hide();
        }else if(document.getElementById("subfarm2").checked){
            $(".onesubfarm").hide();
            $(".manysubfarm").show();
        }else if(document.getElementById("subfarm3").checked){
            $(".onesubfarm").show();
        }
    });
    $('[name="s_farmer"]').change(function() {
        if(document.getElementById("farmer1").checked){
            $(".onefarmer").hide();
        }else if(document.getElementById("farmer2").checked){
            $(".onefarmer").hide();
            $(".manyfarmer").show();
        }else if(document.getElementById("farmer3").checked){
            $(".onefarmer").show();
        }
    });
    $('[name="s_year"]').change(function() {
        array_set1 = ["#month1","#month2","#month3","#day1","#day2","#day3"];
        array_set2 = ["#month1","#month2","#month3"];
        array_many1 = [".manyyear",".manymonth","manyday"];
        array_many2 = [".manymonth","manyday"];
        if(document.getElementById("year1").checked){
            hideone_time();
            $(".set_year").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("year2").checked){
            hideone_time();
            $(".set_year").prop('checked', false);
            $(".manyyear").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("year3").checked){
            $(".oneyear").show();
            for(i=0;i<array_set2.length;i++){
                $(array_set2[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    $('[name="s_month"]').change(function() {
        array_set1 = ["#day1","#day2","#day3"];
        array_many1 = [".manymonth","manyday"];
        array_many2 = ["manyday"];
        if(document.getElementById("month1").checked){
            hideone_time();
            $(".set_month").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("month2").checked){
            hideone_time();
            $(".set_month").prop('checked', false);
            $(".manymonth").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many2.length;i++){
                $(array_many2[i]).hide();
            }
        }else if(document.getElementById("month3").checked){
            $(".onemonth").show();
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).removeAttr("disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }
    });
    $('[name="s_day"]').change(function() {
        if(document.getElementById("day1").checked){
            $(".oneday").hide();
        }else if(document.getElementById("day2").checked){
            $(".oneday").hide();
            $(".manyday").show();
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
            html2="";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["AD2ID"]}">${result[i]["Distrinct"]}</option>`;
            html2 += `<li Name='${result[i]['Distrinct']}' id_attr='${result[i]['AD2ID']}'>${result[i]['Distrinct']} </li>`;
            }
            $("#selectdist").html(html);
            $("#dist_list1").html(html2);
            $("#dist_list2").html("");

        });
    });
    $('#selectdist').change(function(){
        id_dist = $('#selectdist').val();
        // alert(province);
        $.post("dataForChart.php", {request: "subdist" ,id: id_dist}, function(result){
            result = JSON.parse(result);
            // console.log(result);
            html="<option selected value=0>เลือกตำบล</option> ";
            html2="";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["AD3ID"]}">${result[i]["subDistrinct"]}</option>`;
            html2 += `<li Name='${result[i]['subDistrinct']}' id_attr='${result[i]['AD3ID']}'>${result[i]['subDistrinct']} </li>`;    
            }
            $("#selectsubdist").html(html);
            $("#subdist_list1").html(html2);
            $("#subdist_list2").html("");
        });
    });
    $('#selectsubdist').change(function(){
        id_subdist = $('#selectsubdist').val();
        // alert(id_subdist);
        $.post("dataForChart.php", {request: "farm" ,id: id_subdist}, function(result){
            result = JSON.parse(result);
            console.log(result);
            html="<option selected value=0>เลือกสวน</option> ";
            html2="";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["dbID"]}">${result[i]["Name"]}</option>`;
            html2 += `<li Name='${result[i]['Name']}' id_attr='${result[i]['dbID']}'>${result[i]['Name']} </li>`;    
            }
            $("#selectfarm").html(html);
            $("#farm_list1").html(html2);
            $("#farm_list2").html("");
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
            html2="";
            for (i = 1; i <= result[0]['numrow']; i++) {
            html += `<option value="${result[i]["dbID"]}">${result[i]["Name"]}</option>`;
            html2 += `<li Name='${result[i]['Name']}' id_attr='${result[i]['dbID']}'>${result[i]['Name']} </li>`;    
            }
            $("#selectsubfarm").html(html);
            $("#subfarm_list1").html(html2);
            $("#subfarm_list2").html("");
        });
    });
    $('#setsubmit').click(function(){
        html = "";
        $('#present').html();
    });
});
function hide1(){
    $(".oneprovince").hide();
    $(".onedist").hide();
    $(".onesubdist").hide();
    $(".onefarm").hide();
    $(".onesubfarm").hide();
}
function hide2(){
    $(".onedist").hide();
    $(".onesubdist").hide();
    $(".onefarm").hide();
    $(".onesubfarm").hide();
}
function hide3(){
    $(".onesubdist").hide();
    $(".onefarm").hide();
    $(".onesubfarm").hide();
}
function hide4(){
    $(".onefarm").hide();
    $(".onesubfarm").hide();
}
function hideone_time(){
    $(".oneyear").hide();
    $(".onemonth").hide();
    $(".oneday").hide();
}