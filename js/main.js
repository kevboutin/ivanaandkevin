;(function () {
	'use strict';

	var mobileMenuOutsideClick = function() {
		$(document).click(function (e) {
	    var container = $("#iak-offcanvas, .js-iak-nav-toggle");
	    if (!container.is(e.target) && container.has(e.target).length === 0) {

	    	if ( $('body').hasClass('offcanvas') ) {

    			$('body').removeClass('offcanvas');
    			$('.js-iak-nav-toggle').removeClass('active');
	    	}
	    }
		});
	};

	var offcanvasMenu = function() {
		$('#page').prepend('<div id="iak-offcanvas" />');
		$('#page').prepend('<a href="#" class="js-iak-nav-toggle iak-nav-toggle iak-nav-white"><i></i></a>');
		var clone1 = $('.menu-1 > ul').clone();
		$('#iak-offcanvas').append(clone1);
		var clone2 = $('.menu-2 > ul').clone();
		$('#iak-offcanvas').append(clone2);

		$('#iak-offcanvas .has-dropdown').addClass('offcanvas-has-dropdown');
		$('#iak-offcanvas')
			.find('li')
			.removeClass('has-dropdown');

		// Hover dropdown menu on mobile
		$('.offcanvas-has-dropdown').mouseenter(function(){
			var $this = $(this);

			$this
				.addClass('active')
				.find('ul')
				.slideDown(500, 'easeOutExpo');
		}).mouseleave(function(){

			var $this = $(this);
			$this
				.removeClass('active')
				.find('ul')
				.slideUp(500, 'easeOutExpo');
		});


		$(window).resize(function(){

			if ( $('body').hasClass('offcanvas') ) {

    			$('body').removeClass('offcanvas');
    			$('.js-iak-nav-toggle').removeClass('active');

	    	}
		});
	};

	var burgerMenu = function() {
		$('body').on('click', '.js-iak-nav-toggle', function(event){
			var $this = $(this);


			if ( $('body').hasClass('overflow offcanvas') ) {
				$('body').removeClass('overflow offcanvas');
			} else {
				$('body').addClass('overflow offcanvas');
			}
			$this.toggleClass('active');
			event.preventDefault();

		});
	};

	var contentWayPoint = function() {
		var i = 0;
		$('.animate-box').waypoint( function( direction ) {
			if( direction === 'down' && !$(this.element).hasClass('animated-fast') ) {
				i++;
				$(this.element).addClass('item-animate');
				setTimeout(function() {
					$('body .animate-box.item-animate').each(function(k) {
						var el = $(this);
						setTimeout( function () {
							var effect = el.data('animate-effect');
							if ( effect === 'fadeIn') {
								el.addClass('fadeIn animated-fast');
							} else if ( effect === 'fadeInLeft') {
								el.addClass('fadeInLeft animated-fast');
							} else if ( effect === 'fadeInRight') {
								el.addClass('fadeInRight animated-fast');
							} else {
								el.addClass('fadeInUp animated-fast');
							}

							el.removeClass('item-animate');
						},  k * 200, 'easeInOutExpo' );
					});
				}, 100);
			}
		} , { offset: '85%' } );
	};


	var dropdown = function() {
		$('.has-dropdown').mouseenter(function() {
			var $this = $(this);
			$this
				.find('.dropdown')
				.css('display', 'block')
				.addClass('animated-fast fadeInUpMenu');
		}).mouseleave(function(){
			var $this = $(this);
			$this
				.find('.dropdown')
				.css('display', 'none')
				.removeClass('animated-fast fadeInUpMenu');
		});
	};

	var testimonialCarousel = function() {
		var owl = $('.owl-carousel-fullwidth');
		owl.owlCarousel({
			items: 1,
			loop: true,
			margin: 0,
			responsiveClass: true,
			nav: false,
			dots: true,
			smartSpeed: 800,
			autoHeight: true,
		});
	};

	var goToTop = function() {
		$('.js-gotop').on('click', function(event) {
			event.preventDefault();

			$('html, body').animate({
				scrollTop: $('html').offset().top
			}, 500, 'easeInOutExpo');

			return false;
		});

		$(window).scroll(function() {
			var $win = $(window);
			if ($win.scrollTop() > 200) {
				$('.js-top').addClass('active');
			} else {
				$('.js-top').removeClass('active');
			}
		});
	};

	// Loading page
	var loaderPage = function() {
		$(".iak-loader").fadeOut("slow");
	};

	var counter = function() {
		$('.js-counter').countTo({
			 formatter: function (value, options) {
	      return value.toFixed(options.decimals);
	    },
		});
	};

	var counterWayPoint = function() {
		if ($('#iak-counter').length > 0 ) {
			$('#iak-counter').waypoint( function( direction ) {
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {
					setTimeout( counter , 400);
					$(this.element).addClass('animated');
				}
			} , { offset: '90%' } );
		}
	};

	// Parallax
	var parallax = function() {
		$(window).stellar();
	};

	// RSVP form
	$("#btn-attend").click(function (event) {
		event.preventDefault();
		$("#rsvp-message").empty();
		var name = $('#name').val();
		var email = $('#email').val();
		var people = $('#people').val();
		jQuery.support.cors = true;

		$.ajax({
			method: 'POST',
			url: './php/postrsvp.php',
			data: {name: name, email: email, attendees: people}
		}).success(function (response) {
			var data = $.parseJSON(response);
			if (data.status === 'success') {
				if (people && !isNaN(people) && people > 1) {
					$("#rsvp-message").append($("<br/>Thank you for your RSVP with " + people + " people.<br/>"));
				} else {
					$("#rsvp-message").append($("<br/>Thank you for your RSVP.<br/>"));
				}
				$("#name").val("");
				$("#email").val("");
				$("#people").val("");
			} else {
				$("#rsvp-message").append($("<br/>" + data.errors + "<br/>"));
			}
		}).error(function (jqXhr, textStatus, errorMessage) {
			$("#rsvp-message").append($("<br/>Error: " + errorMessage + "<br/>"));
		});
	});

	// Wishes form
	$("#btn-wishes").click(function (event) {
		event.preventDefault();
		$("#wishes-message").empty();
		var name = $('#namewishes').val();
		var message = $('#message').val();
		jQuery.support.cors = true;

		$.ajax({
			method: 'POST',
			url: './php/postguestbook.php',
			data: {name: name, message: message}
		}).success(function () {
			$("#wishes-message").append($("<br/>Thank you for your message.<br/>"));
			$("#namewishes").val("");
			$("#message").val("");
		}).error(function (jqXhr, textStatus, errorMessage) {
			$("#wishes-message").append($("<br/>Error: " + errorMessage + "<br/>"));
		});
	});

	$(function() {
		mobileMenuOutsideClick();
		parallax();
		offcanvasMenu();
		burgerMenu();
		contentWayPoint();
		dropdown();
		testimonialCarousel();
		goToTop();
		loaderPage();
		counter();
		counterWayPoint();
	});
}());
