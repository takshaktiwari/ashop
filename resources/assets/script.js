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

const scrollContainer = document.querySelector(".horizontal-scroll");
scrollContainer.addEventListener("wheel", (event) => {
	event.preventDefault();
	scrollContainer.scrollLeft += event.deltaY * 2;
});
