// side-bar click menu
    $(document).ready(function () {
        $('header .main-nav .click-menu').click(function () {
            $('header .main-nav nav').addClass('show')
            $('header .main-nav .cancel-menu').addClass('show')
            $('body').css({ 'position': 'fixed', 'width': '100%' });
        });

        $('header .main-nav .cancel-menu').click(function () {
            $('header .main-nav nav').removeClass('show')
            $('header .main-nav .cancel-menu').removeClass('show')
            $('body').css({ 'position': 'unset', 'width': 'auto' });
        });

    });


// header fix
 
$(window).scroll(function(){
    if ($(window).scrollTop () > 50) {
        $('header').addClass('show');
    }
    else {
        $('header').removeClass('show');
    }
});


$(document).ready(function () {
    $('.user-menu').click(function (e) {
        e.stopPropagation(); 
        $('.userDropDown').toggleClass('show');
        $('.projectDropDown').removeClass('show');
    });

    $('.first-menu').click(function (e) {
        e.stopPropagation(); 
        $('.projectDropDown').toggleClass('show');
        $('.userDropDown').removeClass('show'); 
    });

    $('.cityClick').click(function (e) {
        e.stopPropagation(); 
        $('.citySelect').toggleClass('show');
        $('.projectDropDown').removeClass('show');
        $('.userDropDown').removeClass('show'); 
    });

    $(document).click(function () {
        $('.userDropDown').removeClass('show');
        $('.projectDropDown').removeClass('show');
        $('.citySelect').removeClass('show');
    });

    $(document).ready(function () {
        $('.filterBox a').click(function (e) {
            e.stopPropagation();
            $('.porpertyFilter').toggleClass('open');
        });
        $(document).click(function (e) {
            if (!$(e.target).closest('.filterBox, .porpertyFilter').length) {
                $('.porpertyFilter').removeClass('open');
            }
        });
    });

    $('.masterClick').click(function(e){
        e.stopPropagation();
        $('.dataDropdown').toggleClass('active');
        $('.masterClick i').toggleClass('active');
    });

    $(document).click(function(){
        $('.dataDropdown').removeClass('active');
        $('.masterClick i').removeClass('active');
    });

});

// ================ Slider js

$('.latesNews .owl-carousel').owlCarousel({
    loop:true,
    margin:20,
    nav:true,
    navText:['<img src="assest/images/left-ar.png" alt="left-ar">','<img src="assest/images/right-ar.png" alt="right-ar">'],
    dots:false,
    responsive:{
        0:{
            items:1
        },
        575:{
            items:2
        },
        769:{
            items:3
        },
        1200:{
            items:4
        }
    }
})


$('.mobileViewSection .owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    responsive:{
        0:{
            items:1
        }
    }
})

$('.mobileViewSection .owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    responsive:{
        0:{
            items:1
        }
    }
})


$(document).ready(function () {
    $(".compareBoxOpen").click(function (event) {
        
        event.preventDefault(); 
        event.stopPropagation();
            $(".comparePorjectModal").addClass("show");
        });

    $(document).click(function (event) {
        if (!$(event.target).closest(".comparePorjectModal, .compareBoxOpen").length) {
            $(".comparePorjectModal").removeClass("show");
        }
    });
    $(".propertyCard").click(function (event) {  
        $('#propertyModal').modal('show');
    });
     
});


$(document).ready(function() {
    $(".lightBoximg").click(function() {
        $(".boxImg a").first().trigger("click");
    });


    $('body').on('click', '#nav-tabContent2 .imgBox, .masterImg', function(e) {
        let src = $(this).find('img').attr('src') || $(this).attr('src'); 
        $('.img_view_section img').attr('src', src);
        $('#imageModal').modal('show');
    });
    

});

// ======== Data counter ========

$('.counter').each(function() {
    var $this = $(this),
        countTo = $this.attr('data-count');
   
    $({ countNum: $this.text()}).animate({
      countNum: countTo
    },
  
    {
      duration: 2000,
      easing:'linear',
      step: function() {
        $this.text(Math.floor(this.countNum));
      },
      complete: function() {        
        $this.text(this.countNum);
      }
    });     
});