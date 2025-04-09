$(document).ready(function () {

    var dataTableMain = $('#dataTableMain').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: apiUrl,
        },
        columns: [
            { 
                data: 'project_name', 
                name: 'project_name', 
                orderable: false,
                render: function (data, type, row) {
                    return `<div style="white-space: pre-wrap;">${data}</div>`;
                }
             },
            { data: 'name', name: 'name' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },

        ],
    });
});
