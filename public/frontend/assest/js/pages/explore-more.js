$(document).on("click", "#loadMoreBtn", function () {
    let button = $(this);

    $.ajax({
        url: exploreMoreUrl,
        type: "GET",
        data: { page: page },
        beforeSend: function () {
            button.prop("disabled", true).text("Loading...");
        },
        success: function (response) {
            if (response.properties) {
                $("#propertyContainer").append(response.properties);
                page++;

                if (!response.hasMore) {
                    button.hide();
                } else {
                    button.prop("disabled", false).text("Explore More");
                }
            }
        },
        error: function () {
            button.prop("disabled", false).text("Explore More");
        }
    });
});