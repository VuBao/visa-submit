<?php
$linkMan = "index.php?com=user&act=man&p=".$curPage;
$linkSave = "index.php?com=user&act=save&p=".$curPage;
$linkChat = "index.php?com=chat&act=man";
?>
<!-- Content Header -->
<section class="content-header text-sm">
	<div class="container-fluid">
		<div class="row">
			<ol class="breadcrumb float-sm-left">
				<li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
				<li class="breadcrumb-item active">Chi tiết tài khoản ứng viên</li>
			</ol>
		</div>
	</div>
</section>

<!-- Main content -->
<section class="content">
	<form class="validation-form" novalidate method="post" action="<?=$linkSave?>" enctype="multipart/form-data">
		<div class="card-footer text-sm sticky-top">
			<button type="submit" class="btn btn-sm bg-gradient-primary" disabled><i class="far fa-save mr-2"></i>Lưu</button>
			<button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
			<a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
		</div>
		<div class="card card-primary card-outline text-sm">
			<div class="card-header">
				<h3 class="card-title"><?=($act=="edit")?"Cập nhật":"Thêm mới";?> tài khoản</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<?php if(@$item['link_facebook']) { ?> 
						<div class="form-group col-md-12">
							<label>Facebook  :</label>
							<?php if(@$item['link_facebook']) { ?> 
							<a href="<?= @$item['link_facebook'] ?>" class="font-weight-bold" target="_blank">Truy cập ngay</a>
							<?php } else { ?> 
								<a class="font-weight-bold">Chưa cập nhật</a>
							<?php } ?>
							<label class="ml-3">Sơ yếu lí lịch: </label>
							<?php if(@$item['soyeulilich']) { ?> 
								<a href="../upload/file/<?= @$item['soyeulilich'] ?>" class="font-weight-bold" target="_blank">Xem</a>
							<?php } else { ?> 
								<a class="font-weight-bold">Chưa cập nhật</a>
							<?php } ?>
							<label class="ml-3">CV: </label>
							<?php if(@$item['cv']) { ?> 
								<a href="../upload/file/<?= @$item['cv'] ?>" class="font-weight-bold" target="_blank">Xem</a>
							<?php } else { ?> 
								<a class="font-weight-bold">Chưa cập nhật</a>
							<?php } ?>
							<label class="ml-3">Chat ngay  :</label>
							<a href="<?= $linkChat.'&id='.@$item['id'] ?>" class="font-weight-bold">Chat</a>
							
						</div>
					<?php } ?>
					
				
					<div class="form-group col-md-4">
						<label for="username">Tài khoản: <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="data[username]" id="username" placeholder="Tài khoản" value="<?=@$item['username']?>" <?=($act=="edit")?'readonly':'';?> required>
					</div>
					<div class="form-group col-md-4">
						<label for="email">Email: <span class="text-danger">*</span></label>
						<input type="email" class="form-control" name="data[email]" id="email" placeholder="Email" value="<?=@$item['email']?> " <?=($act=="edit")?'readonly':'';?> required>
					</div>
					<div class="form-group col-md-4">
						<label for="ten">Họ tên: <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="data[ten]" id="ten" placeholder="Họ tên" value="<?=@$item['ten']?>" required>
					</div>
					<div class="form-group col-md-4">
						<label for="password">Mật khẩu:</label>
						<input type="password" class="form-control" name="data[password]" id="password" placeholder="Mật khẩu" <?=($act=="add")?'required':'';?>>
					</div>
					<div class="form-group col-md-4">
						<label for="confirm_password">Nhập lại mật khẩu:</label>
						<input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Nhập lại mật khẩu" <?=($act=="add")?'required':'';?>>
					</div>
					
					<div class="form-group col-md-4">
						<label for="dienthoai">Điện thoại:</label>
						<input type="text" class="form-control" name="data[dienthoai]" id="dienthoai" placeholder="Điện thoại" value="<?=@$item['dienthoai']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="gioitinh">Giới tính:</label>
						<input type="text" class="form-control" name="data[gioitinh]" id="gioitinh" placeholder="Giới tính" value="<?=@$item['gioitinh']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="ngaysinh">Ngày sinh:</label>
						<input type="text" class="form-control" name="data[ngaysinh]" id="ngaysinh" placeholder="Ngày sinh" value="<?=(@$item['ngaysinh'])?date('d/m/Y',@$item['ngaysinh']):"";?>" readonly>
					</div>
					<div class="form-group col-md-4">
						<label for="diachi">Địa chỉ:</label>
						<input type="text" class="form-control" name="data[diachi]" id="diachi" placeholder="Địa chỉ" value="<?=@$item['diachi']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="tucachcutru">Tư cách cư trú:</label>
						<input type="text" class="form-control" name="data[tucachcutru]" id="tucachcutru" placeholder="Tư cách cư trú" value="<?=@$item['tucachcutru']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="thoihanvisa">Thời hạn visa:</label>
						<input type="text" class="form-control" name="data[thoihanvisa]" id="thoihanvisa" placeholder="Thời hạn visa" value="<?=@$item['thoihanvisa']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="quoctich">Quốc tịch:</label>
						<input type="text" class="form-control" name="data[quoctich]" id="quoctich" placeholder="Quốc tịch" value="<?=@$item['quoctich']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="trinhdotiengnhat">Trình độ tiếng Nhật:</label>
						<input type="text" class="form-control" name="data[trinhdotiengnhat]" id="trinhdotiengnhat" placeholder="Trình độ tiếng Nhật" value="<?=@$item['trinhdotiengnhat']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="tinhmongmuon">Tỉnh mong muốn:</label>
						<input type="text" class="form-control" name="data[tinhmongmuon]" id="tinhmongmuon" placeholder="Tỉnh mong muốn" value="<?=@$item['tinhmongmuon']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="nganhnghemongmuon">Ngành nghề mong muốn:</label>
						<input type="text" class="form-control" name="data[nganhnghemongmuon]" id="nganhnghemongmuon" placeholder="Ngành nghề mong muốn" value="<?=@$item['nganhnghemongmuon']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="mucluongmongmuon">Mức lương mong muốn:</label>
						<input type="text" class="form-control" name="data[mucluongmongmuon]" id="mucluongmongmuon" placeholder="Mức lương mong muốn" value="<?=@$item['mucluongmongmuon']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="thoigianchuyenviec">Thời gian chuyển việc:</label>
						<input type="text" class="form-control" name="data[thoigianchuyenviec]" id="thoigianchuyenviec" placeholder="Thời gian chuyển việc" value="<?=@$item['thoigianchuyenviec']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="zalo_fb_line">Nền tảng liên hệ:</label>
						<input type="text" class="form-control" name="data[zalo_fb_line]" id="zalo_fb_line" placeholder="Nền tảng liên hệ" value="<?=@$item['zalo_fb_line']?>">
					</div>
					<div class="form-group col-md-4">
						<label for="zalo_fb_line_id">ID liên hệ:</label>
						<input type="text" class="form-control" name="data[zalo_fb_line_id]" id="zalo_fb_line_id" placeholder="ID liên hệ" value="<?=@$item['zalo_fb_line_id']?>">
					</div>
					<div class="form-group col-md-12">
						<label for="noidungmongmuon">Nội dung công việc mong muốn:</label>
						<textarea class="form-control" name="data[noidungmongmuon]" id="noidungmongmuon" rows="4"><?= htmlspecialchars_decode(@$item['noidungmongmuon']) ?></textarea>
					</div>
					<div class="form-group col-md-12">
						<label for="mongmuonkhac">Mong muốn khác:</label>
						<textarea class="form-control" name="data[mongmuonkhac]" id="mongmuonkhac" rows="3"><?= htmlspecialchars_decode(@$item['mongmuonkhac']) ?></textarea>
					</div>
					<div class="form-group col-md-12">
						<label for="gioithieubanthan">Giới thiệu bản thân:</label>
						<textarea class="form-control" name="data[gioithieubanthan]" id="gioithieubanthan" rows="5"><?= htmlspecialchars_decode(@$item['gioithieubanthan']) ?></textarea>
					</div>
					<div class="form-group col-md-12">
						<label for="kinhnghiemlamviec">Kinh nghiệm làm việc:</label>
						<textarea class="form-control" name="data[kinhnghiemlamviec]" id="kinhnghiemlamviec" rows="10"><?= htmlspecialchars_decode(@$item['kinhnghiemlamviec']) ?></textarea>
					</div>
					
					
				</div>
				<div class="form-group">
					<label for="hienthi" class="d-inline-block align-middle mb-0 mr-2">Kích hoạt:</label>
					<div class="custom-control custom-checkbox d-inline-block align-middle">
						<input type="checkbox" class="custom-control-input hienthi-checkbox" name="data[hienthi]" id="hienthi-checkbox" <?=(!isset($item['hienthi']) || $item['hienthi']==1)?'checked':''?>>
						<label for="hienthi-checkbox" class="custom-control-label"></label>
					</div>
				</div>
				<div class="form-group">
					<label for="stt" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
					<input type="number" class="form-control form-control-mini d-inline-block align-middle" min="0" name="data[stt]" id="stt" placeholder="Số thứ tự" value="<?=isset($item['stt']) ? $item['stt'] : 1?>">
				</div>
			</div>
		</div>
		<div class="card-footer text-sm">
			<button type="submit" class="btn btn-sm bg-gradient-primary" disabled><i class="far fa-save mr-2"></i>Lưu</button>
			<button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
			<a class="btn btn-sm bg-gradient-danger" href="<?=$linkMan?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
			<input type="hidden" name="id" value="<?=(isset($item['id']) && $item['id'] > 0) ? $item['id'] : ''?>">
		</div>
	</form>
</section>

<!-- User js -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#ngaysinh').datetimepicker({
			timepicker: false,
			format: 'd/m/Y',
			formatDate: 'd/m/Y',
	        // minDate: '1950/01/01',
	        maxDate: '<?=date("Y/m/d",time())?>'
	    });
	});
</script>