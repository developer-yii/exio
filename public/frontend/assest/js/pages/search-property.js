$(document).ready(function () {
    // function getUrlParam(param) {
    //     const urlParams = new URLSearchParams(window.location.search);
    //     return urlParams.get(param);
    // }

    const urlType = getUrlParam('type');
    const urlId = getUrlParam('id');
    let urlCity = getUrlParam('city');
    let urlSearch = getUrlParam('search');    
    const defaultCity = "All"; // Set your default city here

    function setCityName(selector, city) {
        $(selector).text(city ? decodeURIComponent(city) : defaultCity);
    }

    setCityName('#city_name_home', urlCity);

    if (urlSearch) {
        $('.clickListClass').val(decodeURIComponent(urlSearch));
    }

    $(".clickListClass").on("keyup", function () {
        handleSearch($(this), '.searchKeyup');
    });

    function handleSearch($input, parentClass) {

        var value = $input.val().toLowerCase();
        var $searchKey = $input.closest(parentClass).find('.search-key');

        if (value === "") {
            $searchKey.addClass('d-none');
            $searchKey.find('li, h6').show();
        } else {
            $searchKey.removeClass('d-none');
            $searchKey.find('li').each(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
            $searchKey.find('h6').each(function () {
                var $heading = $(this);
                var $lis = $heading.nextUntil('h6', 'li');
                if ($lis.filter(':visible').length === 0) {
                    $heading.hide();
                } else {
                    $heading.show();
                }
            });
            if ($searchKey.find('li:visible').length === 0) {
                $searchKey.addClass('d-none');
            }
        }
    }

    $('.search-key a').click(function () {
        const name = $(this).data('name');
        $('.clickListClass').val(name);
        $(this).closest('.search-key').addClass('d-none');

    });

    $('.searchBtn').click(function () {
        const searchValue = $('.clickListClass').val().trim();
        const cityName = $('#city_name_home').text().trim();
        let selectedElement = '';
        let selectedType = '';
        let selectedId = '';
        if (searchValue) {
            selectedElement = $('.search-key ul li a:contains("' + searchValue + '")').first();
            if (selectedElement.length > 0) {
                selectedType = selectedElement.data('type');
                selectedId = selectedElement.data('id');
            }
        }

        const currentUrl = window.location.origin + window.location.pathname;
        const newUrl =
            `${currentUrl}?type=${selectedType}&id=${selectedId}&city=${encodeURIComponent(cityName)}&search=${encodeURIComponent(searchValue)}`;

        // Redirect to the new URL
        window.location.href = newUrl;

    });

    function setCityClickHandler(selector, inputSelector, nameSelector) {
        $(selector).click(function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            $(inputSelector).val(id);
            $(nameSelector).text(name);
            $('a.cityClick i').toggleClass('rotate');
        });
    }

    setCityClickHandler('.city_click', '#city_home', '#city_name_home');

    $('.clickListClass').on('input', function () {
        if ($(this).val() === '') {
            $('.search-key').addClass('d-none');
        }
    });
});