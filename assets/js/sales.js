$(document).ready(function() {

	var smc = document.getElementById('sales_metrics_chart');

	var myLineChart = new Chart(smc, {
		type: 'line',
		data: {
			labels: ['January', 'February', 'March', 'April', 'May', 'June',],
			datasets: [{
				label: '2020',
				backgroundColor: '#ea3c3c',
				borderColor: '#ea3c3c',
				data: [1839, 3092, 893, 4391, 3092, 4021],
				fill: false,
			},
			{
				label: '2021',
				fill: false,
				backgroundColor: '#4698e0',
				borderColor: '#4698e0',
				data: [4012, 4093, 705, 6720, 4208, 2873],
			}
			]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Sales Performance Timeline (First Half)'
			},
			// scales: {
			// 	yAxes: [{
			// 		ticks: {
			// 			min: 10,
			// 			max: 50
			// 		}
			// 	}]
			// }
		}
	});

	var bsc = document.getElementById('best_seller_chart');

	var myLineChart = new Chart(bsc, {
		type: 'bar',
		data: {
			labels: ['Lettuce', 'Onions', 'Chillis', 'Tomatoes', 'Mint', 'Rosemary',],
			datasets: [{
				label: 'Units Sold March 2021',
				backgroundColor: '#3ec3c3',
				data: [254, 607, 189, 379, 455, 760],
				fill: false,
			}]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Best Selling Products'
			},
			// scales: {
			// 	yAxes: [{
			// 		ticks: {
			// 			min: 10,
			// 			max: 50
			// 		}
			// 	}]
			// }
		}
	});
});