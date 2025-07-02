$(document).ready(function () {
    $('#txt_B_name').val($(this).val());

});

$("#Cancel").click(function () {


    $("#txt_dep_name").val("");



});



// function gotoUpdate(id) {

//     var id = id;
//     // alert(id);
//     var form = new FormData();
//     form.append("id", id);

//     var r = new XMLHttpRequest();
//     r.onreadystatechange = function () {
//         if (r.readyState == 4) {
//             // window.location.replace(baseurl + "Master/OT_Pattern");
            
//         }
//     }

//     r.open("POST", baseurl + "Master/OT_Pattern/updateOt", true);
//     r.send(form);
// }




function delete_id() {
    swal({ title: "Are you sure?", text: "You will not be able to recover this data!", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, Delete This!", cancelButtonText: "No, Cancel This!", closeOnConfirm: false, closeOnCancel: false },
        function (isConfirm) {
            alert("ok");
            // if (isConfirm) {

            //     $.ajax({
            //         url: baseurl + "index.php/Master/Department/ajax_delete/" + id,
            //         type: "POST",
            //         dataType: "JSON",
            //         success: function (data) {

            //             //if success reload ajax table
            //             $('#modal_form').modal('hide');
            //             reload_table();
            //         }

            //     });


            //     swal("Deleted!", "Selected data has been deleted.", "success");


            //     $(document).ready(function () {
            //         setTimeout(function () {
            //             window.location.replace(baseurl + "Master/Department/");
            //         }, 1000);
            //     });


            // } else {
            //     swal("Cancelled", "Selected data Cancelled", "error");

            // }

        });

}


function createOTPatternArr() {
    myData = [];
    $("[id^='Day'").each(function () {

        elementIndex = this.id.replace("Day", "");

        myData.push({
            "Day": $("#Day" + elementIndex).val(),
            "Type": $("#Type" + elementIndex).val(),
            "chkBSH": $("#chkBSH" + elementIndex).val(),
            "MinTw": $("#MinTw" + elementIndex).val(),
            "ChkASH": $("#chkASH" + elementIndex).val(),
            "ASH_MinTw": $("#ASH_MinTw" + elementIndex).val(),
            "RoundUp": $("#RoundUp" + elementIndex).val()


        });

    });
    $("#hdntext").val(JSON.stringify(myData));
    // alert($("#hdntext").val());
}




