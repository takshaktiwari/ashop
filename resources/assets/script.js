$(document).ready(function () {
	$(".shop_sidebar_close_btn").click(function () {
		$(".products_page").find(".shop_sidebar_wrapper").slideUp("fast");
	});

	$(".shop_header .mobile-filter-btn").click(function () {
		$(".products_page").find(".shop_sidebar_wrapper").slideDown("fast");
	});

	$(".product_page #buy_now_btn").click(function (e) {
		$(".product_page #buy_now_btn").before(
			'<input type="hidden" name="buy_now" value="1">'
		);
		return true;
	});

    // START: product detail page, image carousel and image zoom
	$(".product_page a.zoomImage").zoom({
		magnify: 1.75,
		url: $(this).attr("href"),
	});
	$(".product_page .product_md_images").slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		autoplay: true,
		fade: true,
		asNavFor: ".product_sm_images",
	});
	$(".product_page .product_sm_images").slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: ".product_md_images",
		dots: true,
		centerMode: true,
		focusOnSelect: true,
    });
    // END: product detail page, image carousel and image zoom


	$(".quantity_counter button.plus").click(function () {
		var input = $(this).prev("input");
		var qty = input.val();
		qty = (qty ? eval(qty) : 0) + 1;

		if (eval(input.attr("max")) < qty) {
			qty = eval(input.attr("max"));
		}

		input.val(qty);
	});

	$(".quantity_counter button.minus").click(function () {
		var input = $(this).next("input");
		var qty = input.val();
		qty = (qty ? eval(qty) : 0) - 1;

		if (eval(input.attr("min")) > qty) {
			qty = eval(input.attr("min"));
		}

		input.val(qty);
	});
});

var scrollContainer = document.querySelector(".horizontal-scroll");
scrollContainer.addEventListener("wheel", (event) => {
	event.preventDefault();
	scrollContainer.scrollLeft += event.deltaY * 2;
});
