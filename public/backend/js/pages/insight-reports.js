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
            { 
                data: 'project_name', 
                name: 'property.project_name',
                render: function (data, type, row) {
                    return `<div style="white-space: pre-wrap;">${data}</div>`;
                }
             },
            { data: 'user_name', name: 'user.name' },
            { data: 'user_email', name: 'user.email' },
            { data: 'user_mobile', name: 'user.mobile' },
            { data: 'created_at', name: 'created_at' },
        ],
    });
});
