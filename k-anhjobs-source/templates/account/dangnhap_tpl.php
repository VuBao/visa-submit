<div class="bg-img" style="background: url(<?= UPLOAD_PHOTO_L . $background['photo'] ?>)">
    <div class="wrap-user">
        <h3 class="title-login"><?= dangnhap ?></h3>
        
        <div class="auth-tabs d-flex justify-content-between mb-4 mt-2" style="background: #f1f3f5; border-radius: 30px; padding: 4px;">
            <a href="account/dang-nhap?type=candidate" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; <?= (!isset($_GET['type']) || $_GET['type'] == 'candidate') ? 'background: #0072bc; color: #fff;' : 'color: #495057;' ?>">Đăng nhập Ứng viên</a>
            <a href="account/dang-nhap?type=ctv" class="auth-tab text-center py-2 flex-grow-1 text-decoration-none" style="border-radius: 25px; font-weight: bold; font-size: 14px; transition: all 0.3s ease; <?= (isset($_GET['type']) && $_GET['type'] == 'ctv') ? 'background: #0072bc; color: #fff;' : 'color: #495057;' ?>">Đăng nhập CTV</a>
        </div>

        <span class="mt-2 mb-3 font-normal text-login"><?= bandacotaikhoan ?></span>

        <form class="form-user validation-user" novalidate method="post" action="account/dang-nhap<?= (isset($_GET['type'])) ? '?type=' . htmlspecialchars($_GET['type']) : '' ?>"
            enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">Email
                    <strong class="required"><?= batbuoc ?></strong>
                </label>
                <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">
                        <label for="email"><i class="fas fa-at"></i></label>
                        <input type="email" id="email" name="email" placeholder="Email" required />
                        <div class="invalid-feedback"><?= vuilongdienemail ?></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password"><?= matkhau ?>
                    <strong class="required">Bắt buộc</strong>
                </label>
                <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">  
                        <label for="password"><i class="fa fa-lock"></i></label>
                        <input type="password" id="password" name="password" placeholder="<?=matkhau?>" required />
                        <div class="invalid-feedback"><?=vuilongdienmatkhau?></div>
                    </div>
                </div>
            </div>


            <div class="text-box">
                <div class="text">
                    <span><?= banquanmatkhau ?>? <a href="account/quen-mat-khau" class="quenmatkhau"><?= caidattaiday ?></a></span>
                </div>
                <div class="checkbox-user custom-checkbox text">
                    <input type="checkbox" class="custom-control-input text" name="remember-user" id="remember-user"
                        value="1">
                    <label class="custom-control-label text taotaikhoan" for="remember-user"><?= nhomatkhau ?></label>
                </div>
            </div>

            <div class="button-user d-flex align-items-center justify-content-between">
                <a href="javascript:history.back()" class="btn btn-outline-primary lg-btn"> <?= trove ?></a>

                <input type="submit" class="btn btn-primary lg-btn" name="dangnhap" value="<?= dangnhap ?>">

            </div>
            <div class="note-user">
                <span class="banchuacotaikhoan"><?= banchuacotaikhoan ?> ! </span>
            </div>
            <div class="text">
                <p class="text taotaikhoan"><?= taotaikhoan ?></p>
            </div>
            <div class="button-user d-flex align-items-center justify-content-between">

                <a class="btn btn-outline-primary lg-btn" href="account/dang-ky"> <?= ungvien ?></a>
                <a class="btn btn-outline-primary lg-btn" href="account/dang-ky-ctv"> <?= congtacvien ?></a>


            </div>
        </form>
    </div>
</div>