<div class="center bg-white">
    <div class="title-main d-none">
        <h1><?= $title_crumb ?></h1>
        <p><?= $slogan['ten'] ?></p>
    </div>
    <div class="w-clear py-5 my-2 d-flex flex-column align-items-center justify-content-center">
        <section id="luckywheel" data-id="<?= $pro_cat['id'] ?>" class="hc-luckywheel">
            <div class="hc-luckywheel-container">
                <canvas class="hc-luckywheel-canvas" width="500px" height="500px">Vòng Xoay May Mắn</canvas>
            </div>
            
            <button class="hc-luckywheel-quay"></button>
            <button class="hc-luckywheel-btn d-none"></button>
        </section>

        <?php if($noidung['noidung']) { ?> 
            <div class="mt-5 pt-5 w-clear">
                <?= htmlspecialchars_decode($noidung['noidung']) ?>
            </div>    
        <?php } ?>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/hc-canvas-luckwheel.js"></script>
<script src="assets/js/sweetalert.js"></script>

<script>
let prizes = JSON.parse('<?= json_encode($result); ?>');
var isPercentage = true;

$('.hc-luckywheel-quay').click(function(e) {
        e.preventDefault();
        const user = '<?= $_SESSION[$login_member]['active'] ?>';
        if (!user) {
            return Swal.fire(
                'Lỗi',
                'Vui lòng đăng nhập để tiếp tục',
                'error'
            )
        } else {
            const url__submit = 'ajax/ajax_quaythuong.php';
            $.ajax({
                url: url__submit,
                type: 'POST',
                data: {
                    id: 33,
                },
                success: function(res) {
                    const result = JSON.parse(res);
                    if (result.status == 'error') {
                        return Swal.fire(
                            'Lỗi',
                            result.message,
                            'error'
                        )
                    } else {
                        for (let i = 0; i < prizes.length; i++) {
                            if (prizes[i].percentpage == 1) {
                                prizes[i].percentpage = 0;
                            }
                            if (prizes[i].id == result.id) {
                                prizes[i].percentpage = 1;
                            }
                        }
                        
                        $('.hc-luckywheel-btn').click();
                    }
                }.bind(this)
            });
        }
    })

hcLuckywheel.init({
    id: "luckywheel",
    config: function(callback) {
        callback &&
            callback(prizes);
    },
    mode: "both",
    getPrize: function(callback) {
        var rand = randomIndex(prizes);
        var chances = rand;
        callback && callback([rand, chances]);
    },
    gotBack: function(data) {
        if (data == null) {
            Swal.fire(
                'Chương trình kết thúc',
                'Đã hết phần thưởng',
                'error'
            )
        } else if (data == 'Chúc bạn may mắn lần sau') {
            Swal.fire(
                'Bạn không trúng thưởng',
                data,
                'error'
            )
        } else {
            Swal.fire(
                'Đã trúng giải',
                data,
                'success'
            )
        }
    }
});

function randomIndex(prizes) {
    if (isPercentage) {
        var counter = 1;
        for (let i = 0; i < prizes.length; i++) {
            if (prizes[i].number == 0) {
                counter++
            }
        }
        if (counter == prizes.length) {
            return null
        }
        let prizeIndex = 0;
        for (let i = 0; i < prizes.length; i++) {
            if (prizes[i].percentpage == 1) {
                prizeIndex = i;
            }
        }

        return prizeIndex;
    }
}
</script>