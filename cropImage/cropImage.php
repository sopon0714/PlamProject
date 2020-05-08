<script>
    let idImg;
    $('#pic-logo').on('change', function() {
        cropImage(this)
        idImg = 'img-pic-logo'

    })

    $('#p_photo').on('change', function() {
        //alert('change')
        imagesPreview(this, '#grid-p_photo', 'p_photo', 'pic-photo');
    });

    $('#pic-style-char').on('change', function() {
        //alert('change')
        imagesPreview(this, '#grid-pic-style-char', 'pic-style-char', 'pic-SC');
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
        submitCrop(idImg)
    });

    $(document).on('click', '.btn-cancel-crop', function() {
        $('.main').toggle()
        $('.normal-button').toggle()
        $('.crop-img').toggle()
        $('.crop-button').toggle()
        $('.upload-demo2').croppie('destroy')
    })

    function imagesPreview(input, img_prepend, first_img, className) {
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    $(img_prepend).prepend(`<div class="card" width="70px" hight="70px">
                                <div class="card-body" style="padding:0;">
                                    <img class="${className}" src = "${event.target.result}" id="img-${first_img+(+new Date)}" width="100%" hight="100%" />
                                </div>
                                <div class="card-footer">
                                    <button  type="button" class="btn btn-warning edit-img">แก้ไข</button>
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
        $('.main').hide()
        $('.normal-button').hide()
        $('.crop-img').show()
        $('.crop-button').show()

        let UC = $('.upload-demo2').croppie({
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
            console.log('jQuery bind complete');
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
    
</script>