$(document).ready(function () {

    var dataTableMain = $('#dataTableMain').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: apiUrl,
        },
        order : [0, 'desc'],
        columns: [
            { data: 'id', name: 'id', visible : false },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },

        ],
    });
});
