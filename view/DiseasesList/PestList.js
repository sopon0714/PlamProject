$(document).ready(function() {
    let DATA_DB;
    pullData();

    function pullData() {
        $.ajax({
            type: "POST",
            datatype: 'application/json',
            url: "manage.php",
            data: {
                request: 'select'
            },
            async: false,
            success: function(result) {
                DATA_DB = JSON.parse(result);
                console.log(DATA_DB);
            }
        });
    }

    // insert submit
    $('#save').click(function() {
        // alert('55555555')
        let name = $("input[name='name_insert']");
        let alias = $("input[name='alias_insert']");
        let styleChar = $("input[name='charactor_insert']");
        let styleDanger = $("input[name='danger_insert']");

        let pic_sc = new Array()
        let pic_photo = new Array()
        let pic_logo
        pic_logo = $('#img-pic-logo').attr('src')
        $('.pic-SC').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('.pic-photo').each(function(i, obj) {
            pic_photo.push($(this).attr('src') + 'manu20')
        });
        $('#pic1').val(pic_logo);
        $('#pic2').val(pic_sc);
        $('#pic3').val(pic_photo);

        var PIC_SC = $(".pic-SC");
        var PIC_PHOTO = $(".pic-photo");

        // console.log(PIC_SC.length);

        if(PIC_PHOTO.length == 0){
            // console.log('PIC_PHOTO.length == 0');
            $("#p_photo").attr("required","");
            $("#p_photo")[0].setCustomValidity('กรุณาเพิ่มรูปการทำลาย');

        }else{
            $("#p_photo").removeAttr("required");
            $("#p_photo")[0].setCustomValidity('');

        }
        if(PIC_SC.length == 0){
            // console.log('PIC_SC.length == 0');
            $("#pic-style-char").attr("required","");
            $("#pic-style-char")[0].setCustomValidity('กรุณาเพิ่มรูปลักษณะ');

        }else{
            $("#pic-style-char").removeAttr("required");
            $("#pic-style-char")[0].setCustomValidity('');
        }

        if($('#img-pic-logo').attr('src') == 'https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg'){
            // console.log('img-pic-logo not change');
            $("#pic-logo").attr("required","");
            $("#pic-logo")[0].setCustomValidity('กรุณาเพิ่มรูป');
        }else{
            $("#pic-logo").removeAttr("required");
            $("#pic-logo")[0].setCustomValidity('');
        }

        
        if (!checkSameName(name)) return;
        if (!checkSameAlias(alias)) return;

    });

    function checkSameName(name) { // check same name
        for (i in DATA_DB) {
            console.log(DATA_DB[i].Name);
            if (name.val().trim() == DATA_DB[i].Name) {
                name[0].setCustomValidity('ชื่อทางการนี้ซ้ำ');
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;
    }
    function checkSameAlias(alias) { // check same alias
        for (i in DATA_DB) {
            // console.log(DATA_DB[i].Name);
            if (alias.val().trim() == DATA_DB[i].Alias) {
                alias[0].setCustomValidity('ชื่อนี้ซ้ำ');
                return false;
            } else {
                alias[0].setCustomValidity('');
            }
        }
        return true;
    }

    function setImgEdit(pid, num, footer, type) {
        // console.log('setIMG');
        $.post("manage.php", {request: type,pid: pid}, function(result){
            // console.log(result);
            string = result;
            string = string.replace(/^\s*|\s*$/g,'');
            // console.log(string);
            // console.log(typeof string);
            var arr = string.split(',');

            console.log(arr);
            // console.log('arr[0]'+arr[0]);

            var textPicChar = ''
            if(type == 'style'){
                var cl = 'pic-SC-edit';
            }else if(type == 'danger'){
                var cl = 'pic-photo-edit';
            }
            // console.log("num" + num)
    
            for (i = 0;i < num ;i++) {
                textPicChar += `<div class="card" width="70px" hight="70px">
                                    <div class="card-body" style="padding:0;">
             `;
                let path;
                // console.log('arr[i] = '+arr[i]);
        
                path = `../../picture/pest/disease/${type}/${pid}/${arr[i]}`
                
                // console.log('path = '+path);
                textPicChar += `<img class="${cl}" src = "${path}" id="img-${(+new Date)}" width="100%" hight="100%" />`
                textPicChar += `</div>
                <div class="card-footer">
                    <button  type="button" class="btn btn-warning edit-img-edit">แก้ไข</button>
                    <button  type="button" class="btn btn-danger delete-img">ลบ</button>
                </div>
            </div>`
            }
            textPicChar += footer
            if(type == 'style'){
                $('#grid-pic-style-char-edit').html(textPicChar);
            }else if (type == 'danger'){
                $('#grid-p_photo-edit').html(textPicChar);

                $('#p_photo-edit').on('change', function() {
                    // alert('change')
                    imagesPreview(this, '#grid-p_photo-edit', 'p_photo-edit', 'pic-photo-edit','edit-img-edit');
                });
        
                $('#pic-style-char-edit').on('change', function() {
                    // alert('change')
                    imagesPreview(this, '#grid-pic-style-char-edit', 'pic-style-char-edit', 'pic-SC-edit','edit-img-edit');
                });
        
                $(document).on('click', '.edit-img-edit', function() {
                    let url = $(this).parent().prev().children().attr('src')
                    // console.log('url = '.url);
                    idImg = $(this).parent().prev().children().attr('id')
                    cropImgEdit(url, 'square')
                })
                $('.crop-img-edit').hide()
                $('.crop-button-edit').hide()
            
                function cropImgEdit(url, type) {
                    // console.log('url = '+url);
                    // console.log('type = '+type);
                    // console.log('crop-img-edit');
                    $('.main-edit').hide()
                    $('.normal-button-edit').hide()
                    $('.crop-img-edit').show()
                    $('.crop-button-edit').show()
            
                    let UC = $('.upload-demo2-edit').croppie({
                        viewport: {
                            width: 200,
                            height: 200,
                            type: type
                        },
                        enforceBoundary: false,
                        enableExif: true
                    });
                    UC.croppie('bind', {
                        url: url
                    }).then(function() {
                        // console.log('jQuery bind edit complete');
                    });
                }
        
                $(document).on('click', '.btn-crop-edit', function(ev) {
                    submitCrop(idImg)
                });
            
                $(document).on('click', '.btn-cancel-crop-edit', function() {
                    // console.log('btn-cancel-crop-edit');
                    $('.main-edit').toggle()
                    $('.normal-button-edit').toggle()
                    $('.crop-img-edit').toggle()
                    $('.crop-button-edit').toggle()
                    $('.upload-demo2-edit').croppie('destroy')
                })
            }

        });

    }

    $('.btn_edit').click(function() {
        $("#editModal").modal();
        var pid = $(this).attr('pid');
        var name = $(this).attr('name');
        var alias = $(this).attr('alias');
        var charstyle = $(this).attr('charstyle');
        var danger = $(this).attr('dangerstyle');
        var numPicChar = $(this).attr('numPicChar')
        var numPicDanger = $(this).attr('numPicDanger')
        var icon = $(this).attr('data-icon')
        var footer;

        // console.log("icon = " + icon)

        $('#img-pic-logo-edit').attr('src', "../../icon/pest/" + pid + "/" + icon)
        footer = `<div class="img-reletive">

        <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
        <input type="file" id="pic-style-char-edit" name="picstyle_insert-edit[]" accept=".jpg,.png" multiple>
    </div>`
        setImgEdit(pid, numPicChar, footer, "style");

        footer = `<div class="img-reletive">
        <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
        <input type="file" class="form-control" id="p_photo-edit" name="p_photo-edit[]" accept=".jpg,.png" multiple>
    </div>`
        setImgEdit(pid, numPicDanger, footer, "danger");
        
        $('#e_name').val(name);
        $('#e_alias').val(alias);
        $('#e_charactor').text(charstyle);
        $('#e_danger').text(danger);

        // console.log("pid = " + pid)

        $('#e_pid').val(pid);
        // console.log("pid = " + $('#e_pid').val())

        $('#e_o_name').val(name);
        $('#e_o_alias').val(alias);
        $('#e_o_charcator').text(charstyle);
        $('#e_o_danger').text(danger);

        $('#pic-logo-edit').on('change', function() {
            alert('pic-logo-edit');
            cropImageEdit(this)
            idImg = 'img-pic-logo-edit'
    
        });
        function cropImageEdit(input) {
            // console.log('crop-image');
            let rawImg
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    let rawImg = e.target.result;
                    cropImgEdit(rawImg, 'circle');
                }
                reader.readAsDataURL(input.files[0]);
            }
            $(input).val('')
        }

    });

    $('#edit').click(function() {
        // console.log("edit");

        let name = $("input[name = 'e_name']");
        let alias = $("input[name = 'e_alias']");
        let charstyle = $("input[name = 'e_charactor']");
        let danger = $("input[name = 'e_danger']");
        let pid = $("input[name = 'e_pid']");

        let o_name = $("input[name = 'e_o_name']");
        let o_alias = $("input[name = 'e_o_alias']");
        let o_charstyle = $("input[name = 'e_o_charcator']");
        let o_danger = $("input[name = 'e_o_danger']");

        let pic_sc = new Array()
        let pic_photo = new Array()
        let pic_logo
        pic_logo = $('#img-pic-logo-edit').attr('src')
        $('.pic-SC-edit').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('.pic-photo-edit').each(function(i, obj) {
            pic_photo.push($(this).attr('src') + 'manu20')
        });
        $('#e_pic1').val(pic_logo);
        $('#e_pic2').val(pic_sc);
        $('#e_pic3').val(pic_photo);

        var PIC_SC_EDIT = $(".pic-SC-edit");
        var PIC_PHOTO_EDIT = $(".pic-photo-edit");

        // console.log(PIC_SC_EDIT.length);

        if(PIC_PHOTO_EDIT.length == 0){
            // console.log('PIC_PHOTO_EDIT.length == 0');
            $("#p_photo-edit").attr("required","");
            $("#p_photo-edit")[0].setCustomValidity('กรุณาเพิ่มรูปการทำลาย');

        }else{
            $("#p_photo-edit").removeAttr("required");
            $("#p_photo-edit")[0].setCustomValidity('');

        }
        if(PIC_SC_EDIT.length == 0){
            // console.log('PIC_SC_EDIT.length == 0');
            $("#pic-style-char-edit").attr("required","");
            $("#pic-style-char-edit")[0].setCustomValidity('กรุณาเพิ่มรูปลักษณะ');

        }else{
            $("#pic-style-char-edit").removeAttr("required");
            $("#pic-style-char-edit")[0].setCustomValidity('');
        }

        if($('#img-pic-logo-edit').attr('src') == 'https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg'){
            // console.log('img-pic-logo-edit not change');
            $("#pic-logo-edit").attr("required","");
            $("#pic-logo-edit")[0].setCustomValidity('กรุณาเพิ่มรูป');
        }else{
            $("#pic-logo-edit").removeAttr("required");
            $("#pic-logo-edit")[0].setCustomValidity('');
        }
        // console.log('name pid = '+name.val());
        // console.log('edit pid = '+pid.val());
        if (!check_duplicate(o_name, o_alias, o_charstyle, o_danger, name, alias, charstyle, danger)) return;
        if (!checkSameNameEdit(name,pid)) return;
        if (!checkSameAliasEdit(alias,pid)) return;

    })

    function check_duplicate(o_name, o_alias, o_charstyle, o_danger, name, alias, charstyle, danger) {
        if (o_name == name && o_alias == alias && o_charstyle == charstyle && o_danger == danger) {
            return false;
        }
        return true;
    }

    function checkSameNameEdit(name, id) { // check same name
        for (i in DATA_DB) {
            // console.log(DATA_DB[i].Name);
            if (name.val().trim() == DATA_DB[i].Name && DATA_DB[i].PID != id.val()) {
                name[0].setCustomValidity('ชื่อทางการนี้ซ้ำ');
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;
    }
    function checkSameAliasEdit(alias, id) { // check same alias
        for (i in DATA_DB) {
            // console.log(DATA_DB[i].Name);
            if (alias.val().trim() == DATA_DB[i].Alias && DATA_DB[i].PID != id.val()) {
                alias[0].setCustomValidity('ชื่อนี้ซ้ำ');
                return false;
            } else {
                alias[0].setCustomValidity('');
            }
        }
        return true;
    }

    // Configure/customize these variables.
    var showChar = 180; // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more";
    var lesstext = "Show less";

    $('.more').each(function() {
        var content = $(this).html();
        content = content.trim();
        content += ' ';

        for(i = showChar ; i<content.length; i++){
            if(content[i] == ' '){
                showChar = i;
                break;
            }
        }
        
        if (content.length-1 > showChar) {
            
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h +
                '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function() {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;

    });

    $('#addInsect').click(function() {
        // console.log('fffff')
        // $('.Modal').append(addModal);
        id =  $('#addModal').attr("class");
        console.log(id);
        $('#addModal').modal();
    });
});

$(document).on('click', '.delete', function() {
    delfunction($(this).attr('data-pid'), $(this).attr('data-alias'))
})

function delfunction(_sid, _alias) {
    // alert(_did);
    swal({
            title: "คุณต้องการลบ",
            text: `${_alias} หรือไม่ ?`,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
            closeOnConfirm: false,
            closeOnCancel: function() {
                $('[data-toggle=tooltip]').tooltip({
                    boundary: 'window',
                    trigger: 'hover'
                });
                return true;
            }
        },
        function(isConfirm) {
            if (isConfirm) {
                // console.log(1)
                swal({
                    title: "ลบข้อมูลสำเร็จ",
                    type: "success",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "ตกลง",
                    closeOnConfirm: false,
                }, function(isConfirm) {
                    if (isConfirm) {
                        delete_1(_sid)
                    }
                });
            } else {
            }
        });
}

function delete_1(_sid) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = 'DiseasesList.php';
            // alert(this.responseText);
        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`request=delete&pid=${_sid}`);

}