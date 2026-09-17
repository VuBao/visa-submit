<?php /* 
<div class="wrap-user">
    <div class="title-user">
        <span><?=quenmatkhau?></span>
    </div>
    <form class="form-user validation-user" novalidate method="post" action="account/quen-mat-khau" enctype="multipart/form-data">
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-user"></i></div>
            </div>
            <input type="text" class="form-control" id="username" name="username" placeholder="<?=taikhoan?>" required>
            <div class="invalid-feedback"><?=vuilongnhaptaikhoan?></div>
        </div>
        <div class="input-group input-user">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-envelope"></i></div>
            </div>
            <input type="email" class="form-control" id="email" name="email" placeholder="<?=nhapemail?>" required>
            <div class="invalid-feedback"><?=vuilongnhapdiachiemail?></div>
        </div>
        <div class="button-user">
            <input type="submit" class="btn btn-primary" name="quenmatkhau" value="<?=laymatkhau?>" disabled>
        </div>
    </form>
</div>
*/ ?>

<div class="bg-img" style="background: url(<?= UPLOAD_PHOTO_L . $background['photo'] ?>)">

    <div class="wrap-user">
        <form class="form-user validation-user" novalidate method="post" action="account/quen-mat-khau" enctype="multipart/form-data">
            <div class="hr-card__step h-100 d-flex flex-column">
                <h3 class="title"><?= quenmatkhau ?></h3>
                <span class="mt-2 mb-3 font-normal"><?= contentquanmatkhau ?></span>
                <div class="form-group">
                    <label for="">Email <span class="required"><?= batbuoc ?></span> </label>
                    <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">
                        <label for="email"><i class="fas fa-at"></i></label>
                        <input type="email" id="email" name="email" placeholder="Email" required />
                        <div class="invalid-feedback"><?= vuilongdienemail ?></div>
                    </div>
                </div>
                </div>
                
                <div class="button-user d-flex align-items-center justify-content-between mt-5">
                    <a href="javascript:history.back()" class="btn btn-outline-primary lg-btn"> <?= trove ?></a>

                    <input type="submit" class="btn btn-primary lg-btn" name="quenmatkhau" value="<?= laymatkhau ?>" disabled>

                </div>
                <span class="forgot-password mt-3 d-block text-center font-normal">
                    <?= bandacotaikhoan ?> <a href="account/dang-nhap" class="font-weight-bold"><?= dangnhapngay ?></a>
                </span>
            </div>
        </form>
    </div>
</div>