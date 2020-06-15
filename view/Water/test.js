$(document).ready(function() {
    $('.show1').show();
    $('.show2').hide();
    $(document).on("click", "#btn-modal1", function() {
        $("#modal-1").modal('show');
    });
    $(document).on("click", "#btn-modal2", function() {
        $("#modal-2").modal('show');
    });
    $(document).on('click', '#btn-submitRain', function() {
        var date = $("#formAddRain input[name='dateRain']");
        var timeStrat = $("#formAddRain input[name='timeStratRian']");
        var timeEnd = $("#formAddRain input[name='timeEndRian']");
        var Type = $("#formAddRain input[name='Type']:checked");
        var rank = $("#formAddRain select[name='rankRain']");
        var Vol = $("#formAddRain input[name='rainVol']");
        //console.log("date" + date.val() + "\nFarmID" + FarmID.val() + "\nsubFarmID" + subFarmID.val() + "\ntimeStrat" + timeStrat.val() + "\ntimeEnd" + timeEnd.val() + "\nType" + Type.val() + "\nrank" + rank.val() + "\nVol" + Vol.val())
        let dataNull = [date];
        let dataNull2 = [timeStrat, timeEnd];
        if (Type.val() == 1) {
            let dataSelectNull2 = [rank];
            if (!checkNull(dataNull)) return;
            if (!checkNull(dataNull2)) return;
            Vol[0].setCustomValidity('');
            if (!checkSelectNull(dataSelectNull2)) return;
        } else {
            let dataNumNull = [Vol];
            if (!checkNull(dataNull)) return;
            if (!checkNull(dataNull2)) return;
            rank[0].setCustomValidity('');
            if (!checkNumNull(dataNumNull)) return;
        }
        if (timeStrat.val() >= timeEnd.val()) {
            timeStrat[0].setCustomValidity('กรุณรากรอกช่วงเวลาให้ถูกต้อง');
            return;
        } else {
            timeStrat[0].setCustomValidity('');
        }
    });
    $(document).on('click', '.radioType', function() {


        let Type = $(this).val();

        if (Type == 1) {
            $('.show1').show();
            $('.show2').hide();
            $("#rainVol").val("0");
        } else {
            $('.show2').show();
            $('.show1').hide();
            $("#rankRain").val("0");
        }

    })
    $(document).on('click', '#btn-submitWater', function() {
        var date = $("#formAddWater input[name='dateWater']");
        var timeStrat = $("#formAddWater input[name='timeStratWater']");
        var timeEnd = $("#formAddWater input[name='timeEndWater']");
        var Vol = $("#formAddWater input[name='waterVol']");
        let dataNull = [date];
        let dataNull2 = [timeStrat, timeEnd];
        let dataNumNull = [Vol];
        if (!checkNull(dataNull)) return;
        if (!checkNull(dataNull2)) return;
        if (!checkNumNull(dataNumNull)) return;
        if (timeStrat.val() >= timeEnd.val()) {
            timeStrat[0].setCustomValidity('กรุณรากรอกช่วงเวลาให้ถูกต้อง');
            return;
        } else {
            timeStrat[0].setCustomValidity('');
        }
    });

    function checkSelectNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == null || selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('กรุณาเลือกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == '') {
                selecter[i][0].setCustomValidity('กรุณากรอกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkNumNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('ห้ามเป็น 0');
                return false;
            } else {
                selecter[i][0].setCustomValidity('');
            }
        }

        return true;
    }
});