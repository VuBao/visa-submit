function modalNotify(text)
{
    $("#popup-notify").find(".modal-body").html(text);
    $('#popup-notify').modal('show');
}

function ValidationFormSelf(ele='')
{
    if(ele)
    {
        $("."+ele).find("input[type=submit]").removeAttr("disabled");
        $("."+ele).find("button[type=submit]").removeAttr("disabled");
        var forms = document.getElementsByClassName(ele);
        var validation = Array.prototype.filter.call(forms,function(form){
            // Listen to file inputs change to clear is-invalid class on text inputs
            $(form).find('input[type="file"]').on('change', function() {
                var textInput = $(this).siblings('input[type="text"]');
                if (this.files && this.files.length > 0) {
                    textInput.removeClass('is-invalid');
                } else if ($(this).prop('required') && !$(this).val()) {
                    textInput.addClass('is-invalid');
                }
            });

            form.addEventListener('submit', function(event){
                if(form.checkValidity() === false)
                {
                    event.preventDefault();
                    event.stopPropagation();

                    // Style representative text inputs for hidden required file inputs
                    $(form).find('input[type="file"]:invalid').each(function() {
                        $(this).siblings('input[type="text"]').addClass('is-invalid');
                    });

                    // Scroll to the first invalid field
                    var invalidElements = form.querySelectorAll(':invalid');
                    if (invalidElements.length > 0) {
                        var firstInvalid = invalidElements[0];
                        var scrollToElement = firstInvalid;

                        // If element is hidden (like d-none file input), scroll to visible representation or parent
                        if (firstInvalid.classList.contains('d-none') || window.getComputedStyle(firstInvalid).display === 'none') {
                            var visibleSibling = firstInvalid.parentElement.querySelector('input:not(.d-none):not([type="hidden"]), select:not(.d-none), textarea:not(.d-none)');
                            if (visibleSibling && window.getComputedStyle(visibleSibling).display !== 'none') {
                                scrollToElement = visibleSibling;
                            } else {
                                var parentWrapper = firstInvalid.closest('.profile-field, .intent-field, .form-group, .input-wrap, .input-contact');
                                if (parentWrapper) {
                                    scrollToElement = parentWrapper;
                                }
                            }
                        }

                        // Scroll smoothly
                        var offsetMenu = 0;
                        if ($("#menu").length) {
                            offsetMenu = $("#menu").height();
                        }
                        var scrollTopValue = $(scrollToElement).offset().top - (offsetMenu * 2) - 30;
                        $('html, body').animate({
                            scrollTop: scrollTopValue
                        }, 500);

                        // Focus the first invalid element if it is visible
                        if (window.getComputedStyle(firstInvalid).display !== 'none') {
                            firstInvalid.focus();
                        }
                    }
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
}

function loadPagingAjax(url='',eShow='')
{
    if($(eShow).length && url)
    {
        $.ajax({
            url: url,
            type: "GET",
            data: {
                eShow: eShow
            },
            success: function(result){
                $(eShow).html(result);
            }
        });
    }
}

function loadTabAjax(url='',eShow='',eSlick='')
{
    if($(eShow).length && url)
    {
        VNS_FRAMEWORK.AjaxUnSlickProduct(eSlick);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                eShow: eShow
            },
            success: function(result){
                $(eShow).html(result);
                VNS_FRAMEWORK.AjaxSlickProduct(eSlick);
            }
        });
    }
}

function doEnter(event,obj)
{
    if(event.keyCode == 13 || event.which == 13) onSearch(obj);
}

function onSearch(obj) 
{           
    var keyword = $("#"+obj).val();
    
    if(keyword=='')
    {
        modalNotify(LANG['no_keywords']);
        return false;
    }
    else
    {
        location.href = "tim-kiem?keyword="+encodeURI(keyword);
        loadPage(document.location);            
    }
}

function goToByScroll(id)
{
    var offsetMenu = 0;
    id = id.replace("#", "");
    if($("#menu").length) offsetMenu = $("#menu").height();
    $('html,body').animate({
        scrollTop: $("#" + id).offset().top - (offsetMenu * 2)
    }, 'slow');
}

function update_cart(id=0,code='',quantity=1)
{
    if(id)
    {
        var ship = $(".price-ship").val();

        $.ajax({
            type: "POST",
            url: "ajax/ajax_cart.php",
            dataType: 'json',
            data: {cmd:'update-cart',id:id,code:code,quantity:quantity,ship:ship},
            success: function(result){
                if(result)
                {
                    $('.load-price-'+code).html(result.gia);
                    $('.load-price-new-'+code).html(result.giamoi);
                    $('.price-temp').val(result.temp);
                    $('.load-price-temp').html(result.tempText);
                    $('.price-total').val(result.total);
                    $('.load-price-total').html(result.totalText);
                }
            }
        });
    }
}

function load_district(id=0)
{
    $.ajax({
        type: 'post',
        url: 'ajax/ajax_district.php',
        data: {id_city:id},
        success: function(result){
            $(".select-district").html(result);
            $(".select-wards").html('<option value="">'+LANG['wards']+'</option>');
        }
    });
}

function load_wards(id=0)
{
    $.ajax({
        type: 'post',
        url: 'ajax/ajax_wards.php',
        data: {id_district:id},
        success: function(result){
            $(".select-wards").html(result);
        }
    });
}

function load_ship(id=0)
{
    if(SHIP_CART)
    {
        $.ajax({
            type: "POST",
            url: "ajax/ajax_cart.php",
            dataType: 'json',
            data: {cmd:'ship-cart',id:id},
            success: function(result){
                if(result)
                {
                    $('.load-price-ship').html(result.shipText);
                    $('.load-price-total').html(result.totalText);
                    $('.price-ship').val(result.ship);
                    $('.price-total').val(result.total);
                }   
            }
        }); 
    }
}