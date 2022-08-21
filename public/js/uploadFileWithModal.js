$(document).ready(function () {
  $("#search-input").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    console.log(value);
    $("#myTable tr").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $("#blah").attr("src", e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function clearInputFile() {
  const input = document.querySelector("input[name='file_upload']");
  $("#blah").attr(
    "src",
    "data:image/png;base64,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"
  );

  input.value = "";
}
