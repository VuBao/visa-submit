/* Validation form */
ValidationFormSelf("validation-newsletter");
ValidationFormSelf("validation-cart");
ValidationFormSelf("validation-user");
ValidationFormSelf("validation-contact");

/* Exists */
$.fn.exists = function () {
    return this.length;
};

/* Paging ajax */
if ($(".paging-product").exists()) {
    loadPagingAjax("ajax/ajax_product.php?perpage=8", '.paging-product');
}

/* Paging category ajax */
if ($(".paging-product-category").exists()) {
    $(".paging-product-category").each(function () {
        var list = $(this).data("list");
        loadPagingAjax("ajax/ajax_product.php?perpage=8&idList=" + list, '.paging-product-category-' + list);
    })
}

/* Back to top */
VNS_FRAMEWORK.BackToTop = function () {
    $(window).scroll(function () {
        if (!$('.scrollToTop').length) $("body").append('<div class="scrollToTop"><img src="' + GOTOP + '" alt="Go Top"/></div>');
        if ($(this).scrollTop() > 100) $('.scrollToTop').fadeIn();
        else $('.scrollToTop').fadeOut();
    });

    $('body').on("click", ".scrollToTop", function () {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });
};

/* Alt images */
VNS_FRAMEWORK.AltImages = function () {
    $('img').each(function (index, element) {
        if (!$(this).attr('alt') || $(this).attr('alt') == '') {
            $(this).attr('alt', WEBSITE_NAME);
        }
    });
};

/* Fix menu */
VNS_FRAMEWORK.FixMenu = function () {
    $(window).scroll(function () {
        if ($(window).scrollTop() >= ($("#header").height() + $("#banner").height()))
            $("#menu").addClass('fixing');
        else
            $("#menu").removeClass('fixing');
    });
};

/* Tools */
VNS_FRAMEWORK.Tools = function () {
    if ($(".toolbar").exists()) {
        $(".footer").css({ marginBottom: $(".toolbar").innerHeight() });
    }
};

/* Popup */
VNS_FRAMEWORK.Popup = function () {
    if ($("#popup").exists()) {
        $('#popup').modal('show');
    }
};

/* Wow */
VNS_FRAMEWORK.WowAnimation = function () {
    new WOW().init();
};

/* Mmenu */
VNS_FRAMEWORK.Mmenu = function () {
    if ($("nav#mmenu").exists()) {
        $('nav#mmenu').mmenu({
            extensions: ['effect-slide-menu', 'pageshadow'],
            searchfield: false,
            counters: false,
            offCanvas: {
                position: "left"
            }
        });
    }
};

/* Toc */
VNS_FRAMEWORK.Toc = function () {
    if ($(".toc-list").exists()) {
        $(".toc-list").toc({
            content: "div#toc-content",
            headings: "h2,h3,h4"
        });

        if (!$(".toc-list li").length) $(".meta-toc").hide();

        $('.toc-list').find('a').click(function () {
            var x = $(this).attr('data-rel');
            goToByScroll(x);
        });
    }
};

/* Simply scroll */
VNS_FRAMEWORK.SimplyScroll = function () {
    if ($(".tintuc-r ul").exists()) {
        $(".tintuc-r ul").simplyScroll({
            customClass: 'vert',
            orientation: 'vertical',
            // orientation: 'horizontal',
            auto: true,
            manualMode: 'auto',
            pauseOnHover: 1,
            speed: 1,
            loop: 0
        });
    }
};

/* Tabs */
VNS_FRAMEWORK.Tabs = function () {
    if ($(".ul-tabs-pro-detail").exists()) {
        $(".ul-tabs-pro-detail li").click(function () {
            var tabs = $(this).data("tabs");
            $(".content-tabs-pro-detail, .ul-tabs-pro-detail li").removeClass("active");
            $(this).addClass("active");
            $("." + tabs).addClass("active");
        });
    }
};

/* Photobox */
VNS_FRAMEWORK.Photobox = function () {
    if ($(".album-gallery").exists()) {
        $('.album-gallery').photobox('a', { thumbs: true, loop: false });
    }
};

/* Datetime picker */
VNS_FRAMEWORK.DatetimePicker = function () {
    if ($('#ngaysinh').exists()) {
        $('#ngaysinh').datetimepicker({
            timepicker: false,
            format: 'd/m/Y',
            formatDate: 'd/m/Y',
            minDate: '01/01/1950',
            maxDate: TIMENOW
        });
    }
};

/* Search */
VNS_FRAMEWORK.Search = function () {
    if ($(".icon-search").exists()) {
        $(".icon-search").click(function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(".search-grid").stop(true, true).animate({ opacity: "0", width: "0px" }, 200);
            }
            else {
                $(this).addClass('active');
                $(".search-grid").stop(true, true).animate({ opacity: "1", width: "230px" }, 200);
            }
            document.getElementById($(this).next().find("input").attr('id')).focus();
            $('.icon-search i').toggleClass('fa fa-search fa fa-times');
        });
    }
};

/* Videos */
VNS_FRAMEWORK.Videos = function () {
    /* Fancybox */
    // $('[data-fancybox="something"]').fancybox({
    //     // transitionEffect: "fade",
    //     // transitionEffect: "slide",
    //     // transitionEffect: "circular",
    //     // transitionEffect: "tube",
    //     // transitionEffect: "zoom-in-out",
    //     // transitionEffect: "rotate",
    //     transitionEffect: "fade",
    //     transitionDuration: 800,
    //     animationEffect: "fade",
    //     animationDuration: 800,
    //     slideShow: {
    //         autoStart: true,
    //         speed: 3000
    //     },
    //     arrows: true,
    //     infobar: false,
    //     toolbar: false,
    //     hash: false
    // });

    if ($(".video").exists()) {
        $('[data-fancybox="video"]').fancybox({
            transitionEffect: "fade",
            transitionDuration: 800,
            animationEffect: "fade",
            animationDuration: 800,
            arrows: true,
            infobar: false,
            toolbar: true,
            hash: false
        });
    }
};

/* Owl */
VNS_FRAMEWORK.OwlPage = function () {
    if ($(".owl-slideshow").exists()) {
        $('.owl-slideshow').owlCarousel({
            items: 1,
            rewind: true,
            autoplay: true,
            loop: true,
            lazyLoad: true,
            mouseDrag: true,
            touchDrag: true,
            // animateIn: 'animate__animated animate__fadeInLeft',
            // animateOut: 'animate__animated animate__fadeOutRight',
            margin: 0,
            smartSpeed: 500,
            autoplaySpeed: 1500,
            autoplayTimeout: 10000,
            nav: false,
            dots: true
        });
        $('.prev-slideshow').click(function () {
            $('.owl-slideshow').trigger('prev.owl.carousel');
        });
        $('.next-slideshow').click(function () {
            $('.owl-slideshow').trigger('next.owl.carousel');
        });
    }
    if ($(".container-tinnoibat").exists()) {
        $('.container-tinnoibat').owlCarousel({
            items: 4,
            rewind: true,
            autoplay: false,
            loop: false,
            lazyLoad: true,
            mouseDrag: true,
            touchDrag: true,
            // animateIn: 'animate__animated animate__fadeInLeft',
            // animateOut: 'animate__animated animate__fadeOutRight',
            margin: 20,
            smartSpeed: 500,
            autoplaySpeed: 1500,
            autoplayTimeout: 10000,
            nav: false,
            dots: false,
            responsiveClass: true,
            responsiveRefreshRate: 200,
            responsive: {
                0: {
                    items: 1,
                    margin: 10,
               
                },
                400: {
                    items: 2,
                    margin: 10,
                   
                },
                600: {
                    items: 3,
                    margin: 20,
                   
                },
                768: {
                    items: 4,
                    margin: 20,
                   
                }
            }
        });

    }

    if ($(".container-tintuc").exists()) {
        $('.container-tintuc').owlCarousel({
            items: 3,
            rewind: true,
            autoplay: false,
            loop: false,
            lazyLoad: true,
            mouseDrag: true,
            touchDrag: true,
            // animateIn: 'animate__animated animate__fadeInLeft',
            // animateOut: 'animate__animated animate__fadeOutRight',
            margin: 20,
            smartSpeed: 500,
            autoplaySpeed: 1500,
            autoplayTimeout: 10000,
            nav: false,
            dots: false,
            responsiveClass: true,
            responsiveRefreshRate: 200,
            responsive: {
                0: {
                    items: 1,
                    margin: 10,
               
                },
                400: {
                    items: 2,
                    margin: 10,
                   
                },
                700: {
                    items: 3,
                    margin: 20,
                   
                },
            }
        });

    }

    if ($(".owl-doitac").exists()) {
        $('.owl-doitac').owlCarousel({
            items: 7,
            rewind: true,
            autoplay: true,
            loop: true,
            lazyLoad: false,
            mouseDrag: true,
            touchDrag: true,
            margin: 10,
            smartSpeed: 250,
            autoplaySpeed: 1000,
            nav: false,
            dots: false,
            responsiveClass: true,
            responsiveRefreshRate: 200,
            responsive: {
                0: {
                    items: 2,
                    margin: 10
                },
                400: {
                    items: 3,
                    margin: 10
                },
                600: {
                    items: 4,
                    margin: 10
                },
                768: {
                    items: 5,
                    margin: 10
                },
                992: {
                    items: 6,
                    margin: 10
                },
                1200: {
                    items: 7,
                    margin: 10
                }
            }
        });
        $('.prev-doitac').click(function () {
            $('.owl-doitac').trigger('prev.owl.carousel');
        });
        $('.next-doitac').click(function () {
            $('.owl-doitac').trigger('next.owl.carousel');
        });
    }
};

/* Owl pro detail */
VNS_FRAMEWORK.OwlProDetail = function () {
    if ($(".owl-thumb-pro").exists()) {
        $('.owl-thumb-pro').owlCarousel({
            items: 4,
            lazyLoad: false,
            mouseDrag: true,
            touchDrag: true,
            margin: 10,
            smartSpeed: 250,
            nav: false,
            dots: false
        });
        $('.prev-thumb-pro').click(function () {
            $('.owl-thumb-pro').trigger('prev.owl.carousel');
        });
        $('.next-thumb-pro').click(function () {
            $('.owl-thumb-pro').trigger('next.owl.carousel');
        });
    }
};



/* Slick page */
VNS_FRAMEWORK.SlickPage = function () {
    if ($(".slick-xuatnhapkhau").exists()) {
        $('.slick-xuatnhapkhau').slick({
            vertical: false,//Chay dọc
            slidesToShow: 3,    //Số item hiển thị
            slidesToScroll: 1, //Số item cuộn khi chạy
            autoplay: true,  //Tự động chạy
            autoplaySpeed: 3000,  //Tốc độ chạy
            speed: 1000,//Tốc độ chuyển slider
            arrows: false, //Hiển thị mũi tên
            dots: false,  //Hiển thị dấu chấm
        });
    }
};

/* Cart */
VNS_FRAMEWORK.Cart = function () {
    $("body").on("click", ".addcart", function () {
        var mau = ($(".color-pro-detail input:checked").val()) ? $(".color-pro-detail input:checked").val() : 0;
        var size = ($(".size-pro-detail input:checked").val()) ? $(".size-pro-detail input:checked").val() : 0;
        var id = $(this).data("id");
        var action = $(this).data("action");
        var quantity = ($(".qty-pro").val()) ? $(".qty-pro").val() : 1;

        if (id) {
            $.ajax({
                url: 'ajax/ajax_cart.php',
                type: "POST",
                dataType: 'json',
                async: false,
                data: { cmd: 'add-cart', id: id, mau: mau, size: size, quantity: quantity },
                success: function (result) {
                    if (action == 'addnow') {
                        $('.count-cart').html(result.max);
                        $.ajax({
                            url: 'ajax/ajax_cart.php',
                            type: "POST",
                            dataType: 'html',
                            async: false,
                            data: { cmd: 'popup-cart' },
                            success: function (result) {
                                $("#popup-cart .modal-body").html(result);
                                $('#popup-cart').modal('show');
                            }
                        });
                    }
                    else if (action == 'buynow') {
                        window.location = CONFIG_BASE + "gio-hang";
                    }
                }
            });
        }
    });

    $("body").on("click", ".del-procart", function () {
        if (confirm(LANG['delete_product_from_cart'])) {
            var code = $(this).data("code");
            var ship = $(".price-ship").val();

            $.ajax({
                type: "POST",
                url: 'ajax/ajax_cart.php',
                dataType: 'json',
                data: { cmd: 'delete-cart', code: code, ship: ship },
                success: function (result) {
                    $('.count-cart').html(result.max);
                    if (result.max) {
                        $('.price-temp').val(result.temp);
                        $('.load-price-temp').html(result.tempText);
                        $('.price-total').val(result.total);
                        $('.load-price-total').html(result.totalText);
                        $(".procart-" + code).remove();
                    }
                    else {
                        $(".wrap-cart").html('<a href="" class="empty-cart text-decoration-none"><i class="fa fa-cart-arrow-down"></i><p>' + LANG['no_products_in_cart'] + '</p><span>' + LANG['back_to_home'] + '</span></a>');
                    }
                }
            });
        }
    });

    $("body").on("click", ".counter-procart", function () {
        var $button = $(this);
        var quantity = 1;
        var input = $button.parent().find("input");
        var id = input.data('pid');
        var code = input.data('code');
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") quantity = parseFloat(oldValue) + 1;
        else if (oldValue > 1) quantity = parseFloat(oldValue) - 1;
        $button.parent().find("input").val(quantity);
        update_cart(id, code, quantity);
    });

    $("body").on("change", "input.quantity-procat", function () {
        var quantity = $(this).val();
        var id = $(this).data("pid");
        var code = $(this).data("code");
        update_cart(id, code, quantity);
    });

    if ($(".select-city-cart").exists()) {
        $(".select-city-cart").change(function () {
            var id = $(this).val();
            load_district(id);
            load_ship();
        });
    }

    if ($(".select-district-cart").exists()) {
        $(".select-district-cart").change(function () {
            var id = $(this).val();
            load_wards(id);
            load_ship();
        });
    }

    if ($(".select-wards-cart").exists()) {
        $(".select-wards-cart").change(function () {
            var id = $(this).val();
            load_ship(id);
        });
    }

    if ($(".payments-label").exists()) {
        $(".payments-label").click(function () {
            var payments = $(this).data("payments");
            $(".payments-cart .payments-label, .payments-info").removeClass("active");
            $(this).addClass("active");
            $(".payments-info-" + payments).addClass("active");
        });
    }

    if ($(".color-pro-detail").exists()) {
        $(".color-pro-detail").click(function () {
            $(".color-pro-detail").removeClass("active");
            $(this).addClass("active");

            var id_mau = $("input[name=color-pro-detail]:checked").val();
            var idpro = $(this).data('idpro');

            $.ajax({
                url: 'ajax/ajax_color.php',
                type: "POST",
                dataType: 'html',
                data: { id_mau: id_mau, idpro: idpro },
                success: function (result) {
                    if (result != '') {
                        $('.left-pro-detail').html(result);
                        MagicZoom.refresh("Zoom-1");
                        VNS_FRAMEWORK.OwlProDetail();
                    }
                }
            });
        });
    }

    if ($(".size-pro-detail").exists()) {
        $(".size-pro-detail").click(function () {
            $(".size-pro-detail").removeClass("active");
            $(this).addClass("active");
        });
    }

    if ($(".quantity-pro-detail span").exists()) {
        $(".quantity-pro-detail span").click(function () {
            var $button = $(this);
            var oldValue = $button.parent().find("input").val();
            if ($button.text() == "+") {
                var newVal = parseFloat(oldValue) + 1;
            }
            else {
                if (oldValue > 1) var newVal = parseFloat(oldValue) - 1;
                else var newVal = 1;
            }
            $button.parent().find("input").val(newVal);
        });
    }
};

/* Paging ajax */
VNS_FRAMEWORK.PagingAjax = function (list, element, type, perpage) {
    loadPagingAjax("ajax/ajax_paging.php?perpage=" + perpage + "&idList=" + list + "&type=" + type, element);
};

/* Paging product */
VNS_FRAMEWORK.PagingProduct = function () {
    if ($(".paging-product-category").exists()) {
        var list = $(".paging-product-category").data("list");
        VNS_FRAMEWORK.PagingAjax(list, '.paging-product-category', 'product', 8);
        $(".product-list li").click(function () {
            list = $(this).data("list");
            $(".product-list li").removeClass("active");
            $(this).addClass("active");
            VNS_FRAMEWORK.PagingAjax(list, '.paging-product-category', 'product', 8);
        });
    }
};

/* ToggleSearch */
VNS_FRAMEWORK.ToggleSearch = function () {
    if ($(".btn-search").exists()) {
        $(".search_open").click(function () {
            $(".search_box_hide").toggleClass('opening');
        });
    }
};

VNS_FRAMEWORK.ToggleLangguage = function () {
    if ($(".language-toggle").exists()) {
        $(".language-toggle").on("change", function () {
            const radioValue = $(this).val();
            if (radioValue) {
                window.location.href = CONFIG_BASE + "ngon-ngu-" + radioValue;
            }
        });
    }
};

/*Ajax bản đồ*/
VNS_FRAMEWORK.AjaxBando = function () {
    if ($(".click-map.active").exists()) {
        $(".click-map.active").each(function () {
            var id = $(this).data("id");
            loadPagingAjax("ajax/ajax_bando.php?id=" + id, '.load-map');
        });
        $('.click-map').click(function () {
            $(this).parents('.title-map').find('.click-map').removeClass('active');
            $(this).addClass('active');
            var id = $(this).data("id");
            loadPagingAjax("ajax/ajax_bando.php?id=" + id, '.load-map');
        });
    }
};

/*Hiệu ứng Logo*/
VNS_FRAMEWORK.LogoLoad = function () {
    $(document).ready(function () {
        var api = $(".sss").peShiner({ api: true, paused: true, reverse: true, repeat: 1, color: 'monoHL' }); //mã màu đặc biệt: monoHL, oceanHL, fireHL
        api.resume();

        var api1 = $(".sss1").peShiner({ api: true, paused: true, reverse: true, repeat: 1, color: 'monoHL' }); //mã màu đặc biệt: monoHL, oceanHL, fireHL
        api1.resume();
    });
};

/*Hiệu ứng Scroll*/
VNS_FRAMEWORK.scrollAos = function () {
    $(document).ready(function () {
        AOS.init();
    });
}

VNS_FRAMEWORK.photoSwiper = function () {
    $(document).ready(function () {

        $("#lightgallery").lightGallery({
            selector: 'a.gallery'
        });
    });
}

VNS_FRAMEWORK.swiper = function () {

    $(document).ready(function () {
        const sliderThumbs = new Swiper('.slider__thumbs .swiper-container', {
            loop: true,
            direction: 'vertical',
            slidesPerView: 3,
            spaceBetween: 24,
            grabCursor: true,
            navigation: {
                nextEl: '.slider__next',
                prevEl: '.slider__prev'
            },
            freeMode: true,
            breakpoints: {
                0: {
                    direction: 'horizontal',
                    spaceBetween: 13,
                },
                768: {
                    direction: 'vertical',
                }
            }
        });

        const sliderImages = new Swiper('.slider__images .swiper-container', {
            loop: true,
            direction: 'vertical',
            slidesPerView: 1,
            spaceBetween: 32,
            mousewheel: true,
            navigation: {
                nextEl: '.slider__next',
                prevEl: '.slider__prev'
            },
            grabCursor: true,
            thumbs: {
                swiper: sliderThumbs
            },
            breakpoints: {
                0: {
                    direction: 'horizontal',
                },
                768: {
                    direction: 'vertical',
                }
            }
        });
    })

    $('.input-open-file').click(function() {
        $("#avatar").click();
    });
    $('#avatar').change(function(e) {
        const file = e.target.files[0];
        $('.input-open-file').val('');
        filename = file.name;
        $('.input-open-file').val(filename);
    });
   

    $('#soyeulilich').click(function() {
        $("#file_soyeulilich").click();
    });
    $('#file_soyeulilich').change(function(e) {
        const file = e.target.files[0];
        $('#soyeulilich').val('');
        filename = file.name;
        $('#soyeulilich').val(filename);
    });

    $('#cv_input').click(function() {
        $("#file_cv").click();
    });
    $('#file_cv').change(function(e) {
        const file = e.target.files[0];
        $('#cv_input').val('');
        filename = file.name;
        $('#cv_input').val(filename);
    });

    $('#thengoaitruoc').click(function() {
        $("#file_thengoaitruoc").click();
    });
    $('#file_thengoaitruoc').change(function(e) {
        const file = e.target.files[0];
        $('#thengoaitruoc').val('');
        filename = file.name;
        $('#thengoaitruoc').val(filename);
    });

    $('#thengoaisau').click(function() {
        $("#file_thengoaisau").click();
    });
    $('#file_thengoaisau').change(function(e) {
        const file = e.target.files[0];
        $('#thengoaisau').val('');
        filename = file.name;
        $('#thengoaisau').val(filename);
    });

    $('#filter-dscv').change(function() {
        const value = $(this).val();
        window.location.href = "account/danh-sach-ung-tuyen?tinhtrang="+value;
    });

    $('#filter-vitri').change(function() {
        const value = $(this).val();
        const url = $(this).attr('data-url');
        window.location.href = url+"?vitri="+value;
    });

    $('#copy').click(function() {
        const link = $("#link").select();
        document.execCommand('copy');
        
        Swal.fire(
            'Đã copy link giới thiệu vào bộ nhớ tạm',
            'success'
        )
    });

    $('.wishlist').click(function(e) {
        e.preventDefault();
        const id = $(this).attr('data-news');
        let element = $(this);
       
        $.ajax({
            url: 'ajax/ajax_uuthich.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function (data) {
                if(data == 0) {
                    Swal.fire(
                        'Vui lòng thử lại',
                        'success'
                    )
                } else {
                    if(element.hasClass('active')) {
                        element.removeClass('active')
                    } else {
                        element.addClass('active');
                    }
                }
            },
        });
    });

    $('.morongdanhsach').click(function() {
        $('.table-gioithieu').toggle(500);
    });
}

$(".content-tabs-pro-detail table").wrap("<div class='responsive-item'></div>");

/* Ready */
$(document).ready(function () {
    VNS_FRAMEWORK.Tools();
    VNS_FRAMEWORK.Popup();
    VNS_FRAMEWORK.WowAnimation();
    VNS_FRAMEWORK.AltImages();
    //VNS_FRAMEWORK.BackToTop();
    VNS_FRAMEWORK.FixMenu();
    VNS_FRAMEWORK.Mmenu();
    VNS_FRAMEWORK.OwlPage();
    VNS_FRAMEWORK.OwlProDetail();
    VNS_FRAMEWORK.SlickPage();
    VNS_FRAMEWORK.Toc();
    VNS_FRAMEWORK.Cart();
    VNS_FRAMEWORK.SimplyScroll();
    VNS_FRAMEWORK.Tabs();
    VNS_FRAMEWORK.Videos();
    VNS_FRAMEWORK.Photobox();
    VNS_FRAMEWORK.Search();
    VNS_FRAMEWORK.DatetimePicker();
    VNS_FRAMEWORK.LogoLoad();
    // VNS_FRAMEWORK.ToggleSearch();
    // VNS_FRAMEWORK.PagingProduct();
    VNS_FRAMEWORK.scrollAos();
    VNS_FRAMEWORK.swiper();
    VNS_FRAMEWORK.photoSwiper();
    VNS_FRAMEWORK.ToggleLangguage();
});

function zoom(e) {
    var zoom = e.currentTarget;
    e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
    e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
    x = (offsetX / zoom.offsetWidth) * 100
    y = (offsetY / zoom.offsetHeight) * 100
    zoom.style.backgroundPosition = x + "% " + y + "%";
}

$(document).ready(function () {

    "use strict";
    var progressPath = document.querySelector('.progress-wrap path');
    var pathLength = progressPath.getTotalLength();
    progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
    progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
    progressPath.style.strokeDashoffset = pathLength;
    progressPath.getBoundingClientRect();
    progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
    var updateProgress = function () {
        var scroll = $(window).scrollTop();
        var height = $(document).height() - $(window).height();
        var progress = pathLength - (scroll * pathLength / height);
        progressPath.style.strokeDashoffset = progress;
    };
    updateProgress();
    $(window).scroll(updateProgress);
    var offset = 150;
    var duration = 550;
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > offset) {
            $('.progress-wrap').addClass('active-progress');
        } else {
            $('.progress-wrap').removeClass('active-progress');
        }
    });
    $('.progress-wrap').on('click', function (event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, duration);
        return false;
    });
});