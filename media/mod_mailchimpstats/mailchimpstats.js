google.load('visualization', '1', {'packages':['corechart']});
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Task');
    data.addColumn('number', 'Hours per Day');
    data.addRows(5);
    data.setValue(0, 0, 'opens');
    data.setValue(0, 1, mcStats.opens);
    data.setValue(1, 0, 'bounced');
    data.setValue(1, 1, mcStats.bounced);
    data.setValue(2, 0, 'not opened');
    data.setValue(2, 1, mcStats.notOpened);

    var chart = new google.visualization.PieChart(document.getElementById('mcStatsPieChart'));
    chart.draw(data, {
        width: 250,
        height: 300,
        is3D: false,
        title: 'stats',
        colors: ['#93ccea', '#5c8ea9', '#275886'],
        titleTextStyle: {color: '#c0c0c0'},
        backgroundColor: {
            stroke: null,
            fill: null,
            strokeSize: 0
        },
        chartArea: {
            left: 20,
            top: 10,
            width: '90%',
            height:'75%'
        },
        legend: 'bottom'
    });
}