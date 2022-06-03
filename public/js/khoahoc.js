function addLopHoc() {
    $('#myModal').modal('show');
}
function addLopHocTS() {
    $('#myModalTS').modal('show');
}
$("#hinh_anh_khoa_hoc").change(function () {
    readURL(this, '#hinh_anh_khoa_hoc_preview');
});
function readURL(input, selector) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $('#saveLopHoc').click(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var datastring = $("#preview_form").serialize();
        console.log(datastring);
        $.ajax({
            url: "/lop-hoc/add",
            method: 'post',
            data: datastring,
            success: function(result){
                if(result.errors)
                {
                    $('.alert-danger').html('');
                    $.each(result.errors, function(key, value){
                        $('.alert-danger').show();
                        $('.alert-danger').append('<li>'+value+'</li>');
                    });
                }
                else
                {
                    $('.alert-danger').hide();
                    $('#open').hide();
                    $('#myModal').modal('hide');
                    location.reload();
                }
            }});
    });
});
