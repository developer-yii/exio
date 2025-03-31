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

    $('#resetFilter').click(function () {
        
        // Reset radio buttons (property_type)
        document.querySelectorAll("input[name='property_type']").forEach((radio, index) => {
            radio.checked = index === 0; // Check the first radio button by default
        });
    
        // Uncheck all checkboxes (bhk, amenities)
        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
            checkbox.checked = false;
        });
    
        // Reset range sliders
        const minSlider = document.getElementById("slider-min");
        const maxSlider = document.getElementById("slider-max");
    
        minSlider.value = minSlider.getAttribute("min");
        maxSlider.value = maxSlider.getAttribute("max");
    
        document.getElementById("slider-min-value").textContent = minSlider.getAttribute("min");
        document.getElementById("slider-max-value").textContent = maxSlider.getAttribute("max");
    
        // Hide elements that were shown based on user input
        document.getElementById("bhk-filter").classList.add("d-none");
        document.querySelector(".show-sub-type").classList.add("d-none");
    
        // Hide additional amenities if they were shown
        document.querySelectorAll(".hidden-amenity").forEach(item => item.classList.add("d-none"));
    
        // Reset sub-type box
        document.getElementById("property-sub-type").innerHTML = "";
        
        
    });
    $('#applyFilter').click(function () {
        page = 0;
        lastPage = false;
        $('#pills-home .row').empty();
        $('.porpertyFilter').removeClass('open');
        loadMoreProjects(true);
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

function loadMoreProjects(filterBox = false) {
    if (isLoading || lastPage) return;
    isLoading = true;
    page++;

    console.log(filterBox);
    var city = $("#city_header").val();
    var search = $("#filter_search").val();
    let propertyType = $('[name="property_type"]:checked').val();
    let subTypes_o = $('[name="property_sub_types[]"]:checked').map(function () {
        return $(this).val();
    }).get();
    let bhk = $('[name="bhk[]"]:checked').map(function () {
        return $(this).val();
    }).get();
    let amenities_filter = $('[name="amenities[]"]:checked').map(function () {
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
            amenities: amenities_filter,
            minPrice: minPrice,
            maxPrice: maxPrice,
            filterApply: filterBox ? 1 : 0
        },
        success: function (response) {

            if(response.data.data.length == 0 && page < 2){
                $('#pills-home .row').html('<p class="not-found">No property found</p>');
            }
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
                // $('#pills-home .row').html('<p class="not-found">No property found</p>');
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
                    $('#pills-profile .row').append(renderPropertyCard(proj, amenities));
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
                    $('#pills-match .row').append(renderPropertyCard(proj, amenities));
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