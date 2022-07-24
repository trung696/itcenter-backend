$(document).ready(function () {
  $("#id_khoa_hoc").on("change", function () {
    const listLopHoc = $("#id_lop_hoc");
    const selectvalue = this.value ?? 0;
    //

    listLopHoc.find("option").remove();

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
      },
    });
    $.ajax({
      url: `/api/course/${selectvalue}`,
      method: "get",
      data: {},
      success: function (result) {
        if (result.errors) {
          console.log("result.errors", result.errors);
          return;
        }
        if (result?.data?.length > 0) {
          $.each(result?.data, function (index, item) {
            listLopHoc.append(new Option(item?.name, item?.id));
          });
          listLopHoc.val("");
        }
      },
    });
  });
});
