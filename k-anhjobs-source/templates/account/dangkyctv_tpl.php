<div class="bg-img" style="background: url(<?= UPLOAD_PHOTO_L . $background['photo'] ?>)">

    <div class="wrap-user">
        <form class="form-user validation-user" novalidate method="post" action="account/dang-ky-ctv" enctype="multipart/form-data">
            <div class="hr-card__step h-100 d-flex flex-column">
                <h3 class="title"><?= dangky ?> <?= congtacvien ?></h3>
                
                <div class="auth-tabs d-flex justify-content-between mb-4 mt-2" style="background: #f1f3f5; border-radius: 30px; padding: 4px;">
                    <a href="account/dang-ky" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; color: #495057;">Đăng ký Ứng viên</a>
                    <a href="account/dang-ky-ctv" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; background: #0072bc; color: #fff;">Đăng ký CTV</a>
                </div>

                <span class="mt-3 mb-3 font-normal"><?= haynhapemailcuaban ?></span>

                <div class="form-group mb-0">
                    <label for=""><?= hoten ?> <span class="required"><?= batbuoc ?></span> </label>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="ten"><i class="fa fa-user"></i></label>
                            <input type="text" id="ten" name="ten" placeholder="<?= hoten ?>" required />
                            <div class="invalid-feedback"><?= vuilongdienhoten ?></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label for="">Email <span class="required"><?= batbuoc ?></span> </label>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="email"><i class="fas fa-at"></i></label>
                            <input type="email" id="email" name="email" placeholder="Email" required />
                            <div class="invalid-feedback"><?= vuilongdienemail ?></div>
                        </div>
                    </div>
                </div>
               
                <div class="form-group mb-0">
                    <label for=""><?= matkhau ?> <span class="required"><?= batbuoc ?></span> </label>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="password"><i class="fa fa-lock"></i></label>
                            <input type="password" id="password" name="password" placeholder="<?=matkhau?>" required />
                            <div class="invalid-feedback"><?=vuilongdienmatkhau?></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for=""><?= nhaplaimatkhau ?> <span class="required"><?= batbuoc ?></span> </label>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="repassword"><i class="fa fa-lock"></i></label>
                            <input type="password" id="repassword" name="repassword" placeholder="<?=nhaplaimatkhau?>" required />
                            <div class="invalid-feedback"><?=vuilongnhaplaimatkhau?></div>
                        </div>
                    </div>
                </div>
              
                <div class="button-user d-flex align-items-center justify-content-between">
                    <a href="javascript:history.back()" class="btn btn-outline-primary lg-btn"> <?= trove ?></a>

                    <input type="submit" class="btn btn-primary lg-btn" name="dangky" value="<?= dangky ?>">

                </div>
                <span class="forgot-password mt-3 d-block text-center font-normal">
                    <?= bandacotaikhoan ?> <a href="account/dang-nhap" class="font-weight-bold"><?= dangnhapngay ?></a>
                </span>
            </div>
        </form>
    </div>
</div>

<!-- <div class="warp-user">
    <div class="card-body hr-card__body center">

    </div>
</div> -->

<!-- <label for="basic-url"><?= hoten ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-user"></i></div>
            </div>
            <input type="text" class="form-control" id="ten" name="ten" placeholder="<?= nhaphoten ?>" required>
            <div class="invalid-feedback"><?= vuilongnhaphoten ?></div>
        </div>
        <label for="basic-url"><?= taikhoan ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-user"></i></div>
            </div>
            <input type="text" class="form-control" id="username" name="username" placeholder="<?= nhaptaikhoan ?>" required>
            <div class="invalid-feedback"><?= vuilongnhaptaikhoan ?></div>
        </div>
        <label for="basic-url"><?= matkhau ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-lock"></i></div>
            </div>
            <input type="password" class="form-control" id="password" name="password" placeholder="<?= nhapmatkhau ?>" required>
            <div class="invalid-feedback"><?= vuilongnhapmatkhau ?></div>
        </div>
        <label for="basic-url"><?= nhaplaimatkhau ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-lock"></i></div>
            </div>
            <input type="password" class="form-control" id="repassword" name="repassword" placeholder="<?= nhaplaimatkhau ?>" required>
            <div class="invalid-feedback"><?= vuilongnhaplaimatkhau ?></div>
        </div>
        <label for="basic-url"><?= gioitinh ?></label>
        <div class="input-group input-user">
            <div class="radio-user custom-control custom-radio">
                <input type="radio" id="nam" name="gioitinh" class="custom-control-input" value="1" required>
                <label class="custom-control-label" for="nam"><?= nam ?></label>
            </div>
            <div class="radio-user custom-control custom-radio">
                <input type="radio" id="nu" name="gioitinh" class="custom-control-input" value="2" required>
                <label class="custom-control-label" for="nu"><?= nu ?></label>
            </div>
        </div>
        <label for="basic-url"><?= ngaysinh ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-lock"></i></div>
            </div>
            <input type="text" class="form-control" id="ngaysinh" name="ngaysinh" placeholder="<?= nhapngaysinh ?>" required>
            <div class="invalid-feedback"><?= vuilongnhapsodienthoai ?></div>
        </div>
        <label for="basic-url">Email</label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-envelope"></i></div>
            </div>
            <input type="email" class="form-control" id="email" name="email" placeholder="<?= nhapemail ?>" required>
            <div class="invalid-feedback"><?= vuilongnhapdiachiemail ?></div>
        </div>
        <label for="basic-url"><?= dienthoai ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-phone-square"></i></div>
            </div>
            <input type="number" class="form-control" id="dienthoai" name="dienthoai" placeholder="<?= nhapdienthoai ?>" required>
            <div class="invalid-feedback"><?= vuilongnhapsodienthoai ?></div>
        </div>
        <label for="basic-url"><?= diachi ?></label>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-map"></i></div>
            </div>
            <input type="text" class="form-control" id="diachi" name="diachi" placeholder="<?= nhapdiachi ?>" required>
            <div class="invalid-feedback"><?= vuilongnhapdiachi ?></div>
        </div>
        <div class="button-user">
            <input type="submit" class="btn btn-primary btn-block" name="dangky" value="<?= dangky ?>" disabled>
        </div> -->