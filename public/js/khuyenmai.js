$("#hinh_anh_khuyen_mai").change(function () {
    readURL(this, '#hinh_anh_khuyen_mai_preview');
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