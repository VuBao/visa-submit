<!-- Js Config -->
<script type="text/javascript">
var VNS_FRAMEWORK = VNS_FRAMEWORK || {};
var CONFIG_BASE = '<?=$config_base?>';
var WEBSITE_NAME = '<?=(!empty($setting['ten'.$lang])) ? addslashes($setting['ten'.$lang]) : ''?>';
var TIMENOW = '<?=date("d/m/Y",time())?>';
var SHIP_CART = <?=(isset($config['order']['ship']) && $config['order']['ship'] == true) ? 'true' : 'false'?>;
var GOTOP = 'assets/images/top.png';
var LANG = {
    'no_keywords': '<?=chuanhaptukhoatimkiem?>',
};
</script>

<!-- Js Files -->
<?php
$js->setCache("cached");
$js->setJs("./assets/js/jquery.min.js");
$js->setJs("./assets/bootstrap/bootstrap.js");
$js->setJs("./assets/js/wow.min.js");
$js->setJs("./assets/mmenu/mmenu.js");
$js->setJs("./assets/simplyscroll/jquery.simplyscroll.js");
$js->setJs("./assets/fotorama/fotorama.js");
$js->setJs("./assets/owlcarousel2/owl.carousel.js");
$js->setJs("./assets/slick/slick.js");
$js->setJs("./assets/fancybox3/jquery.fancybox.js");
$js->setJs("./assets/photobox/photobox.js");
$js->setJs("./assets/datetimepicker/php-date-formatter.min.js");
$js->setJs("./assets/datetimepicker/jquery.mousewheel.js");
$js->setJs("./assets/datetimepicker/jquery.datetimepicker.js");
$js->setJs("./assets/toc/toc.js");
$js->setJs("./assets/js/functions.js");
$js->setJs("./assets/js/jquery.pixelentity.shiner.min.js");
$js->setJs("./assets/swiper/swiper.js");
$js->setJs("./assets/lightgallery/Lightgallery.js");
$js->setJs("./assets/aos/aos.js");
// $js->setJs("./assets/js/socket.js");
$js->setJs("./assets/js/howler.js");
$js->setJs("./assets/select2/select2.full.js");
$js->setJs("./assets/js/sweetalert.js");
$js->setJs("./assets/js/apps.js");
$js->setJs("./assets/magiczoomplus/magiczoomplus.js");
echo $js->getJs();
?>

<?php if(isset($config['googleAPI']['recaptcha']['active']) && $config['googleAPI']['recaptcha']['active'] == true) { ?>
<!-- Js Google Recaptcha V3 -->
<script src="https://www.google.com/recaptcha/api.js?render=<?=$config['googleAPI']['recaptcha']['sitekey']?>"></script>
<script type="text/javascript">
grecaptcha.ready(function() {
    grecaptcha.execute('<?=$config['googleAPI']['recaptcha']['sitekey']?>', {
        action: 'Newsletter'
    }).then(function(token) {
        var recaptchaResponseNewsletter = document.getElementById('recaptchaResponseNewsletter');
        recaptchaResponseNewsletter.value = token;
    });
    <?php if($source=='contact') { ?>
    grecaptcha.execute('<?=$config['googleAPI']['recaptcha']['sitekey']?>', {
        action: 'contact'
    }).then(function(token) {
        var recaptchaResponseContact = document.getElementById('recaptchaResponseContact');
        recaptchaResponseContact.value = token;
    });
    <?php } ?>
});
</script>
<?php } ?>

<?php if(isset($config['oneSignal']['active']) && $config['oneSignal']['active'] == true) { ?>
<!-- Js OneSignal -->
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script type="text/javascript">
var OneSignal = window.OneSignal || [];
OneSignal.push(function() {
    OneSignal.init({
        appId: "<?=$config['oneSignal']['id']?>"
    });
});
</script>
<?php } ?>

<!-- Js Structdata -->
<?php include TEMPLATE.LAYOUT."strucdata.php"; ?>

<!-- Js Addons -->
<?=$addons->setAddons('script-main', 'script-main', 0.5);?>
<?=$addons->getAddons();?>

<!-- Js Body -->
<?=htmlspecialchars_decode($setting['bodyjs'])?>

<?php if($_SESSION[$login_member]['active']) { 
    $is_ctv = ($_SESSION[$login_member]['role'] == 1);
    $jobs = [];
    if ($is_ctv) {
        $jobs = $d->rawQuery("select id, tenvi, tenkhongdauvi from #_news where type = ? and hienthi > 0 and trangthai = 1 order by id desc", array('tin-tuyen-dung'));
    }
?>
<script>
$(document).ready(function() {
    var jobs = <?= json_encode($jobs) ?>;
    
    // Cross-tab Coordination using BroadcastChannel and LocalStorage Lock
    const currentTabId = Math.random().toString(36).substring(2, 15);
    const chatChannel = new BroadcastChannel('chat_unread_coordination');
    let needToPlaySoundOnInteraction = false;

    function unlockAndPlay() {
        if (needToPlaySoundOnInteraction) {
            needToPlaySoundOnInteraction = false;
            try {
                console.log("User interacted. Playing queued notification sound.");
                const sound = new Howl({
                    src: [CONFIG_BASE + 'assets/mp3/chuong.mp3'],
                    html5: false,
                    autoplay: true,
                    volume: 0.5
                });
                sound.play();
            } catch(e) {
                console.error("Error playing queued sound: ", e);
            }
        }
    }
    $(document).on('click keydown touchstart', unlockAndPlay);

    function isLeader() {
        try {
            const lock = JSON.parse(localStorage.getItem('chat_poll_lock'));
            return lock && lock.tabId === currentTabId;
        } catch(e) {
            return false;
        }
    }

    function updateBadge(count) {
        count = parseInt(count) || 0;
        localStorage.setItem('chat_unread_count', count);
        
        const badges = $('.js-chat-badge');
        if (count > 0) {
            badges.text(count).show();
        } else {
            badges.hide().text('0');
        }

        const sidebarBadge = $('.js-chat-badge-sidebar');
        if (sidebarBadge.length) {
            if (count > 0) {
                sidebarBadge.text(count).show();
            } else {
                sidebarBadge.hide().text('0');
            }
        }
    }

    function pollUnreadCount() {
        $.ajax({
            url: 'ajax/ajax_get_unread_message_count.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 'success') {
                    const count = parseInt(response.count) || 0;
                    const oldCount = parseInt(localStorage.getItem('chat_unread_count')) || 0;

                    // Play notification sound if count increased and we are not on the chat page
                    if (count > oldCount && $(".body-chat").length === 0) {
                        try {
                            console.log("Playing notification sound from: " + CONFIG_BASE + "assets/mp3/chuong.mp3");
                            const sound = new Howl({
                                src: [CONFIG_BASE + 'assets/mp3/chuong.mp3'],
                                html5: false,
                                autoplay: true,
                                volume: 0.5,
                                onplayerror: function(id, err) {
                                    console.warn("Audio play error (likely browser autoplay policy):", err);
                                    needToPlaySoundOnInteraction = true;
                                },
                                onloaderror: function(id, err) {
                                    console.error("Audio load error:", err);
                                }
                            });
                            sound.play();
                        } catch(e) {
                            console.error("Error playing sound: ", e);
                        }
                    }

                    updateBadge(count);
                    chatChannel.postMessage({
                        type: 'unread_count_update',
                        count: count
                    });
                }
            }
        });
    }

    const LEASE_DURATION = 15000; // 15s lease
    const CHECK_INTERVAL = 3000;  // Check leadership every 3s
    const POLL_INTERVAL = 10000;  // Poll database every 10s
    let lastPollTime = 0;

    function runCoordination() {
        const now = Date.now();
        let lock = null;
        try {
            lock = JSON.parse(localStorage.getItem('chat_poll_lock'));
        } catch(e) {}

        // If lock is valid and owned by someone else, we are follower
        if (lock && (now - lock.timestamp < LEASE_DURATION) && lock.tabId !== currentTabId) {
            return;
        }

        // Claim/renew lock
        const newLock = {
            tabId: currentTabId,
            timestamp: now
        };
        localStorage.setItem('chat_poll_lock', JSON.stringify(newLock));

        if (now - lastPollTime >= POLL_INTERVAL) {
            lastPollTime = now;
            pollUnreadCount();
        }
    }

    // Load last known count immediately to avoid blank state
    const lastCount = localStorage.getItem('chat_unread_count');
    if (lastCount !== null) {
        updateBadge(parseInt(lastCount));
    }

    // Start coordination loops
    setInterval(runCoordination, CHECK_INTERVAL);
    runCoordination();

    // Listen to messages from other tabs
    chatChannel.onmessage = function(event) {
        if (event.data && event.data.type === 'unread_count_update') {
            updateBadge(event.data.count);
        } else if (event.data && event.data.type === 'trigger_poll') {
            if (isLeader()) {
                pollUnreadCount();
            }
        }
    };

    // If we are on the active chat page, trigger an immediate poll to clear badges
    if ($(".body-chat").length > 0) {
        pollUnreadCount();
    }

    // AJAX Polling for Chat Messages (every 10 seconds)
    if ($(".body-chat").length > 0) {
        setInterval(function() {
            var data = {};
            if ($('#id_candidate').length) {
                data.id_candidate = $('#id_candidate').val();
            }
            if ($('#id_ctv').length) {
                data.id_ctv = $('#id_ctv').val();
            }
            $.ajax({
                url: 'ajax/ajax_get_new_messages.php',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.html) {
                        $(".no-images").remove();
                        $(".body-chat").prepend(response.html);
                        
                        // Play notification sound
                        console.log("Playing chat page notification sound from: " + CONFIG_BASE + "assets/mp3/chuong.mp3");
                        const sound = new Howl({
                            src: [CONFIG_BASE + 'assets/mp3/chuong.mp3'],
                            html5: false,
                            autoplay: true,
                            volume: 0.5,
                            onplayerror: function(id, err) {
                                console.warn("Audio play error (likely browser autoplay policy):", err);
                            },
                            onloaderror: function(id, err) {
                                console.error("Audio load error:", err);
                            }
                        });
                        sound.play();

                        // Poll unread count on the current tab, which will update the badge and broadcast it to other tabs
                        pollUnreadCount();
                    }
                }
            });
        }, 10000);
    }

    $(".ungtuyen-submit").click((e) => {
        e.preventDefault();
        $(".loadding").removeClass("hide");
        $('.submit-form').click();
    });

    function get_message(id) {
        const url__submit = "ajax/ajax_get_message.php";

        $.ajax({
            url: url__submit,
            type: 'POST',
            data: {
                id: id
            },
            success: function(res) {
                $(".body-chat").prepend(res);
            }
        });
    }

    $('.send-file').click(function() {
        $('#file_message').click();
    });
    $('#file_message').change(function(e) {
        const file = e.target.files[0];

        const objectURL = URL.createObjectURL(file);
        Swal.fire({
            title: '<?= xacnhan ?>',
            text: "<?= bancochacchanmuonguihinhanh ?>",
            imageUrl: objectURL,
            imageWidth: 400,
            imageHeight: 200,
            imageAlt: 'Send images',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= xacnhan ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                const time = new Date();
                const day = time.getDate();
                const month = time.getMonth();
                const year = time.getFullYear();
                const hours = time.getHours();
                const minutes = time.getMinutes();

                const element = ` <div class="message-right message messager-js">
                            <div class="mw-75" title="<?= daguiluc ?> ${hours+':'+minutes+ ' '+day+':'+month+':'+year}">
                                <img src="${objectURL}" alt="">   
                            </div>
                        </div>`;
                $(".no-images").remove();
                $(".body-chat").prepend(element);

                let data = new FormData();
                data.append('image', file);
                if ($('#id_candidate').length) {
                    data.append('id_candidate', $('#id_candidate').val());
                }
                if ($('#id_ctv').length) {
                    data.append('id_ctv', $('#id_ctv').val());
                }

                $('#file_message').val('');

                send_messager(data);
            }
        })
    });

    $('#message').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            const value = $(this).val();
            if (value == '') {
                return false;
            }
            let data = new FormData();
            data.append('message', value);
            if ($('#id_candidate').length) {
                data.append('id_candidate', $('#id_candidate').val());
            }
            if ($('#id_ctv').length) {
                data.append('id_ctv', $('#id_ctv').val());
            }

            $('#message').val('');
            const time = new Date();
            const day = time.getDate();
            const month = time.getMonth();
            const year = time.getFullYear();
            const hours = time.getHours();
            const minutes = time.getMinutes();
            const element = ` <div class="message-right message">
                            <div class="mw-75" title="<?= daguiluc ?> ${hours+':'+minutes+ ' '+day+':'+month+':'+year}">
                                ${value}   
                            </div>
                        </div>`;
            $(".no-images").remove();
            $(".body-chat").prepend(element);

            send_messager(data);
        }
    });

    $('.send-messager-btn').click(function() {
        const value = $('#message').val();
        if (value == '') {
            return false;
        }
        let data = new FormData();
        data.append('message', value);
        if ($('#id_candidate').length) {
            data.append('id_candidate', $('#id_candidate').val());
        }
        if ($('#id_ctv').length) {
            data.append('id_ctv', $('#id_ctv').val());
        }

        $('#message').val('');
        const time = new Date();
        const day = time.getDate();
        const month = time.getMonth();
        const year = time.getFullYear();
        const hours = time.getHours();
        const minutes = time.getMinutes();
        const element = ` <div class="message-right message">
                            <div class="mw-75" title="<?= daguiluc ?> ${hours+':'+minutes+ ' '+day+':'+month+':'+year}">
                                ${value}   
                            </div>
                        </div>`;
        $(".no-images").remove();
        $(".body-chat").prepend(element);

        send_messager(data);
    });

    function send_messager(data) {
        const url__submit = "ajax/ajax_send_file.php";

        $.ajax({
            url: url__submit,
            contentType: false,
            processData: false,
            type: 'POST',
            data: data,
            success: function(res) {
                const response = JSON.parse(res);
                return;
            }
        });
    }

    function removeVietnameseTones(str) {
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
        str = str.replace(/đ/g,"d");
        str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        str = str.replace(/Ù|Ú|Ụ|Ủ|U|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        str = str.replace(/Đ/g, "D");
        str = str.replace(/\u0300|\u0301|\u0309|\u0303|\u0323/g, "");
        str = str.replace(/\u02C6|\u0306|\u031B/g, "");
        return str;
    }

    function select2Matcher(params, data) {
        if ($.trim(params.term) === '') {
            return data;
        }
        if (typeof data.text === 'undefined') {
            return null;
        }
        var search = removeVietnameseTones(params.term.toLowerCase());
        var text = removeVietnameseTones(data.text.toLowerCase());
        if (text.indexOf(search) > -1) {
            return data;
        }
        return null;
    }

    $(document).on('click', '.btn-share-job', function() {
        var selectHtml = '<select id="swal-share-job-select" class="form-control" style="width: 100%;">';
        selectHtml += '<option value="">-- Chọn công việc/Job --</option>';
        if (jobs && jobs.length) {
            for (var i = 0; i < jobs.length; i++) {
                var job_url = '<?= $config_base ?>' + jobs[i].tenkhongdauvi;
                var titleText = jobs[i].tenvi ? jobs[i].tenvi : '';
                var escapedTitle = titleText.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                selectHtml += '<option value="' + job_url + '" data-title="' + escapedTitle + '">' + escapedTitle + '</option>';
            }
        }
        selectHtml += '</select>';

        Swal.fire({
            title: 'Chia sẻ công việc / Job',
            html: `
                <div class="text-left font-weight-bold" style="font-size: 14px;">
                    <p class="mb-2">Chọn công việc bạn muốn gửi cho ứng viên:</p>
                    ${selectHtml}
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#2f6fed',
            confirmButtonText: 'Chia sẻ',
            cancelButtonText: 'Hủy',
            onOpen: () => {
                setTimeout(function() {
                    $('#swal-share-job-select').select2({
                        dropdownParent: $('.swal2-container'),
                        matcher: select2Matcher
                    });
                    $('#swal-share-job-select').select2('open');
                }, 150);
            },
            preConfirm: () => {
                const selectEl = Swal.getPopup().querySelector('#swal-share-job-select');
                const url = selectEl.value;
                const title = selectEl.options[selectEl.selectedIndex].getAttribute('data-title');
                if (!url) {
                    Swal.showValidationMessage(`Vui lòng chọn một công việc`);
                }
                return { url: url, title: title };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var jobText = `Công việc: ${result.value.title}\nLink chi tiết: ${result.value.url}`;
                var currentVal = $('#message').val();
                if(currentVal) {
                    $('#message').val(currentVal + '\n' + jobText);
                } else {
                    $('#message').val(jobText);
                }
                $('#message').focus();
                if (typeof autoResize === 'function') {
                    autoResize(document.getElementById('message'));
                }
            }
        });
    });

    $(document).on('click', '.btn-chot-job', function() {
        var candidateId = $(this).data('candidate-id');
        var selectHtml = '<select id="swal-chot-job-select" class="form-control" style="width: 100%;">';
        selectHtml += '<option value="">-- Chọn công việc/Job --</option>';
        if (jobs && jobs.length) {
            for (var i = 0; i < jobs.length; i++) {
                var titleText = jobs[i].tenvi ? jobs[i].tenvi : '';
                var escapedTitle = titleText.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                selectHtml += '<option value="' + jobs[i].id + '">' + escapedTitle + '</option>';
            }
        }
        selectHtml += '</select>';

        Swal.fire({
            title: 'Chốt ứng viên vào Job',
            html: `
                <div class="text-left font-weight-bold" style="font-size: 14px;">
                    <p class="mb-2">Chọn công việc bạn muốn chốt cho ứng viên này để gửi tới Admin duyệt:</p>
                    ${selectHtml}
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Xác nhận chốt',
            cancelButtonText: 'Hủy',
            onOpen: () => {
                setTimeout(function() {
                    $('#swal-chot-job-select').select2({
                        dropdownParent: $('.swal2-container'),
                        matcher: select2Matcher
                    });
                    $('#swal-chot-job-select').select2('open');
                }, 150);
            },
            preConfirm: () => {
                const selectEl = Swal.getPopup().querySelector('#swal-chot-job-select');
                const id_news = selectEl.value;
                const title_news = selectEl.options[selectEl.selectedIndex].text;
                if (!id_news) {
                    Swal.showValidationMessage(`Vui lòng chọn một công việc`);
                }
                return { id_news: id_news, title_news: title_news };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/ajax_chot_job.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_candidate: candidateId,
                        id_news: result.value.id_news
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            allowOutsideClick: false,
                            onOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: res.message
                            }).then(() => {
                                var alertMsg = `Tôi đã đăng ký giới thiệu bạn vào vị trí: ${result.value.title_news}. Vui lòng chờ Admin duyệt và liên hệ phỏng vấn nhé!`;
                                $('#message').val(alertMsg);
                                $('.send-messager-btn').click();
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: res.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra trong quá trình xử lý.'
                        });
                    }
                });
            }
        });
    });

    $(document).on('submit', '#form-add-ungvien', function(e) {
        e.preventDefault();
        var $form = $(this);
        var ten = $.trim($form.find('[name="ten"]').val());
        if (!ten) {
            Swal.fire({ icon: 'warning', title: 'Thiếu thông tin', text: 'Vui lòng nhập họ tên ứng viên.' });
            return;
        }

        $.ajax({
            url: 'ajax/ajax_add_ungvien.php',
            type: 'POST',
            dataType: 'json',
            data: $form.serialize(),
            beforeSend: function() {
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    onOpen: () => { Swal.showLoading(); }
                });
            },
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Thành công', text: res.message }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: res.message });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra trong quá trình xử lý.' });
            }
        });
    });

    $(document).on('click', '#btn-toggle-info', function() {
        if ($(window).width() < 992) {
            $('#info-panel-content').slideToggle(200, function() {
                if ($(this).is(':visible')) {
                    $('#toggle-info-text').text('[Ẩn]');
                } else {
                    $('#toggle-info-text').text('[Hiện]');
                }
            });
        }
    });
})
</script>
<?php } ?>

<?php if (isset($_SESSION['popup_success'])) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Thành công / 成功',
            text: '<?= $_SESSION['popup_success'] ?>',
            confirmButtonColor: '#2f6fed',
            confirmButtonText: 'Đóng / 閉じる'
        });
    });
</script>
<?php unset($_SESSION['popup_success']); } ?>

<?php if (isset($_SESSION['popup_error'])) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi / エラー',
            text: '<?= $_SESSION['popup_error'] ?>',
            confirmButtonColor: '#e0563f',
            confirmButtonText: 'Đóng / 閉じる'
        });
    });
</script>
<?php unset($_SESSION['popup_error']); } ?>