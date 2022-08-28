$(document).ready(function () {
  $("#id_khoa_hoc").on("change", function () {
    const listLopHoc = $("#id_lop_hoc");
    const listPrice = $("#price");
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
          const today = new Date();
          const validClasses = result?.data?.filter?.(
            (item) =>
              new Date(item?.start_date).getTime() > today.getTime() &&
              item?.slot > 0
          );

          $.each(validClasses, function (index, item) {
            listLopHoc.append(new Option(item?.name, item?.id));
          });
          listLopHoc.val("");
        }
      },
    });
  });
});

$(document).ready(function () {
  $("#email").on("change", function () {
    if (!this.value) {
      return;
    }

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
      },
    });

    $.ajax({
      url: `/api/hoc-vien-tim-kiem`,
      method: "post",
      data: {
        email: this?.value,
      },
      success: function (result) {
        if (result.errors) {
          return;
        }

        if (result?.data?.ho_ten) {
          $("#ho_ten").val(result?.data?.ho_ten);
          $("#cccd").val(result?.data?.cccd);
          $("#so_dien_thoai").val(result?.data?.so_dien_thoai);
          $("#gioi_tinh").val(result?.data?.gioi_tinh);
          $("#ngay_sinh").val(result?.data?.ngay_sinh);
          return;
        }
        $("#ho_ten").val("");
        $("#cccd").val("");
        $("#so_dien_thoai").val("");
        $("#gioi_tinh").val("");
        $("#ngay_sinh").val("");
      },
      error: function (error) {
        console.log("Error:");
        console.log(error);
      },
    });
  });
});
