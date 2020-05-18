<?php
session_start();
// include_once("../../dbConnect.php");
// require_once("../../set-log-login.php");
require_once("./getData.php");
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Chat";

?>

<?php include_once("../layout/LayoutHeader.php"); ?>

<style>
	#card-detail {
		color: white;
		background-color: #F44336;
	}

	#calendar {}

	/* input[type="checkbox"]{
		position: absolute;
		right: 9000px;
	} */
	input[type=checkbox] {
		background-color: #F44336;
		color: #F44336;
	}

	.sortable {
		list-style-type: none;
		list-style-position: inside;
		margin: 0px 12px 8px 0px;
		width: 80%;
		padding: 2px;
		border-width: 1px;
		border-style: solid;
		min-height: 100px;
	}

	.sortable li {
		margin: 3px 3px 3px 3px;
		font-size: 1em;
		height: 18px;
		padding-bottom: 30px;
		padding-left: 10px;
		border: 2px dashed #d3d3d3;
		background-color: #eee;
		cursor: pointer;
	}
</style>


<body onload="hiddenn('0')">
	<div class="container">
		<div>

			<div class="row">
				<div class="col-xl-12 col-12 mb-4">
					<div class="card">
						<div class="card-header card-bg">
							<div class="row">
								<div class="col-12">
									<span class="link-active font-weight-bold" style="color:<?= $color ?>;">ระบบแจ้งเตือน</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--div class="row mt-4"-->

			<div class="row">
				<div class="col-xl-12 col-12">
					<div class="card">
						<div class="card-header card-bg">
							<div>
								<span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายละเอียดการส่งแจ้งเตือน</span>
							</div>
						</div>
						<div class="card-body card-bg" style="overflow-x:scroll;">

							<div class="row mb-4">
								<div class="col-xl-3 col-12 text-right">
									<span>กลุ่มผู้รับข้อมูล</span>
								</div>
								<div class="col-xl-3 col-12">
									<select class="form-control" id="level" name="level" required>
										<option value='1'>ทั้งหมด</option>
										<option value='2'>เกษตรกร</option>
										<option value='3'>จังหวัด</option>
									</select>
								</div>
							</div>
							<div class="row mb-4" id="Infolevel2" style="display: none">
								<div class="col-xl-3 col-12 text-right">
									<span>เกษตรกร</span>
								</div>
								<div class="col-xl-4 ">
									รายชื่อเกษตรกร
									<br>
									<ul class="list1 sortable" id="list1">
										<?php
										$ArrayInfoFarmer = getAllFarmer();
										for ($i = 1; $i <= count($ArrayInfoFarmer); $i++) {
											echo "<li NameF='{$ArrayInfoFarmer[$i]['FirstName']} {$ArrayInfoFarmer[$i]['LastName']}'>$i) {$ArrayInfoFarmer[$i]['FirstName']} {$ArrayInfoFarmer[$i]['LastName']}</li>";
										}
										?>
									</ul>
								</div>
								<div class="col-xl-4 ">
									รายชื่อเกษตรกรที่ต้องการส่งข้อความ
									<br>
									<ul class="list2 sortable" id="list2">

									</ul>
								</div>
							</div>
							<div class="row mb-4" id="Infolevel3" style="display: none">
								<div class="col-xl-3 col-12 text-right">
									<span>จังหวัด</span>
								</div>
								<div class="col-xl-3 ">
									<select class="form-control" id="province" name="province" required>"
										<?php
										$ArrayInfoProvince = getAllProvince();
										for ($i = 1; $i <= count($ArrayInfoProvince); $i++) {
											echo "<option value='{$ArrayInfoProvince[$i]['Province']}'>{$ArrayInfoProvince[$i]['Province']}</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-xl-3 col-12 text-right">
									<span>เลือกหัวข้อที่ต้องการส่ง</span>
								</div>
								<div class="col-xl-9 col-12">
									<label class="radio-inline">
										<input type="radio" name="optradio" value="แจ้งเตือน" checked> แจ้งแตือน
									</label> &nbsp;&nbsp;&nbsp;&nbsp;
									<label class="radio-inline">
										<input type="radio" name="optradio" value="แจ้งให้ทราบ"> แจ้งให้ทราบ
									</label>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-xl-3 col-12 text-right">
									<span>เนื้อหาการแจ้งเตื่อน</span>
								</div>

								<div class="col-xl-9 col-12">
									<label class="radio-inline">
										<input type="radio" name="optradio2" value="สวนขาดน้ำ" onclick="hiddenn('0')" checked> สวนขาดน้ำ
									</label> &nbsp;&nbsp;&nbsp;
									<label class="radio-inline">
										<input type="radio" name="optradio2" value="ฝนไม่ตกมาหลายวัน" onclick="hiddenn('0')"> ฝนไม่ตกมาหลายวัน
									</label> &nbsp;&nbsp;&nbsp;
									<label class="radio-inline">
										<input type="radio" name="optradio2" value="มีศัตรูพืชในบริเวณข้างเคียง" onclick="hiddenn('0')"> มีศัตรูพืชในบริเวณข้างเคียง
									</label> &nbsp;&nbsp;&nbsp;
									<label class="radio-inline">
										<input type="radio" name="optradio2" value="other" onclick="hiddenn('1')" /> อื่นๆ
									</label>
								</div>
							</div>

							<div class="row mb-4">
								<div class="col-xl-3 col-12 text-right">
									<span id="txt1">ข้อความ
								</div>
								<div class="col-xl-9 col-12">
									<input class="form-control" type="text" name="textother" id="txt2" />
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-success btn-md" style="float:right;" id="submitSend">ส่งข้อความ</button>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!--  -->
		</div>
	</div>
</body>

<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="./Chat.js"></script>