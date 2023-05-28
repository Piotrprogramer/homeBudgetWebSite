
var xValues = ["Italy", "France", "Spain", "USA"];
var yValues = [55, 49, 44, 24];
var barColors = [
"#B22222",
"#1E90FF",
"#32CD32",
"#FF4500",
"#FF00FF",
"#FFFF00",
"#BC8F8F",
];

function jsFunction(){ 


	new Chart("myChart", {
	  type: "pie",
	  data: {
		labels: xValues,
		position:'left',
		datasets: [{
		
		  backgroundColor: barColors,
		  data: yValues
		}]
	  },
	  options: {
		title: {
		  display: true,
		  text: "Przychody"
		}
	  }
	});
}

function jsFunction1(){ 
	new Chart("myChart1", {
	  type: "pie",
	  data: {
		labels: xValues,
		datasets: [{
		  backgroundColor: barColors,
		  data: yValues
		}]
	  },
	  options: {
		title: {
		  display: true,
		  text: "Wydatki"
		}
	  }
	});
}

