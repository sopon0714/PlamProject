<script>
    // console.log('crop');
    let idImg;

    // console.log($('#pic-style-char').val());

    $('#pic-logo').on('change', function() {
        cropImage(this)
        idImg = 'img-pic-logo'
 
    })
    $('#p_photo').on('change', function() {
        // alert('change')
        imagesPreview(this, '#grid-p_photo', 'p_photo', 'pic-photo', 'edit-img');
    });

    $('#pic-style-char').on('change', function() {
        // alert('change')
        imagesPreview(this, '#grid-pic-style-char', 'pic-style-char', 'pic-SC', 'edit-img');

    });

    $(document).on('click', '.delete-img', function() {
        $(this).parent().parent().remove()
    })

    $('.crop-img').hide()
    $('.crop-button').hide()

    $(document).on('click', '.edit-img', function() {
        let url = $(this).parent().prev().children().attr('src')
        idImg = $(this).parent().prev().children().attr('id')
        cropImg(url, 'square')
    })

    $(document).on('click', '.btn-crop', function(ev) {
        console.log('submit crop');
        console.log(idImg);
        console.log('RESULT:', $('#' + idImg).attr('src'))

        submitCrop(idImg)
    });

    $(document).on('click', '.btn-crop-edit', function(ev) {
        console.log('submit crop');

        console.log(idImg);
        submitCropEdit(idImg)

        // toDataURL($('#' + idImg).attr('src'), function(dataUrl) {
        //     // console.log('RESULT:', dataUrl)
        //     $('#' + idImg).attr('src',dataUrl);
        //     console.log('RESULT:', $('#' + idImg).attr('src'))

        //     submitCropEdit(idImg)

        // })

        
    });
    
    $(document).on('click', '.btn-cancel-crop-edit', function() {
        // console.log('btn-cancel-crop-edit');
        $('.main-edit').toggle()
        $('.normal-button-edit').toggle()
        $('.crop-img-edit').toggle()
        $('.crop-button-edit').toggle()
        $('.upload-demo2-edit').croppie('destroy')
    })
    $(document).on('click', '.btn-cancel-crop', function() {
        $('.main').toggle()
        $('.normal-button').toggle()
        $('.crop-img').toggle()
        $('.crop-button').toggle()
        $('.upload-demo2').croppie('destroy')
    })

    function imagesPreview(input, img_prepend, first_img, className, edit, del) {
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    // let rawImg = event.target.result;
                    // cropImg(rawImg, 'square');
                    // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    $(img_prepend).prepend(`<div class="card" width="70px" hight="70px">
                                <div class="card-body" style="padding:0;">
                                    <img class="${className}" src = "${event.target.result}" id="img-${first_img+(+new Date)}" width="100%" hight="100%" />
                                </div>
                                <div class="card-footer">
                                    <button  type="button" class="btn btn-warning ${edit}">แก้ไข</button>
                                    <button  type="button" class="btn btn-danger delete-img">ลบ</button>
                                </div>
                            </div>`)
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
        $(input).val('')
    }

    function cropImage(input) {
        // console.log('crop-image');
        let rawImg
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                let rawImg = e.target.result;
                cropImg(rawImg, 'circle');
            }
            reader.readAsDataURL(input.files[0]);
        }
        $(input).val('')
    }

    function cropImg(url, type) {

        // console.log('crop-img');
        // console.log('url = '+url);
        if(type == 'circle'){
            w = 200;
            h = 200;
        }else{
            w = 300;
            h = 200;
        }

        $('.main').hide()
        $('.normal-button').hide()
        $('.crop-img').show()
        $('.crop-button').show()

        let UC = $('.upload-demo2').croppie({
            viewport: {
                width: w,
                height: h,
                type: type
            },
            enableExif: true,
            enableOrientation: true,
            enableZoom: true,
            enforceBoundary: true,
            mouseWheelZoom: true,
            showZoomer: true,
        });
        UC.croppie('bind', {
            url: url
        }).then(function() {
            // console.log('jQuery bind complete');
        });
    }
   
    function submitCrop(output) {
        $('.upload-demo2').croppie('result', {
                type: 'canvas',
                size: 'viewport'
            })
            .then(function(r) {
                $('.main').show()
                $('.normal-button').show()
                $('.crop-img').hide()
                $('.crop-button').hide()
                $('#' + output).attr('src', r)
            });
        $('.upload-demo2').croppie('destroy')

    }
    function submitCropEdit(output) {
        // toDataURL($('#' + output).attr('src'), function(dataUrl) {
        //     console.log('RESULT:', dataUrl)
        //     $('#' + output).attr('src',dataUrl);
            $('.upload-demo2-edit').croppie('result', {
                type: 'canvas',
                size: 'viewport'
            })
            .then(function(r) {
                $('.main-edit').show()
                $('.normal-button-edit').show()
                $('.crop-img-edit').hide()
                $('.crop-button-edit').hide()
                $('#' + output).attr('src', r)
            });
        $('.upload-demo2-edit').croppie('destroy')

        // })

    }
    function toDataURL(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
            var reader = new FileReader();
            reader.onloadend = function() {
            callback(reader.result);
            }
            reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }
    
</script>