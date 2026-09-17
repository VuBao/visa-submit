<div class="center">

    <div class="title-main d-none">
        <h1><?= $title_crumb ?></h1>
        <p><?= $slogan['ten'] ?></p>
    </div>

    <div class="box-title mt-2">
        <h3><?= $lienhe['ten'] ?></h3>
        <p><?= banmuontimviec ?> <a href="tin-tuyen-dung"><?= taiday ?></a></p>
    </div>

    <div class="w-clear my-5">
        <div class="top-contact">
            <form class="form-contact validation-contact" novalidate method="post" action=""
                enctype="multipart/form-data">
                
                <div class="row custom-row-contact">
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="ten"><i class="far fa-user"></i></label>
                            <input type="text" id="ten" name="ten" placeholder="<?= hoten ?>" required />
                            <div class="invalid-feedback"><?= vuilongdienhoten ?></div>
                        </div>

                    </div>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="dienthoai"><i class="fas fa-phone"></i></label>
                            <input type="text"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                id="dienthoai" name="dienthoai" placeholder="<?= dienthoai ?>" required />
                            <div class="invalid-feedback"><?= vuilongnhapdienthoai ?></div>
                        </div>

                    </div>
                </div>
                <div class="row custom-row-contact">
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="diachi"><i class="fas fa-map-marker-alt"></i></label>
                            <input type="text" id="diachi" name="diachi" placeholder="<?= diachi ?>" required />
                            <div class="invalid-feedback"><?= vuilongnhapdiachi ?></div>
                        </div>

                    </div>
                    <div class="input-contact custom-col-contact">
                        <div class="position-relative custom-hover-input">
                            <label for="email"><i class="fas fa-at"></i></label>
                            <input type="email" id="email" name="email" placeholder="Email" required />
                            <div class="invalid-feedback"><?= vuilongnhapemail ?></div>
                        </div>

                    </div>
                </div>

                <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">
                        <label for="tieude"><i class="fab fa-battle-net"></i></label>
                        <input type="text" id="tieude" name="tieude" placeholder="<?= chude ?>" required />
                        <div class="invalid-feedback"><?= vuilongnhapchude ?></div>
                    </div>


                </div>



                <div class="input-contact custom-col-contact">
                    <div class="position-relative custom-hover-input">
                        <textarea id="noidung" name="noidung" rows="5" placeholder="<?= noidung ?>" required /></textarea>
                        <div class="invalid-feedback"><?= vuilongnhapnoidung ?></div>
                    </div>

                </div>
    <div class="row custom-row-contact">
        <input type="submit" class="custom-btn-contact" name="submit-contact" value="<?= gui ?>" disabled />
        <input type="reset" class="custom-btn-contact reset" value="<?= nhaplai ?>" />
        <input type="hidden" name="recaptcha_response_contact" id="recaptchaResponseContact">
    </div>

    </form>
    <div class="article-contact d-flex justify-content-end align-items-start">

    <img  onerror="this.src='<?= THUMBS ?>/757x420x2/assets/images/noimage.png';"
                            src="<?= THUMBS ?>/757x420x2/<?= UPLOAD_NEWS_L . $lienhe['photo'] ?>" alt="<?= $lienhe['ten'] ?>">

    </div>

</div>

<div>
    <?= (isset($lienhe['noidung']) && $lienhe['noidung'] != '') ? htmlspecialchars_decode($lienhe['noidung']) : '' ?>
</div>

</div>
</div>