jQuery.event.special.touchstart = {
    setup: function (_, ns, handle) {
        if (ns.includes("noPreventDefault")) {
            this.addEventListener("touchstart", handle, { passive: false });
        } else {
            this.addEventListener("touchstart", handle, { passive: true });
        }
    }
};
$(document).ready(function () {
    $("input").attr("autocomplete", "off");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
function pageReload() {
    setTimeout(function () {
        location.reload(true);
    }, 3000);
}
function showToastMessage(toast_type = "success", message = "") {
    var toast_type = toast_type.toLowerCase();
    if (toast_type == "warning") {
        $.toast({
            heading: 'Warning',
            text: message,
            position: 'top-right',
            icon: 'warning',
            allowToastClose: false,
            stack: false
        });
    } else if (toast_type == "info") {
        $.toast({
            heading: 'Info',
            text: message,
            position: 'top-right',
            icon: 'info',
            allowToastClose: false,
            stack: false
        });
    } else if (toast_type == "error") {
        $.toast({
            heading: 'Error',
            text: message,
            position: 'top-right',
            icon: 'error',
            allowToastClose: false,
            stack: false,
            hideAfter: 4000,
        });
    } else {
        $.toast({
            heading: 'Success',
            text: message,
            position: 'top-right',
            icon: 'success',
            allowToastClose: false,
            stack: false,
            hideAfter: 3000,
        });
    }
}
function initializeCKEditor(elementId, height, folderPath) {
    CKEDITOR.replace(elementId, {
        height: height,
        filebrowserUploadUrl:
            uploadImageUrl +
            "?_token=" +
            document.querySelector('meta[name="csrf-token"]').getAttribute("content") +
            "&folder=" + folderPath + "/editor_images",
        filebrowserImageUploadUrl:
            uploadImageUrl +
            "?_token=" +
            document.querySelector('meta[name="csrf-token"]').getAttribute("content") +
            "&folder=" + folderPath,
    });
}
