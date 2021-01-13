$(document).ready(function() {
    $(".maxmin").hide();
    $(".maxmin2").hide();
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
    hide1_time();

    $(".set_pro").attr("disabled","disabled");
    $(".set_year").attr("disabled","disabled");

    $('#show_chart').hide();
    $('#show_error').hide();
    $('#show_nodata').hide();
    $('#show_loading').hide();
    $("#multi_chart").hide();

    $("#chose_cond").change(function() {
        chose_cond = $("#chose_cond").val();
        if(chose_cond == ""){
            $(".maxmin").hide();
        }else{
            $(".maxmin").show();
        }
    });
    $("#chose_cond2").change(function() {
        chose_cond2 = $("#chose_cond2").val();
        if(chose_cond2 == ""){
            $(".maxmin2").hide();
        }else{
            $(".maxmin2").show();
        }
    });
    $(function() {
        $(".province_list1, .province_list2, .dist_list1, .dist_list2, .subdist_list1, .subdist_list2, .farm_list1, .farm_list2,.subfarm_list1, .subfarm_list2, .farmer_list1, .farmer_list2, .year_list1, .year_list2, .month_list1, .month_list2, .day_list1, .day_list2").sortable({
            connectWith: ".sortable"
        });
    });
    $('[name="present"]').change(function() {
        pre = $('input[name="present"]:checked').val(); 
        if(pre == "pie" || pre == "bar" || pre == "multi_bar" || pre == "complex_bar" || pre == "chart_radar" || pre == "mix" ){
            if(pre == "multi_bar" || pre == "complex_bar" || pre == "chart_radar" || pre == "mix" ){
                $("#chose_label_span1").html("เลือกหัวข้อหลัก");
                $("#multi_chart").show();
            }else if(pre == "pie" || pre == "bar"){
                $("#chose_label_span1").html("เลือกหัวข้อ");
                $("#multi_chart").hide();
            }
            html1 = `<option value="">กรุณาเลือกหัวข้อ</option>
            <option name="province" id="province" value="Province">จังหวัด</option>
            <option name="district" id="district" value="Distrinct">อำเภอ</option>
            <option name="subdistrict" id="subdistrict" value="SubDistrinct">ตำบล</option>
            <option name="farm" id="farm" value="F_name">สวน</option>
            <option name="subfarm" id="subfarm" value="SF_name">แปลง</option>
            <option name="farmer" id="farmer" value="FM_name">เกษตรกร</option>
            <option name="year" id="year" value="Year2">ปี</option>
            <option name="month" id="month" value="Month">เดือน</option>
            <option name="day" id="day" value="dd">วัน</option>`;

        }else if(pre == "line" || pre == "area" || pre == "table" || pre == "multi_line" || pre == "multi_area"){
            if(pre == "multi_line" || pre == "multi_area"){
                $("#chose_label_span1").html("เลือกหัวข้อหลัก");
                $("#multi_chart").show();
            }else{
                $("#chose_label_span1").html("เลือกหัวข้อ");
                $("#multi_chart").hide();
            }
            html1 = `<option value="">กรุณาเลือกหัวข้อ</option>
            <option name="year" id="year" value="Year2">ปี</option>
            <option name="month" id="month" value="Month">เดือน</option>
            <option name="day" id="day" value="dd">วัน</option>`;
        }
        $("#chose_label1").html(html1);

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
            hide1_time();
            $(".set_year").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("year2").checked){
            hide1_time();
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
            hide2_time();
            $(".set_month").prop('checked', false);
            for(i=0;i<array_set1.length;i++){
                $(array_set1[i]).attr("disabled","disabled");
            }
            for(i=0;i<array_many1.length;i++){
                $(array_many1[i]).hide();
            }
        }else if(document.getElementById("month2").checked){
            hide2_time();
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
            html = "";
            // html="<option selected value=0>เลือกปี</option> ";
            for (i = 1; i <= result[0]['numrow']; i++) {
                if(result[i]["Year2"] > select_year)
                    html += `<option value="${result[i]["Year2"]}">${result[i]["Year2"]}</option>`;
            }
            $("#selectyear2").html(html);
        });
    });
    $('#selectmonth1').change(function(){
        month = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
        select_month = $('#selectmonth1').val();
        html = "";
        // html="<option selected value=0>เลือกเดือน</option> ";
        for (i = 0; i < month.length ; i++) {
            if((i+1) > select_month)
                html += `<option value="${(i+1)}">${month[i]}</option>`;
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
        $("#selectday2").val(select_day);
    });
    $('#setsubmit').click(function(){
        
        present = $('input[name="present"]:checked').val(); 
            // console.log("present=|"+present+"|");
            // console.log("chose_label1=|"+$('#chose_label1').val()+"|");
            // console.log("chose_type=|"+$('#chose_type').val()+"|");
            // console.log("chose_cal=|"+$('#chose_cal').val()+"|");
        if(present == "table" || present == "pie" || present == "line" || present == "multi_line" || present == "bar" || present == "chart_radar" || 
        present == "multi_bar" || present == "complex_bar" || present == "area" || present == "multi_area" || present == "mix"
        ){
            if($('#chose_label1').val() != "" && $('#chose_type').val() != "" && $('#chose_cal').val() != ""){
                $('#show_chart').hide();
                $('#show_error').hide();
                $('#show_nodata').hide();
                $('#show_loading').show();

                chose_label1 = $("#chose_label1 option:selected").html();
                chose_type = $("#chose_type option:selected").html();
                chose_cal = $("#chose_cal option:selected").html();
                chose_cond = $("#chose_cond option:selected").html();
                
        
                html = chose_type+" "+chose_cal;
                if(chose_cond == "ทั้งหมด")
                    html += " ตาม ";
                else
                    html += " ที่"+chose_cond+" "+$('#order').val()+" ลำดับ ";
                html += chose_label1;
                if($('#chose_label2').val() != ""){
                    chose_label2 = $("#chose_label2 option:selected").html();
                    html += " และ "+chose_label2;
                }
        
                //pro/dist/subdist/farm/subfarm
                SET1 = Array();

                SET2 = Array();
                TOPIC2 = "";

                SET3 = Array();
                TOPIC3 = "";

                if($("#pro1").prop('checked')){
                    SET1 = null;
                    html += " ของทุกจังหวัด";
                }else if($("#pro2").prop('checked')){
                    ArrayData = getArrayMany("#province_list2");
                    // console.log(ArrayData);
                    SET1 = ArrayData;
                    SET1[0] = "Province";
                    html += " ของจังหวัด";
                    for(i=1;i<ArrayData.length;i++){
                        html += ArrayData[i];
                        if(i != ArrayData.length-1) html += ", ";
                    }
                }else if($("#pro3").prop('checked')){
                    SET1[1] = $("#selectprovince option:selected").html();
                    SET1[0] = "Province";
                    html += " ของจังหวัด"+$("#selectprovince option:selected").html();
                    if($("#dist1").prop('checked')){
                        html += " ของทุกอำเภอ";
                    }else if($("#dist2").prop('checked')){
                        ArrayData = getArrayMany("#dist_list2");
                        SET1 = ArrayData;
                        SET1[0] = "Distrinct";
                        html += " ของอำเภอ";
                        for(i=1;i<ArrayData.length;i++){
                            html += ArrayData[i];
                            if(i != ArrayData.length-1) html += ", ";
                        }
                    }else if($("#dist3").prop('checked')){
                        SET1[1] = $("#selectdist option:selected").html();
                        SET1[0] = "Distrinct";
                        html += " ของอำเภอ"+$("#selectdist option:selected").html();
                        if($("#subdist1").prop('checked')){
                            html += " ของทุกตำบล";
                        }else if($("#subdist2").prop('checked')){
                            ArrayData = getArrayMany("#subdist_list2");
                            SET1 = ArrayData;
                            SET1[0] = "SubDistrinct";
                            html += " ของตำบล";
                            for(i=1;i<ArrayData.length;i++){
                                html += ArrayData[i];
                                if(i != ArrayData.length-1) html += ", ";
                            }
                        }else if($("#subdist3").prop('checked')){
                            SET1[1] = $("#selectsubdist option:selected").html();
                            SET1[0] = "SubDistrinct";
                            html += " ของตำบล"+$("#selectsubdist option:selected").html();
                            if($("#farm1").prop('checked')){
                                html += " ของทุกสวน";
                            }else if($("#farm2").prop('checked')){
                                ArrayData = getArrayMany("#farm_list2");
                                SET1 = ArrayData;
                                SET1[0] = "F_name";
                                html += " ของสวน";
                                for(i=1;i<ArrayData.length;i++){
                                    html += ArrayData[i];
                                    if(i != ArrayData.length-1) html += ", ";
                                }
                            }else if($("#farm3").prop('checked')){
                                SET1[1] = $("#selectfarm option:selected").html();
                                SET1[0] = "F_name";
                                html += " ของสวน"+$("#selectfarm option:selected").html();
                                if($("#subfarm1").prop('checked')){
                                    html += " ของทุกแปลง";
                                }else if($("#subfarm2").prop('checked')){
                                    ArrayData = getArrayMany("#subfarm_list2");
                                    SET1 = ArrayData;
                                    SET1[0] = "SF_name";
                                    html += " ของแปลง";
                                    for(i=1;i<ArrayData.length;i++){
                                        html += ArrayData[i];
                                        if(i != ArrayData.length-1) html += ", ";
                                    }
                                }else if($("#subfarm3").prop('checked')){
                                    SET1[1] = $("#selectsubfarm option:selected").html();
                                    SET1[0] = "SF_name";
                                    html += " ของแปลง"+$("#selectsubfarm option:selected").html();
                                }
                            }
                        }                
                    }
                }
                //farmer
                if($("#farmer1").prop('checked')){
                    SET2 = null;
                    html += " ของทุกเกษตรกร";
                }else if($("#farmer2").prop('checked')){
                    ArrayData2 = getArrayMany("#farmer_list2");
                    SET2 = ArrayData2;
                    SET2[0] = "FM_name";
                    html += " ของเกษตรกร";
                    for(i=1;i<ArrayData2.length;i++){
                        html += ArrayData2[i];
                        if(i != ArrayData2.length-1) html += ", ";
                    }
                }else if($("#farmer3").prop('checked')){
                    SET2[1] = $("#selectfarmer option:selected").html();
                    SET2[0] = "FM_name";
                    html += " ของเกษตรกร"+$("#selectfarmer option:selected").html();
                }
                //year/month/day
                SET3[3] = "";
                SET3[6] = "";

                if($("#year1").prop('checked')){
                    SET3 = null;
                    html += " ของทุกปี ("+$('#minyear').html()+" - "+$('#maxyear').html()+")";
                }else if($("#year2").prop('checked')){
                    SET3[1] = $('#selectyear1').val();
                    SET3[2] = $('#selectyear2').val();
                    SET3[0] = "Year2";
                    html += " ของปี "+$('#selectyear1').val()+" - "+$('#selectyear2').val();
                }else if($("#year3").prop('checked')){
                    SET3[1] = $("#selectyear option:selected").html();
                    SET3[2] = $("#selectyear option:selected").html();
                    SET3[0] = "Year2";
                    html += " ของปี "+$("#selectyear option:selected").html();
                    if($("#month1").prop('checked')){
                        html += " ของทุกเดือน";
                    }else if($("#month2").prop('checked')){
                        SET3[4] = monthToNumber($("#selectmonth1 option:selected").html());
                        SET3[5] = monthToNumber($("#selectmonth2 option:selected").html());
                        SET3[3] = "Month";
                        html += " ของเดือน "+$("#selectmonth1 option:selected").html()+" - "+$("#selectmonth2 option:selected").html();
                    }else if($("#month3").prop('checked')){
                        SET3[4] = monthToNumber($("#selectmonth option:selected").html());
                        SET3[5] = monthToNumber($("#selectmonth option:selected").html());
                        SET3[3] = "Month";
                        html += " ของเดือน "+$("#selectmonth option:selected").html();
                        if($("#day1").prop('checked')){
                            html += " ของทุกวัน";
                        }else if($("#day2").prop('checked')){
                            SET3[7] = $('#selectday1').val()
                            SET3[8] = $('#selectday2').val();
                            SET3[6] = "dd";
                            html += " ของวันที่ "+$('#selectday1').val()+" - "+$('#selectday2').val();
                        }else if($("#day3").prop('checked')){
                            SET3[8] = $("#selectday").val();
                            SET3[7] = $("#selectday").val();
                            SET3[6] = "dd";
                            html += " ของวันที่ "+$("#selectday").val();
                        }
                    }
                }
                console.log(SET1);
                console.log(SET2);
                console.log(SET3);


                $('.headshow').html(html);
                //for show in table
                label1 = $("#chose_label1 option:selected").html();
                label2 = $("#chose_label2 option:selected").html();
                data1 = $("#chose_type option:selected").html();
                cal1 = $("#chose_cal option:selected").attr("show");

                //for sent to php
                chose_label1 = $("#chose_label1 option:selected").val();
                chose_label2 = $("#chose_label2 option:selected").val();
                chose_type = $("#chose_type option:selected").val();
                chose_cal = $("#chose_cal option:selected").val();
                chose_cond = $("#chose_cond option:selected").val();

                console.log(label1);
                $.post("dataForChart.php", {request: "chart" ,chose_label1: chose_label1,chose_label2: chose_label2,chose_type: chose_type,
                chose_cal: chose_cal,chose_cond: chose_cond,SET1:SET1,SET2:SET2,SET3:SET3}, function(result){
                    // console.log(result);

                    try{
                        result = JSON.parse(result);
                        console.log(result);
                        if(result[0]['numrow']  <= 0){
                        $('#show_chart').hide();
                        $('#show_error').hide();
                        $('#show_nodata').show();
                        $('#show_loading').hide();
                        }else{
                            $('#show_chart').show();
                            $('#show_error').hide();
                            $('#show_nodata').hide();
                            $('#show_loading').hide();
                        }
                    }catch{
                        $('#show_chart').hide();
                        $('#show_error').show();
                        $('#show_nodata').hide();
                        $('#show_loading').hide();
                    }
                    
                    labelChart1 = Array();
                    dataChart1 = Array();
                    round = $("#order").val();
                    if(round > result[0]['numrow'] || chose_cond == ""){
                        round = result[0]['numrow'];
                    }

                    for(i=1;i<=round;i++){
                        labelChart1[i-1] = result[i]['label1'];
                        dataChart1[i-1] = result[i]['data'];
                    }

                    //data for chart
                    html = "";
                    // labelChart1 = ['แปลง1', 'แปลง2', 'แปลง3','แปลง4','แปลง5', 'อื่นๆ'];
                    // dataChart1 = [20, 12, 10, 8, 5, 30];
                    unit1 = result[0]['unit'];
                    color1 = Array();
                    colorBorder1 = Array();
                    for(i=0;i<dataChart1.length;i++){
                        // var randomColor = Math.floor(Math.random()*16777215).toString(16);
                        var r = Math.floor(Math.random() * 256);
                        var g = Math.floor(Math.random() * 256);
                        var b = Math.floor(Math.random() * 256);
                        var randomColor = "rgba("+r + ", " + g + ", " + b;
                        if(checkDup(color1,randomColor)){
                            color1[i] = randomColor+",0.6)";
                            colorBorder1[i] = randomColor+",0.8)"; 
                        }
                    }
                    console.log(color1);
                    console.log("present = "+present);

                    //data for chart
                    fillChart = false; 
                    if(present == "area" || present == "multi_area" || present == "chart_radar"){
                        console.log("fill");
                        fillChart = true;
                    }
                    var ctx = $('#chartjs');
                    if(present == "pie" || present == "bar" || present == "line" || present == "area"){
                        if(present == "area") typeChart = "line";
                        else typeChart = present;
                        myChart = new Chart(ctx, {
                            type: typeChart,
                            data: {
                                labels: labelChart1,
                                datasets: [{
                                    label: chose_type,
                                    data: dataChart1,
                                    fill: fillChart,
                                    backgroundColor: color1,
                                    borderColor : colorBorder1
                                }]
                            }
                        });
                    }else{            
                        if(present == "multi_area"){
                            typeChart = "line";
                        }else{
                            t = present.split("_");
                            typeChart = t[1];
                            console.log("typeChart = "+typeChart);
                        }
                        
                        if(present == "complex_bar"){
                            optionChart = {
                                scales: {
                                    xAxes: [{
                                        stacked: true,
                                    }],
                                    yAxes: [{
                                        stacked: true
                                    }]
                                }
                            };
                        }else{
                            optionChart = "";
                        }
                        //data for chart
                        labelChart1 = Array();//
                        labelChart2 = Array();
                        dataInChart = Array();
                        arrInData = Array();

                        for(i=1;i<=round;i++){
                            labelChart2[i-1] = result[i]['label1']; //year/month/day
                            labelChart1[i-1] = result[i]['label2']; 
                        }
                        labelChart1 = unique(labelChart1);
                        labelChart2 = unique(labelChart2);

                        console.log("labelChart1");
                        console.log(labelChart1);
                        console.log("labelChart2");
                        console.log(labelChart2);

                        for(i=0;i<labelChart1.length;i++){
                            arrInData[labelChart1[i]] = Array();
                        }

                        for(i=0;i<labelChart1.length;i++){
                            for(j=0;j<labelChart2.length;j++){
                                arrInData[labelChart1[i]][labelChart2[j]] = "0";
                            }
                        }
                        // console.log("arrInData");
                        // console.log(arrInData);
                        for(i=1;i<=round;i++){
                            arrInData[result[i]['label2']][result[i]['label1']] = result[i]['data'];
                        }
                        for(i=0;i<labelChart1.length;i++){
                            arrToData = Array();
                            for(j=0;j<labelChart2.length;j++){
                                arrToData.push(arrInData[labelChart1[i]][labelChart2[j]]);
                            }
                            dataInChart.push(arrToData);
                        }
                        console.log("arrInData");
                        console.log(arrInData);
                        console.log("dataInChart");
                        console.log(dataInChart);


                        // labelChart1 = ['แปลง1', 'แปลง2', 'แปลง3','แปลง4','แปลง5', 'อื่นๆ'];
                        // month = ["มกราคม","พฤษภาคม","อื่นๆ"]; //
                        // dataInChart = [[5, 3, 2, 6, 6, 8],[3, 2, 1, 1, 2, 6],[6, 0, 2, 1, 3, 0]];
                        // labelChart1 = ['บจ.', 'สวน1'];
                        // month = ["2","11","13","18","26"]; //
                        // dataInChart = [[1,2,1,1,0],[0,0,0,0,1]];
                        // labelChart2 = month; //
                        unit2 = result[0]['unit'];
                        color2 = Array();
                        colorBorder2 = Array();

                        for(i=0;i<labelChart2.length;i++){
                            // var randomColor = Math.floor(Math.random()*16777215).toString(16);
                            var r = Math.floor(Math.random() * 256);
                            var g = Math.floor(Math.random() * 256);
                            var b = Math.floor(Math.random() * 256);
                            var randomColor = "rgba("+r + ", " + g + ", " + b;
                            if(checkDup(color2,randomColor)){
                                if(present == "rader"){
                                    color2[i] = randomColor+",0.1)";
                                }else{
                                    color2[i] = randomColor+",0.6)";
                                }
                                colorBorder2[i] = randomColor+",0.8)"; 
                            }
                        }                
                        console.log(color2);
                        dataChart2 = [];
                        for(i=0;i<labelChart1.length;i++){
                            dataChart2[i] = {
                                label: labelChart1[i],
                                data: dataInChart[i],
                                fill: fillChart,
                                backgroundColor: color2[i],
                                borderColor : colorBorder2[i]
                            };
                        }
                        // data for chart
                        console.log("optionChart = "+optionChart);
                        myChart = new Chart(ctx, {
                            type: typeChart,
                            data: {
                                labels: labelChart2,
                                datasets: dataChart2
                            },
                            options: optionChart
                        });
                    }

                    if(present == "pie" ||  present == "line" || present == "bar"){
                        html = `<tr>
                        <th>ลำดับ</th>
                        <th>${label1}</th>
                        <th>${data1}${cal1} (${unit1}) </th>
                        </tr>`;
                        for(i=1;i<=round;i++){
                            html+=`<tr>
                            <td align="right">${i}</td>
                            <td>${result[i]['label1']}</td>
                            <td align="right">${result[i]['data']}</td>
                            </tr>`;
                        }
                    }else{
                        html = `<tr>
                        <th>ลำดับ</th>
                        <th>${label1}</th>
                        <th>${label2}</th>
                        <th>${data1}${cal1} (${unit1}) </th>
                        </tr>`;
                        for(i=1;i<=round;i++){
                            html+=`<tr>
                            <td align="right">${i}</td>
                            <td>${result[i]['label1']}</td>
                            <td>${result[i]['label2']}</td>
                            <td align="right">${result[i]['data']}</td>
                            </tr>`;
                        }
                    }

                    $('#dataTable').html(html);
                });
            }else{
                $('#show_chart').hide();
            }
        }else{
            $('#show_chart').hide();
        } 
        


    });
    
});
function unique(list) {
    var result = [];
    $.each(list, function(i, e) {
      if ($.inArray(e, result) == -1) result.push(e);
    });
    return result;
  }
function monthToNumber(month){
    monthArr = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
    for(i=0;i<monthArr.length;i++){
        if(monthArr[i] == month){
            return i+1;
        }
    }  
    return 0;
}
function checkDup(arr,dt){
    for(i=0;i<arr.length;i++){
        if(arr[i] == dt){
            return false;
        }
    }
    return true;
}
function getArrayMany(id_list){
    var children = $(id_list).children();
    var Array_Data = [];
    var currentChild;
    for (var i = 0; i < children.length; i++) {
        currentChild = children.eq(i);
        Array_Data[i+1] = currentChild.attr('Name');
    }
    return Array_Data;
}
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
function hide1_time(){
    $(".oneyear").hide();
    $(".onemonth").hide();
    $(".oneday").hide();
}
function hide2_time(){
    $(".onemonth").hide();
    $(".oneday").hide();
}
function updateData() {

    swal({
            title: "กำลังโหลดข้อมูล...",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary",
            closeOnConfirm: false,
        },
        function(isConfirm) {
            if (isConfirm) {
                // system();
                $("#loading").show();
                $("#update").hide();
                    $('[data-toggle=tooltip]').tooltip({
                        boundary: 'window',
                        trigger: 'hover'
                    });
                    return true;
                
            } else {
    
            }
        });
    
}