var xhr = new XMLHttpRequest();
xhr.open('GET', 'getIncome.php');
xhr.onload = function() {
  if (xhr.status === 200) {
    var data = JSON.parse(xhr.responseText);
	createPieChart(data, 'incomes');
  }
};
xhr.send();

var zhr = new XMLHttpRequest();
zhr.open('GET', 'getExpenses.php');
zhr.onload = function() {
  if (zhr.status === 200) {
    var data = JSON.parse(zhr.responseText);
	createPieChart(data, 'expenses');
  }
};
zhr.send();

function createPieChart(data, nazwa) {
  var ctx = document.getElementById(nazwa).getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: data.map(function(item) {
        return item.category;
      }),
      datasets: [{
        data: data.map(function(item) {
          return item.total_amount;
        }),
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)',
          'rgba(54, 162, 235, 0.5)',
          'rgba(255, 206, 86, 0.5)',
          'rgba(75, 192, 192, 0.5)',
          'rgba(153, 102, 255, 0.5)',
          'rgba(255, 159, 64, 0.5)'
        ]
      }]
    }
  });
}