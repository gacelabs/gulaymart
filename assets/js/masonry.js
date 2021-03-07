$(document).on('ready resize change', function() {

	function resizeGridItem(item){
		grid = document.getElementsByClassName("eo-blends-parent-grid")[0],
		rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows')),
		rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap')),
		rowSpan = Math.ceil((item.querySelector('.blend-card').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap)),
		item.style.gridRowEnd = "span "+rowSpan;
	}

	function resizeAllGridItems(){
		allItems = document.getElementsByClassName("blend-card-parent");
		for(x=0;x<allItems.length;x++){
			resizeGridItem(allItems[x]);
		}
	}

	function resizeInstance(instance){
		item = instance.elements[0];
		resizeGridItem(item);
	}

	window.onload = resizeAllGridItems();
	window.addEventListener("resize", resizeAllGridItems);

	allItems = document.getElementsByClassName("blend-card-parent");

	// for(x=0;x<allItems.length;x++){
	// 	imagesLoaded(allItems[x], resizeInstance);
	// }


	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		function resizeGridItem(item){
			grid = document.getElementsByClassName("eo-blends-parent-grid")[0],
			rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows')),
			rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap')),
			rowSpan = Math.ceil((item.querySelector('.blend-card').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap)),
			item.style.gridRowEnd = "span "+rowSpan;
		}

		function resizeAllGridItems(){
			allItems = document.getElementsByClassName("blend-card-parent");
			for(x=0;x<allItems.length;x++){
				resizeGridItem(allItems[x]);
			}
		}

		function resizeInstance(instance){
			item = instance.elements[0];
			resizeGridItem(item);
		}

		window.onload = resizeAllGridItems();
		window.addEventListener("resize", resizeAllGridItems);

		allItems = document.getElementsByClassName("blend-card-parent");
	});

});