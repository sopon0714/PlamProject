<div class="row">
    <div class="col-xl-12 col-12 mb-4">
        <div class="card">
            <div class="card-header card-bg">
                <div class="row">
                    <div class="col-12">
                        <span class="link-active head-link">รายการธาตุอาหาร</span>
                        <span style="float:right;">
                            <i class="fas fa-bookmark"></i>
                            <a class="link-path" href="#">หน้าแรก</a>
                            <span> > </span>
                            <a class="link-path link-active head-link" href="#">รายการธาตุอาหาร</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php
    creatCard("card-color-one",   "ธาตุอาหารหลัก", getCountNutrientM() . " ชนิด", "waves");
    creatCard("card-color-three",   "ธาตุอาหารรอง", getCountNutrientS() . " ชนิด", "waves");
    ?>
    <div class="col-xl-3 col-12 mb-4">
        <div class="card border-left-primary card-color-four shadow  py-2" id="addFertilizer" style="cursor:pointer;">
            <div class="card-body">
                <div class="row no-gutters align-items-center" role="button" id="edit1" data-toggle="modal" data-target="#insert" aria-haspopup="true" aria-expanded="false">
                    <div class="col mr-2">
                        <div class="font-weight-bold  text-uppercase mb-1">เพิ่มธาตุอาหาร</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">+1 ชนิด</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus-square fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>