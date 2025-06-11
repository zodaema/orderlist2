(function( $ ) {

	'use strict';

    /*
    Flot: Pie
    */
    (function() {
        var plot = $.plot('#flotPie', flotPieData, {
            series: {
                pie: {
                    show: true,
                    combine: {
                        color: '#999',
                        threshold: 0.1
                    },
                }
            },
            legend: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: true
            }
        });
    })();

    /*
	Morris: Bar
	*/
		Morris.Bar({
			resize: true,
			element: 'morrisBar',
			data: morrisBarData,
			xkey: 'y',
			ykeys: ['a','b','c'],
			labels: ['Available','Unavilable','Indeterminate'],
			hideHover: true,
			barColors: ['#47a447','#d2322d','#ed9c28']
		});

	}).apply( this, [ jQuery ]);