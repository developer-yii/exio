$(document).ready(function () {

    const video = document.getElementById("myVideo");
    const playIcon = document.getElementById("playIcon");

    // Play video on clicking playIcon
    playIcon.addEventListener("click", function () {
        video.play();
        playIcon.style.display = "none"; // Hide play icon
    });

    // Show playIcon when the video is paused
    video.addEventListener("pause", function () {
        playIcon.style.display = "block"; // Show play icon
    });

    // Hide playIcon when the video is playing
    video.addEventListener("play", function () {
        playIcon.style.display = "none"; // Hide play icon
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
                    $('.error').html("");
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
                    top: targetElement.offsetTop - 180, // Adjust for fixed header
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