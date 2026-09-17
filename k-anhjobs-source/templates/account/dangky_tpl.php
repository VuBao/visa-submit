<div class="bg-img" style="background: url(<?= UPLOAD_PHOTO_L . $background['photo'] ?>)">

    <div class="wrap-user">
        <form class="form-user validation-user" novalidate method="post" action="account/dang-ky" enctype="multipart/form-data">
            <div class="hr-card__step h-100 d-flex flex-column">
                <h3 class="title"><?= dangky ?></h3>
                
                <div class="auth-tabs d-flex justify-content-between mb-4 mt-2" style="background: #f1f3f5; border-radius: 30px; padding: 4px;">
                    <a href="account/dang-ky" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; background: #0072bc; color: #fff;">Đăng ký Ứng viên</a>
                    <a href="account/dang-ky-ctv" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; color: #495057;">Đăng ký CTV</a>
                </div>

                <span class="mt-3 mb-3 font-normal"><?= haynhapemailcuaban ?></span>
              

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
                   
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="password"><i class="fa fa-lock"></i></label>
                            <input type="password" id="password" name="password" placeholder="<?=matkhau?>" required />
                            <div class="invalid-feedback"><?=vuilongdienmatkhau?></div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                <label for=""><?= nhaplaimatkhau ?> <span class="required"><?= batbuoc ?></span> </label>
                <div class="input-contact custom-col-contact">
                   
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="repassword"><i class="fa fa-lock"></i></label>
                            <input type="password" id="repassword" name="repassword" placeholder="<?= nhaplaimatkhau ?>" required />
                            <div class="invalid-feedback"><?=vuilongnhaplaimatkhau?></div>
                        </div>
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

