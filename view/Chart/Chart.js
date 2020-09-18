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

    $("#chose_cond").change(function() {
        chose_cond = $("#chose_cond").val();
        if(chose_cond == "ทั้งหมด"){
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
            $(".manyfarmer").hide();
            $(".onefarmer").hide();
        }else if(document.getElementById("farmer2").checked){
            $(".onefarmer").hide();
            $(".manyfarmer").show();
        }else if(document.getElementById("farmer3").checked){
            $(".manyfarmer").hide();
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
            $(".manyday").hide();
            $(".oneday").hide();
        }else if(document.getElementById("day2").checked){
            $(".oneday").hide();
            $(".manyday").show();
        }else if(document.getElementById("day3").checked){
            $(".manyday").hide();
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
    $('#selectyear1').change(function(){
        $.post("dataForChart.php", {request: "selectyear"}, function(result){
            select_year = $('#selectyear1').val();
            result = JSON.parse(result);
            console.log(result);
            html="<option selected value=0>เลือกปี</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
                if(result[i]["Year2"] > select_year)
                    html += `<option value="${result[i]["Year2"]}">${result[i]["Year2"]}</option>`;
            }
            $("#selectyear2").html(html);
        });
    });
    $('#selectmonth1').change(function(){
        result = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
        select_month = $('#selectmonth1').val();
        html="<option selected value=0>เลือกเดือน</option> ";
        for (i = 0; i < result.length ; i++) {
            if((i+1) > select_month)
                html += `<option value="${(i+1)}">${result[i]}</option>`;
        }
        $("#selectmonth2").html(html);
    });
    $('#selectmonth').change(function(){
        $("#selectday1").val("1");
        $("#selectday2").val("2");
        dayin = getDaysInMonth($('#selectmonth').val(),$('#selectyear').val());
        $("#selectday").attr("max",dayin);
        $("#selectday1").attr("max",dayin);
        $("#selectday2").attr("max",dayin);
    });
    $('#selectday1').change(function(){
        select_day = $('#selectday1').val();
        select_day++;
        $("#selectday2").attr("min",(select_day));
    });
    $('#setsubmit').click(function(){
        chose_label = $('#chose_label').val();
        chose_type = $('#chose_type').val();
        chose_cal = $('#chose_cal').val();
        chose_cond = $('#chose_cond').val();

        html = chose_type+" "+chose_cal;
        if(chose_cond == "ทั้งหมด")
            html += " ตาม ";
        else
            html += " ที่"+chose_cond+" "+$('#order').val()+" ลำดับ ";
        html += chose_label;

        //pro/dist/subdist/farm/subfarm
        if($("#pro1").prop('checked')){
            html += " ของทุกจังหวัด";
        }else if($("#pro2").prop('checked')){
            html += " ของจังหวัด....";
        }else{
            html += " ของจังหวัด"+$("#selectprovince option:selected").html();
            if($("#dist1").prop('checked')){
                html += " ของทุกอำเภอ";
            }else if($("#dist2").prop('checked')){
                html += " ของอำเภอ....";
            }else{
                html += " ของอำเภอ"+$("#selectdist option:selected").html();
                if($("#subdist1").prop('checked')){
                    html += " ของทุกตำบล";
                }else if($("#subdist2").prop('checked')){
                    html += " ของตำบล....";
                }else{
                    html += " ของตำบล"+$("#selectsubdist option:selected").html();
                    if($("#farm1").prop('checked')){
                        html += " ของทุกสวน";
                    }else if($("#farm2").prop('checked')){
                        html += " ของสวน....";
                    }else{
                        html += " ของสวน"+$("#selectfarm option:selected").html();
                        if($("#subfarm1").prop('checked')){
                            html += " ของทุกแปลง";
                        }else if($("#subfarm2").prop('checked')){
                            html += " ของแปลง....";
                        }else{
                            html += " ของแปลง"+$("#selectsubfarm option:selected").html();
                        }
                    }
                }                
            }
            //farmer
            if($("#farmer1").prop('checked')){
                html += " ของทุกเกษตรกร";
            }else if($("#farmer2").prop('checked')){
                html += " ของเกษตรกร....";
            }else{
                html += " ของเกษตรกร"+$("#selectfarmer option:selected").html();
            }
            //year/month/day
            if($("#year1").prop('checked')){
                html += " ของทุกปี ("+$('#minyear').html()+" - "+$('#maxyear').html()+")";
            }else if($("#yesr2").prop('checked')){
                html += " ของปี "+$('#selectyear1').val()+" - "+$('#selectyear2').val();
            }else{
                html += " ของปี "+$("#selectyear option:selected").html();
                if($("#month1").prop('checked')){
                    html += " ของทุกเดือน";
                }else if($("#month2").prop('checked')){
                    html += " ของเดือน "+$('#selectmonth1').val()+" - "+$('#selectmonth2').val();
                }else{
                    html += " ของเดือน "+$("#selectmonth option:selected").html();
                    if($("#day1").prop('checked')){
                        html += " ของทุกวัน";
                    }else if($("#day2").prop('checked')){
                        html += " ของวันที่ "+$('#selectday1').val()+" - "+$('#selectday2').val();
                    }else{
                        html += " ของวันที่ "+$("#selectday option:selected").html();
                        
                    }
                }
            }

        }
        $('#headshow').html(html);

    });
});
function getDaysInMonth (month,year) {
   return new Date(year, month, 0).getDate();
  }
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