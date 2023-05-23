$(document).ready(function(){
    $.ajax({
        type: "GET",
        url: "php/display.php",
        datatype: "json",
        success: function(res){
            var data = jQuery.parseJSON(res);
            console.log(data[2]);
            let cards = data[0];

                $('<li>').append($('<span>').html(`<i><strong>${cards.CompanyCount} </strong>Companiees</i>`)).appendTo($('#card'));
                $('<li>').append($('<span>').html(`<i><strong>${cards.StudentsPlaced} </strong>Student Placed</i>`)).appendTo($('#card'));
                $('<li>').append($('<span>').html(`<i><strong>${cards.AveragePlacement} </strong>Average Package</i>`)).appendTo($('#card'));

            $('#offerToSalaryChart').dxChart({
                dataSource: data[2],
                series: {
                    argumentField: 'salary',
                    valueField: 'value',
                    name: 'Salary',
                    type: 'bar',
                    color: '#ffaa66',
                },
                valueAxis: {
                    title: {
                        text: 'Number of companies',
                    },
                    position: 'left',
                    },
                    title: 'Offer to Salary Graph',
                    tooltip: {
                        enabled: true,
                        location: 'edge',
                        customizeTooltip(arg) {
                            return {
                            text: ` Number of companies: ${arg.value}`,
                            };
                        },
                    },
                });

            $('#companyWiseOffer').dxPieChart({
                dataSource: data[1],
                palette: 'Soft',
                title: 'Company wise Offer',
                series: [{
                argumentField: 'Organization_Name',
                valueField: 'value',
                label: {
                    visible: true,
                    connector: {
                    visible: true,
                    width: 0.5,
                    },
                    position: 'columns',
                    customizeText(arg) {
                    return `${arg.argumentText} (${arg.percentText})`;
                    },
                },
                }],
            });

        }
    });
});