   <!-- modal add -->
   <div class="modal fade" id="insert" tabindex="-1" role="dialog">
       <div class="modal-dialog" role="document">
           <div class="modal-content body-insert">
               <!-- header-------------------------------------------------- -->
               <div class="modal-header header-modal">
                   <h4 class="modal-title" id="largeModalLabel" style="color:white">เพิ่มธาตุอาหาร</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <!-- start body----------------------------------------- -->
               <div class="modal-body ">
                   <!-- start form----------------------------------------- -->
                   <form action="#" method="post" enctype="multipart/form-data" id="form-insert">
                       <!-- .insert-collap -->
                       <div class="divName">
                           <div class="form-group">
                               <div class="form-inline">
                                   <label for="" class="col-4">ชื่อธาตุอาหาร <span class="ml-2"> *</span></label>
                                   <input id='name' name='name_insert' class='form-control col-8' required="" oninput="setCustomValidity(' ')" placeholder="ใส่ชื่อธาตุอาหาร">
                               </div>
                           </div>
                           <div class="form-group">
                               <div class="form-inline">
                                   <label for="" class="col-4">ประเภท <span class="ml-2"> *</span></label>
                                   <div class="form-check col-4">
                                       <input class="form-check-input" type="radio" name="Type" id="insertType1" checked value="ธาตุอาหารหลัก">
                                       <label class="form-check-label" for="Type1">
                                           ธาตุอาหารหลัก
                                       </label>
                                   </div>
                                   <div class=" form-check col-4">
                                       <input class="form-check-input" type="radio" name="Type" id="insertType2" value="ธาตุอาหารรอง">
                                       <label class="form-check-label" for="Type2">
                                           ธาตุอาหารรอง
                                       </label>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <input type="hidden" id="hidden_id" name="request" value="insert" />
               </div>
               <!-- end  body---------------------------------------------- -->
               <div class="modal-footer footer-insert">
                   <div class="buttonSubmit">
                       <button type="submit" class="btn btn-success waves-effect insertSubmit" id="add-data">ยืนยัน</button>
                       <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">ยกเลิก</button>
                   </div>
               </div>
               </form>
               <!-- end form---------------------------------------- -->
           </div>
           <!-- end content -->
       </div>
       <!-- end dialog -->
   </div>
   <!-- end fade -->

   <!-- 

    <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
            </div>
            <div class="modal-body">
                <div id="upload-demo" class="center-block"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div> -->