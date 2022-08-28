$(document).ready(function () {
  $("#id_khoa_hoc_cu").on("change", function () {
    const listLopHoc = $("#id_lop_hoc_cu");
    const listLopHocMoi = $("#id_lop_hoc_moi").val();

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

          const validClasses = result?.data?.filter(
            (item) =>
              new Date(item.start_date).getTime() > today.getTime() &&
              item?.slot > 0
          );
          $.each(validClasses, function (index, item) {
            if (item.id == listLopHocMoi) {
              listLopHoc.append(
                `<option value=${item?.id} disabled="disabled">${item?.name}</option>`
              );
              return;
            }
            listLopHoc.append(new Option(item?.name, item?.id));
          });
          listLopHoc.val("");
        }
      },
    });
  });
});

$(document).ready(function () {
  $("#id_khoa_hoc_moi").on("change", function () {
    const listLopHoc = $("#id_lop_hoc_moi");
    const listLopHocCu = $("#id_lop_hoc_cu").val();

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

          const validClasses = result.data.filter(
            (item) => new Date(item.start_date).getTime() > today.getTime()
          );

          $.each(validClasses, function (index, item) {
            if (item.id == listLopHocCu) {
              listLopHoc.append(
                `<option value=${item?.id} disabled="disabled">${item?.name} - đã được chọn</option>`
              );
              return;
            }
            listLopHoc.append(new Option(item?.name, item?.id));
          });
          listLopHoc.val("");
        }
      },
    });
  });
});
