(function(){
    'use strict';
    var form=document.getElementById('visa-application-form');
    if(!form)return;
    var allowed=['image/jpeg','image/png','image/webp','application/pdf'];
    form.querySelectorAll('input[type=file]').forEach(function(input){
        input.addEventListener('change',function(){
            var output=input.closest('.visa-document-card').querySelector('.visa-file-name');
            output.classList.remove('has-file');
            if(!input.files.length){output.textContent='未選択 / Chưa chọn';return;}
            var file=input.files[0];
            if(file.size>10*1024*1024||allowed.indexOf(file.type)===-1){
                input.value=''; output.textContent='JPG, PNG, WebP, PDF（最大10 MB）'; return;
            }
            output.textContent='✓ '+file.name; output.classList.add('has-file');
        });
    });
    form.addEventListener('submit',function(event){
        if(!form.checkValidity()){
            event.preventDefault();
            form.reportValidity();
            var invalid=form.querySelector(':invalid'); if(invalid)invalid.focus(); return;
        }
        var button=form.querySelector('button[type=submit]');
        button.disabled=true; button.textContent='送信中… / Đang gửi…';
    });
}());
