

$(document).ready(function() {
    // var handler = StripeCheckout.configure({
    //     key: 'pk_test_RktRYcffDgayxWK6b7Gho9Ol',
    //     image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
    //     token: function(token, args) {
    //         // Use the token to create the charge with a server-side script.
    //         // You can access the token ID with `token.id`
    //     }
    // });


    console.log(handler);

    // $('#customButton').on('click', function(e) {
    //     var amountInCents = Math.floor($("#amountInDollars").val() * 100);
    //     var displayAmount = parseFloat(Math.floor($("#amountInDollars").val() * 100) / 100).toFixed(2);
    //     // Open Checkout with further options
    //     handler.open({
    //         name: 'Demo Site',
    //         description: 'Custom amount ($' + displayAmount + ')',
    //         amount: amountInCents,
    //     });
    //     e.preventDefault();
    // });

// Close Checkout on page navigation
//     $(window).on('popstate', function() {
//     handler.close();
// });
//
//     $("#customButton").click(function(e) {
//     let moneyStripe = 0;
//     let money = +$("#txtMoney").val();
//     let discount = +$(".txtDiscount").val();
//
//     if(discount != 0) {
//     moneyStripe = discount / 200;
// }
//     else {
//     moneyStripe = money / 200;
// }
//     var amountInCents = Math.floor(moneyStripe);
//     var displayAmount = parseFloat(Math.floor(moneyStripe)).toFixed(2);
//
//     handler.open({
//     name: 'Demo Site',
//     description: 'Custom amount ($' + displayAmount/100 + ')',
//     amount: amountInCents,
// });
// })
//
//     $(".payment").click(function() {
//     const value = $(this).data("value");
//     $("#type-payment").val(value);
//     if (value==1)
// {
//     $("#btnDangKi").show();
//     return;
// }
//     if (value==2)
// {
//     $("#btnDangKi").show();
//     return;
// }
//     if (value==3)
// {
//     console.log(123);
//     $("#btnDangKi").hide();
//     return;
// }
//
// });
//
//     $("#btnXacNhan").click(function() {
//     $("#result").hide();
//     var coupon = $('#txtCoupon').val();
//     var money = $('#txtMoney').val();
//     $.ajax({
//     url: 'getcoupon.php',
//     type: 'POST',
//     dataType: 'json',
//     data: {
//     coupon
// }
// }).success(function(result) {
//     //   alert("Th??nh c??ng");
//     //  console.log(result.data);
//     if (result.data == 0)
// {
//     $("#result").show().html("M?? kh??ng h???p l???").css('color', 'red');
// }
//     else
// {
//     if (result.date ==2 )
// {
//     $("#result").show().html("M?? ???? h???t h???n").css('color', 'red');
// }
//     else {
//     const moneyDiscount = money * result.giamgia / 100;
//     $('.stripe-button').data('amount', moneyDiscount);
//     // Open Checkout with further options
//     $('.txtDiscount').val(money - moneyDiscount);
//     var nana = (money - moneyDiscount).toLocaleString('it-IT', {
//     style: 'currency',
//     currency: 'VND'
// });
//     $("#result").show().html("Gi?? ti???n gi???m xu???ng c??n" + nana + "VND").css('color', 'red');
// }
// }
// });
// })
    var handler = StripeCheckout.configure({
        key: 'pk_test_RktRYcffDgayxWK6b7Gho9Ol',
        image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
        token: function(token) {
            $("#stripeToken").val(token.id);
            $("#stripeEmail").val(token.email);
            // $("#amountInCents").val(Math.floor($("#amountInDollars").val() * 100));
            $("#amountInCents").val(doiTien());

            $("#amountInCents").val();
            $("#contact-form").submit();
        }
    });
    $(window).on('popstate', function() {
        handler.close();
    });
    function doiTien(){
        let moneyStripe = 0;
        let money = +$("#txtMoney").val();
        let discount = +$(".txtDiscount").val();

        if(discount != 0) {
            moneyStripe = discount / 230;
        }
        else {
            moneyStripe = money / 230;
        }
        return moneyStripe;
    }
    $("#customButton").click(function(e) {
       var moneyStripe = doiTien();
        var amountInCents = Math.floor(moneyStripe);
        var displayAmount = parseFloat(Math.floor(moneyStripe)).toFixed(2);

        handler.open({
            name: 'Demo Site',
            description: 'Custom amount ($' + displayAmount/100 + ')',
            amount: amountInCents,
        });
    });
    $("#Save").click(function(e) {
        var d = new Date();
        let dates = $('#form-date').val();
        var dates1 = dates.split("-");
        var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
        if ($('#form-email').val()=="") {
            Swal.fire(
                'C???nh B??o',
                'B???n kh??ng ???????c b???o tr???ng email',
                'warning'
            )
        } else if($('#form-name').val()=="")  {
            Swal.fire(
                'C???nh B??o',
                'B???n kh??ng ???????c b???o tr???ng h??? v?? t??n',
                'warning'
            )
        } else if ($('#form-date').val()==""){
            Swal.fire(
                'C???nh B??o',
                'B???n kh??ng ???????c b???o tr???ng ng??y sinh',
                'warning'
            )
        }
        else{
            $("#contact-form").submit();
        }
    });
    $("#btnKhuyenMai").click(function() {
        $("#result").hide();
        var coupon = $('#txtCoupon').val();
        var money = $('#txtMoney').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var datastring = $("#contact-form").serialize();
        console.log(datastring)
        $.ajax({
            url: "/check-coupon",
            method: 'post',
            dataType: 'json',
            data: datastring,
            success: function(result) {
                if (result.data == 0) {
                    $("#result").show().html("M?? kh??ng h???p l???").css('color', 'red');
                } else {
                    if (result.date == 2) {
                        $("#result").show().html("M?? ???? h???t h???n").css('color', 'red');
                    }else if (result.trang_thai == 1){
                        $("#result").show().html("M?? ???? ???????c s??? d???ng").css('color', 'red');
                    }
                    else {
                        const moneyDiscount = money * result.giamgia / 100;
                        $('.stripe-button').data('amount', moneyDiscount);
                        // Open Checkout with further options
                        $('.txtDiscount').val(money - moneyDiscount);
                        var nana = (money - moneyDiscount).toLocaleString('it-IT', {
                            style: 'currency',
                            currency: 'VND'
                        });
                        $("#result").show().html("Gi?? ti???n gi???m xu???ng c??n " + nana).css('color', 'red');
                    }
                }
            }
            });

        // $("#result").hide();
        // var coupon = $('#txtCoupon').val();
        // var money = $('#txtMoney').val();
        // $.ajax({
        //     url: 'getcoupon.php',
        //     type: 'POST',
        //     dataType: 'json',
        //     data: {
        //         coupon
        //     }
        // }).success(function(result) {
        //     //   alert("Th??nh c??ng");
        //     //  console.log(result.data);
        //     if (result.data == 0)
        //     {
        //         $("#result").show().html("M?? kh??ng h???p l???").css('color', 'red');
        //     }
        //     else
        //     {
        //         if (result.date ==2 )
        //         {
        //             $("#result").show().html("M?? ???? h???t h???n").css('color', 'red');
        //         }
        //         else {
        //             const moneyDiscount = money * result.giamgia / 100;
        //             $('.stripe-button').data('amount', moneyDiscount);
        //             // Open Checkout with further options
        //             $('.txtDiscount').val(money - moneyDiscount);
        //             var nana = (money - moneyDiscount).toLocaleString('it-IT', {
        //                 style: 'currency',
        //                 currency: 'VND'
        //             });
        //             $("#result").show().html("Gi?? ti???n gi???m xu???ng c??n" + nana + "VND").css('color', 'red');
        //         }
        //     }
        })
});
