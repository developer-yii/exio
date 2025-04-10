$(document).ready(function () {


    let usersData = JSON.parse(localStorage.getItem('usersData')) || [];

    // Show suggestions while typing in the name field
    $('#name').on('input', function () {
        let inputVal = $(this).val().toLowerCase();

        let suggestions = usersData.filter(user => user.name.toLowerCase().includes(inputVal));

        let suggestionsHtml = suggestions.map(user => 
            `<div class="autocomplete-item" data-name="${user.name}" data-phone="${user.phone}" data-email="${user.email}">
                ${user.name}
            </div>`
        ).join('');

        if (suggestionsHtml) {
            $('#autocomplete-list').html(suggestionsHtml).show();
        } else {
            $('#autocomplete-list').hide();
        }
    });

    // Autofill fields when selecting a suggestion
    $(document).on('click', '.autocomplete-item', function () {

        $('#name').val($(this).data('name'));
        $('#phone_number').val($(this).data('phone'));
        $('#brochure_email').val($(this).data('email'));
        $('#autocomplete-list').hide();
    });

    // Hide suggestion box when clicking outside
    $(document).click(function (event) {
        if (!$(event.target).closest('#name, #autocomplete-list').length) {
            $('#autocomplete-list').hide();
        }
    });  

    const video = document.getElementById("myVideo");
    const playIcon = document.getElementById("playIcon");
    const pauseIcon = document.getElementById("pauseIcon");

    // Play video on clicking playIcon
    playIcon.addEventListener("click", function () {
        video.play();
        playIcon.style.display = "none"; // Hide play icon
    });

    // Pause video
    pauseIcon.addEventListener("click", function () {
        video.pause();
    });

    // Show/hide icons based on play/pause state
    video.addEventListener("play", function () {
        playIcon.style.display = "none";
        pauseIcon.style.display = "block";
    });

    video.addEventListener("pause", function () {
        playIcon.style.display = "block";
        pauseIcon.style.display = "none";
    });

    // Optional: show play icon again when video ends
    video.addEventListener("ended", function () {
        playIcon.style.display = "block";
        pauseIcon.style.display = "none";
    });

    // 2D Image Click
    $('#databaseList2D li').click(function () {
        $('#databaseList2D li').removeClass('active'); // Remove active class
        $(this).addClass('active'); // Add active class
        var imagePath = $(this).data('image'); // Get image path
        $('#displayedImage2D').attr('src', imagePath); // Update image
    });

    // 3D Image Click
    $('#databaseList3D li').click(function () {
        $('#databaseList3D li').removeClass('active'); // Remove active class
        $(this).addClass('active'); // Add active class
        var imagePath = $(this).data('image'); // Get image path
        $('#displayedImage3D').attr('src', imagePath); // Update image
    });

    $('#downloadBrochureForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: downloadBrochureUrl,
            type: "POST",
            data: formData,
            dataType: 'json',
            success: function (response) {
                $('.error').html("");
                if (response.user) {
                    let name = response.user.name;
                    let phone = response.user.phone_number;
                    let email = response.user.email;
            
                    let exists = usersData.find(user => user.name === name);
                    if (!exists) {
                        usersData.push({ name, phone, email });
                        localStorage.setItem('usersData', JSON.stringify(usersData));
                    }
                }

                if (response.status == true) {
                    $('#downloadBrochureForm')[0].reset();
                    let link = document.createElement("a");
                    link.href = response.file;
                    link.setAttribute("download", "brochure.pdf");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    toastr.success("Brochure download successfully");
                    $('.downloadBrochure').modal('hide');

                } else {                    
                    if (response.errors) {
                        first_input = "";
                        $.each(response.errors, function (key) {
                            if (first_input == "") first_input = key;
                            $('.' + key).closest('.form-group').find('.error').html(response.errors[key]);
                        });
                        $('#downloadBrochureForm').find("." + first_input).focus();
                    }
                }
            },
            error: function (xhr) {
                alert("Something went wrong. Please try again.");
                console.error(xhr.responseText); // Log error for debugging
            }
        });
    });
})

clearErrorOnInput('#downloadBrochureForm');

document.addEventListener("DOMContentLoaded", function() {

    updateShareLinks();

    let masterPlanListContainer = document.querySelector("#masterPlanList");
    if (!masterPlanListContainer) return;

    let section = masterPlanListContainer.dataset.section;
    let isMobile = section === "mobile";
    let masterPlanList = isMobile
        ? document.querySelectorAll("#masterPlanList a")
        : masterPlanListContainer.querySelectorAll("li");
    let masterImg2D = document.getElementById("masterImg2D");
    let masterImg3D = document.getElementById("masterImg3D");
    let masterClick = document.querySelector(".masterClick");

    if (masterPlanList.length > 0) {
        let firstItem = masterPlanList[0];
        activateItem(firstItem);

        masterPlanList.forEach(item => {
            item.addEventListener("click", function () {
                masterPlanList.forEach(el => el.classList.remove("active"));
                activateItem(this);
            });
        });
    }

    function activateItem(item) {
        item.classList.add("active");
        masterImg2D.src = item.getAttribute("data-2dImage") || "";
        masterImg3D.src = item.getAttribute("data-3dImage") || "";
        if (masterClick) {
            let textElement = item.querySelector("h6");
            if (textElement) {
                masterClick.querySelector("h6").textContent = textElement.textContent;
            }
        }
    }

    // smooth scroll section
    document.querySelectorAll('.stickyTabpanel a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent URL change

            let targetId = this.getAttribute("href").substring(1); // Remove #
            let targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 150, // Adjust for fixed header
                    behavior: "smooth"
                });
            }
        });
    });
});

$('.sliderImgSec .owl-carousel').owlCarousel({
    loop:false,
    margin:20,
    nav:true,
    navText: [
        '<img src="' + baseUrl + 'assest/images/left-ar.png" alt="left-ar">',
        '<img src="' + baseUrl + 'assest/images/right-ar.png" alt="right-ar">'
    ],
    dots:false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2.7
        },
        1000:{
            items:2.7
        }
    }
})

function initMap() {
// Replace with your project's latitude & longitude
    var projectLocation = { lat: latitude, lng: longitude };

    // Initialize map
    var map = new google.maps.Map(document.getElementById("map"), {
        center: projectLocation,
        zoom: 15,
    });

    // Add marker
    new google.maps.Marker({
        position: projectLocation,
        map: map,
        title: "{{ $project->project_name }}",
    });
}

// $('body').on('click','.social_media_share',function(event){
//     var url = $(this).attr('data-href');
//     var left = (screen.width - 600) / 2;
//     var top = (screen.height - 400) / 2;
//     window.open(url, '_blank', 'width=600,height=400,left=' + left + ',top=' + top);
// });


// function copyToClipboard() {
//     var copyText = document.getElementById('copy-link');
//     copyText.removeAttribute("disabled");
//     copyText.select();
//     copyText.setSelectionRange(0, 99999);
//     document.execCommand('copy');
//     if (typeof toastr !== 'undefined') {
//         toastr.success('Link copied!');
//         copyText.setAttribute("disabled", "true");
//     } else {
//         alert('Link copied!');
//         copyText.setAttribute("disabled", "true");
//     }
// }

// Call the function to update share links when the page loads
// window.onload = updateShareLinks;