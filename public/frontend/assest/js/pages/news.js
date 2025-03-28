$(document).on("click", "#loadMoreBtn", function () {
    let button = $(this);

    $.ajax({
        url: loadaMoreUrl,
        type: "GET",
        data: { page: page },
        beforeSend: function () {
            button.prop("disabled", true).text("Loading...");
        },
        success: function (response) {
            if (response.news) {
                $("#newsContainer").append(response.news);
                page++;

                if (!response.hasMore) {
                    button.hide();
                } else {
                    button.prop("disabled", false).text("Load More");
                }
            }
        },
        error: function () {
            button.prop("disabled", false).text("Load More");
        }
    });
});