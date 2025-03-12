$(document).ready(function () {
    if (deviceType == 'desktop') {
        $('.tab-content').on('scroll', function () {
            var container = $(this);
            if ($('#pills-home').hasClass('active')) {
                if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                    50) {
                    loadMoreProjects();
                }
            } else if ($('#pills-profile').hasClass('active')) {
                if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                    50) {
                    loadMoreAppraisal();
                }
            } else if ($('#pills-match').hasClass('active')) {
                if (container.scrollTop() + container.innerHeight() >= container[0].scrollHeight -
                    50) {
                    loadMoreBestMatch();
                }
            }
        });
    }

    if (deviceType == 'mobile') {
        $(window).on('scroll', function () {
            if ($('#pills-home').hasClass('active')) {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                    loadMoreProjects();
                }
            } else if ($('#pills-profile').hasClass('active')) {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                    loadMoreAppraisal();
                }
            } else if ($('#pills-match').hasClass('active')) {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 900) {
                    loadMoreBestMatch();
                }
            }
        });
    }

    $('#applyFilter').click(function () {
        page = 0;
        lastPage = false;
        $('#pills-home .row').empty();
        loadMoreProjects();
    });

    $('.city_click_header').click(function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#city_header').val(id);
        $('#city_header_name').text(name);
        $('a.cityClick i').toggleClass('rotate');

        page = 0;
        lastPage = false;
        $('#pills-home .row').empty();
        loadMoreProjects();
    });

    $('#clear_search').click(function () {
        page = 0;
        lastPage = false;
        $('#pills-home .row').empty();
        loadMoreProjects();
    });
});

function loadMoreProjects() {
    if (isLoading || lastPage) return;
    isLoading = true;
    page++;

    var city = $("#city_header").val();
    var search = $("#filter_search").val();
    let propertyType = $('[name="property_type"]:checked').val();
    let subTypes_o = $('[name="property_sub_types[]"]:checked').map(function () {
        return $(this).val();
    }).get();
    let bhk = $('[name="bhk[]"]:checked').map(function () {
        return $(this).val();
    }).get();
    let amenities = $('[name="amenities[]"]:checked').map(function () {
        return $(this).val();
    }).get();
    let minPrice = $('#slider-min').val();
    let maxPrice = $('#slider-max').val();

    $.ajax({
        url: allProjectsUrl,
        type: "GET",
        data: {
            page: page,
            city: city,
            search: search,
            property_type: propertyType,
            property_sub_types: subTypes_o,
            bhk: bhk,
            amenities: amenities,
            minPrice: minPrice,
            maxPrice: maxPrice
        },
        success: function (response) {
            if (response.status && response.data.data.length) {
                var newProjects = response.data.data;
                if (page == 1) {
                    projects = [];
                }
                projects = projects.concat(newProjects);
                if (deviceType == 'desktop') {
                    initMap();
                }
                newProjects.forEach(function (proj) {
                    $('#pills-home .row').append(renderPropertyCard(proj,
                        amenities));
                });
                if (response.data.current_page >= response.data.last_page) {
                    lastPage = true;
                }
            } else {
                lastPage = true;
            }
        },
        error: function () { },
        complete: function () {
            isLoading = false;
        }
    });
}

function loadMoreAppraisal() {
    if (isAppraisalLoading || appraisalLastPage) return;
    isAppraisalLoading = true;
    appraisalPage++;
    $.ajax({
        url: allAppraisalUrl,
        type: "GET",
        data: {
            page: appraisalPage
        },
        success: function (response) {
            if (response.status && response.data.data.length) {
                var newProjects = response.data.data;
                newProjects.forEach(function (proj) {
                    $('#pills-profile .row').append(renderProject(proj));
                });
                if (response.data.current_page >= response.data.last_page) {
                    appraisalLastPage = true;
                }
            } else {
                appraisalLastPage = true;
            }
        },
        error: function () { },
        complete: function () {
            isAppraisalLoading = false;
        }
    });
}

function loadMoreBestMatch() {
    if (isBestMatchLoading || bestMatchLastPage) return;
    isBestMatchLoading = true;
    bestMatchPage++;
    $.ajax({
        url: allBestMatchUrl,
        type: "GET",
        data: {
            page: bestMatchPage
        },
        success: function (response) {
            if (response.status && response.data.data.length) {
                var newProjects = response.data.data;
                newProjects.forEach(function (proj) {
                    $('#pills-match .row').append(renderProject(proj));
                });
                if (response.data.current_page >= response.data.last_page) {
                    bestMatchLastPage = true;
                }
            } else {
                bestMatchLastPage = true;
            }
        },
        error: function () { },
        complete: function () {
            isBestMatchLoading = false;
        }
    });
}

function renderProject(project) {
    var isWishlisted = project.wishlisted_by_users
        .some(function (user) {
            return user.id === authId;
        });
    var html = '<div class="col-md-6">';
    html += '<div class="propertyCard" data-id="' + project.id +
        '">';
    html += '<div class="imgBox">';
    html += '<img src="' + assetUrl + 'frontend/assest/images/property-img.png" alt="property-img">';
    html +=
        '<div class="imgheader"><span>Best for Investment</span><i data-id="' + project.id +
        '" class="' + (isWishlisted ? 'fa-solid' : 'fa-regular') +
        ' fa-heart heartIconFill"></i></div>';
    html += '</div>';
    html += '<div class="priceBox">';
    html += '<div class="price"><h5>₹' + project.price_from + 'L-' + project.price_to + 'Cr</h5></div>';
    html += '<div class="boxLogo"><img src="' + assetUrl +
        'frontend/assest/images/x-btn.png" alt="x-btn"><span>' + (project.exio_suggest_percentage ||
            0) + '%</span></div>';
    html += '</div>';
    html += '<div class="propertyName"><h5>' + project.project_name + '</h5></div>';
    html += '<div class="locationProperty">';
    html += '<div class="homeBox comBox">';
    html += '<img src="' + assetUrl + 'frontend/assest/images/Home.png" alt="Home">';
    var floorPlans = "";
    if (project.floor_plans && project.floor_plans.length) {
        floorPlans = project.floor_plans.map(function (plan) {
            return plan.carpet_area + " Sqft";
        }).join(", ");
    }
    html += '<p>' + (project.custom_property_type || '') + ' | ' + floorPlans + ' | ' + (project
        .location ? project.location.location_name : '') + '</p>';
    html += '</div>';
    html += '</div>';
    html += '<div class="addressBox">';
    html += '<img src="' + assetUrl + 'frontend/assest/images/Home.png" alt="Home">';
    html += '<p>Lift, Gym, Park, Club House Lift, Gym, Park, Club House</p>';
    html += '<a href="javascript:void(0)">more</a>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}