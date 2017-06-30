<!DOCTYPE HTML>
<!--
	Prologue by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>IPM - IntelligentPestMonitoring</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        
        <script type="text/javascript" src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
        
<?php
     require("phpsqlajax_dbinfo.php");

      // Connect to the database
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) {
            die('Connected Failed : ' . $conn->connect_error) . "<br>";
        }
        // Set the active MySQL database
        $conn->select_db($database);


        $sql = "SELECT MAX(id) FROM insectnumber";
        if($conn->query($sql) == TRUE) 
        {
            $result = $conn->query($sql);
            $temp = $result->fetch_array(); 
            $id=$temp[0];
            $sql = "SELECT numbers, record_date FROM insectnumber where id = $id";
            if($conn->query($sql) == TRUE) 
            {
                
                $temp = $conn->query($sql)->fetch_array(); 
                $number = $temp[0];
                $record_date = $temp[1];
                $data[0] = $number;
                $data[1] = $record_date;

                $arr = array('name' => "A" , 'value' => $number , 'path'=> "M0,-708C9,-482,7,-482,7,-482L179,-452,176,-452,303,-514,271,-717z");
                $abc = json_encode($arr, JSON_NUMERIC_CHECK);

            }

        }


        if (isset($_POST["theDay"])) {
            $a=$_POST["theDay"];
            $dayInsectNum="";
            $sql = "SELECT numbers FROM insectnumber where record_date LIKE '$a%'";
            if($result=$conn->query($sql)) {
                while ($temp = $result->fetch_assoc()) {
                    $dayInsectNum=$dayInsectNum.$temp["numbers"].",";
             }
            }
        }


        if (isset($_POST["theSensorDay"])) {
            $daySensorPressure="";
            $daySensorTenperature="";
            $daySensorHumididty="";
            $daySensorAltitude="";
            $sql = "SELECT pressure, temperature, humidity, altitude FROM sensor_data where record_date LIKE '$b%'";
            if($result=$conn->query($sql)) {
                $b=$_POST["theSensorDay"];
                $daySensorPressure="";
                $daySensorTenperature="";
                $daySensorHumididty="";
                $daySensorAltitude="";
                $sql = "SELECT pressure, temperature, humidity, altitude FROM sensor_data where record_date LIKE '$b%'";
                if($result=$conn->query($sql)) {
                    while ($temp = $result->fetch_assoc()) {
                        $daySensorPressure=$daySensorPressure.$temp["pressure"].",";
                        $daySensorTenperature=$daySensorTenperature.$temp["temperature"].",";
                        $daySensorHumididty=$daySensorHumididty.$temp["humidity"].",";
                        $daySensorAltitude=$daySensorAltitude.$temp["altitude"].",";
                    }
                }
            }
        }
        $conn->close();
        
?>

        <!--highmap-->
        <script type="text/javascript">
           
           $(function () {
                    
                  
                  //downloadUrl ()
        var a='[{ "name": "taiwan", "value":48, "path": "M0,-837C267,-602,261,-612,261,-612L188,-338,276,-151,557,-303,554,-704z" }, { "name": "japan", "value":81, "path": "M554,-695,644,-955,890,-749,1000,-466,890,41,557,-303L557,-307,557,-311" }] ';
        var c=JSON.parse(a);
    // Initiate the chart
    $('#containerr').highcharts('Map', {
    
    
    	chart : {
                borderWidth : 5
            },

            title : {
                text : 'Insect Number of The Area',
                "color": '#333333', 
                "fontSize": '50px'
            },
						//subtitle: {
                       // text: 'Source: <a href="http://en.wikipedia.org/wiki/United_States_presidential_election,' 																+'_2012">NUHU-LAB1006</a>'
         //   },
            legend: {
                title:{text: 'Insect per m²'},
                layout: 'horizontal',
                borderWidth:0,
                borderColor:	'#5B5B5B',
                backgroundColor: 'rgba(255,255,255,0.85)',
                floating: false,
                verticalAlign: 'bottom',
                align: 'left',
                y:-20,
                x:-10,
                align: 'right'
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },

            colorAxis: {
                min: null,
                max: null,
                type: 'linear',
                minColor: '#00c000',
                maxColor: '#EA0000',
                stops: [
                    [0, '#00c000'],
                    [0.5, '#FFD306'],
                    [1, '#EA0000']
                ]
            },
            series:[
            {	animation: {
                    duration: 100
              },
              "type": "map",
              "data": [
   <?php
                  echo $abc;
    ?>
                  ,
   {
    "name": "F",
    "value":36,
    "path": "M3,-708,33,-914,198,-985,326,-978,271,-717L271,-717z"
   },
   {
    "name": "B",
       "value":47,
    "path": "M271,-717,563,-687,607,-504,304,-518Z"
   },
   {
    "name": "C",
    "value":78,
    "path": "M562,-688,815,-711,997,-695,1000,-468,606,-505z"
   },
   {
    "name": "D",
    "value":98,
    "path": "M997,-695,940,-1002,720,-990,620,-847,564,-688,820,-711Z"
   },
   {
    "name": "E",
       "value":23,
    "path": "M326,-977,272,-717,564,-686,620,-847,720,-990Z"
   }
  ]
             ,
              dataLabels: {
                    enabled: true,
                    color: '#000000',
                    format: '{point.name}'
                },
                name: 'Insect density',
                tooltip: {
                		//headerFormat: '<span style="font-size:10px">{series.name}</span><br/>',
                		pointFormat: '{point.name}: {point.value}/m²<br/>',
               			footerFormat: '<span style="font-size:10px">Source: Wikipedia</span><br/>'
                },
                states: {
                  hover: {
                      color: '#2894FF'
                	}
            		}
            }]
    });
               
});
        </script>
               
        <!--highchart-->
		<script type="text/javascript">
			function checkForm(){
                                		document.getElementsByName("theDay").value="<?php echo $a; ?>";
                                	}

            $(function () {
                $('#cont').highcharts({
                    title: {
                        text: 'Day Average Insect Number',
                        x: -20 //center
                    },
                    //subtitle: {
                      //  text: 'Source: NCHU',
                        //x: -20
                    //},
                    xAxis: {
                        categories: ['0','1', '2', '3', '4', '5', '6',
                            '7', '8', '8', '8', '10', '11','12','13','14','15','16','17','18','19','20','21','22','23']
                    },
                    yAxis: {
                        title: {
                            text: 'Insect Number (隻)'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                                        xAxis: {
                        title: {
                            text: "o' clock(hr)"
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },

                    tooltip: {
                        valueSuffix: '隻/m²'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                        name: 'A',
                        data: [<?php echo substr($dayInsectNum, 0,-1); ?>]
                    }, {
                        name: 'B',
                        data: [47, 46, 42, 43, 50, 45, 42, 46, 48, 46, 42, 49,44,44,45,48,54,52,48,47,48,51,50,47]
                    }, {
                        name: 'C',
                        data: [78, 76, 72, 83, 82, 85, 82, 76, 78, 76, 82, 79,84,84,75,88,84,82,78,77,78,84,85,77]
                    }, {
                        name: 'D',
                        data: [98, 97, 92, 103, 95, 105, 102, 96, 98, 106, 102, 99,94,104,105,98,104,102,98,97,98,101,93,97]
                    },{
                        name: 'E',
                        data: [27, 26, 22, 23, 25, 25, 22, 26, 23, 26, 22, 19,24,24,25,23,19,18,20,27,28,21,25,23]
                    },{
                        name: 'F',
                        data: [37, 36, 42, 33, 40, 35, 32, 41, 38, 36, 32, 39,41,40,39,38,34,32,38,37,43,31,36,37]
                    }]
                });
            });
		</script>
        
        <script type="text/javascript">
            $(function () {
    $('#containerrr').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Temperature Humidity Pressure Altitude'
        },
        subtitle: {
            text: ''
        },
        xAxis: [{
            categories: ['0','1', '2', '3', '4', '5', '6',
                            '7', '8', '8', '8', '10', '11','12','13','14','15','16','17','18','19','20','21','22','23'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}°C',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: 'Temperature',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: 'Humidity',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} ％',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: 'pressure',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} hPa',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: 'Altitude',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            },
            labels: {
                format: '{value} m',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            }

        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 0,
            verticalAlign: 'top',
            y: 20,
            floating: false,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Humidity',
            type: 'column',
            yAxis: 1,
            data: [<?php echo substr($daySensorHumididty, 0,-1); ?>],
            tooltip: {
                valueSuffix: ' %'
            }

        }, {
            name: 'Pressure',
            type: 'spline',
            yAxis: 2,
            data: [<?php echo substr($daySensorPressure, 0,-1); ?>],
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                valueSuffix: ' hpa'
            }

        }, {
            name: 'Temperature',
            type: 'spline',
            data: [<?php echo substr($daySensorTenperature, 0,-1); ?>],
            tooltip: {
                valueSuffix: ' °C'
            }
        }, {
            name: 'Altitude',
            type: 'spline',
            yAxis: 3,
            data: [<?php echo substr($daySensorAltitude, 0,-1); ?>],
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                valueSuffix: ' m'
            }

        }]
    });
});


        </script>
        
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
/**
 * This is a complex demo of how to set up a Highcharts chart, coupled to a
 * dynamic source and extended by drawing image sprites, wind arrow paths
 * and a second grid on top of the chart. The purpose of the demo is to inpire
 * developers to go beyond the basic chart types and show how the library can
 * be extended programmatically. This is what the demo does:
 *
 * - Loads weather forecast from www.yr.no in form of an XML service. The XML
 *   is translated on the Higcharts website into JSONP for the sake of the demo
 *   being shown on both our website and JSFiddle.
 * - When the data arrives async, a Meteogram instance is created. We have
 *   created the Meteogram prototype to provide an organized structure of the different
 *   methods and subroutines associated with the demo.
 * - The parseYrData method parses the data from www.yr.no into several parallel arrays. These
 *   arrays are used directly as the data option for temperature, precipitation
 *   and air pressure. As the temperature data gives only full degrees, we apply
 *   some smoothing on the graph, but keep the original data in the tooltip.
 * - After this, the options structure is build, and the chart generated with the
 *   parsed data.
 * - In the callback (on chart load), we weather icons on top of the temperature series.
 *   The icons are sprites from a single PNG image, placed inside a clipped 30x30
 *   SVG <g> element. VML interprets this as HTML images inside a clipped div.
 * - Lastly, the wind arrows are built and added below the plot area, and a grid is
 *   drawn around them. The wind arrows are basically drawn north-south, then rotated
 *   as per the wind direction.
 */

function Meteogram(xml, container) {
    // Parallel arrays for the chart data, these are populated as the XML/JSON file
    // is loaded
    this.symbols = [];
    this.symbolNames = [];
    this.precipitations = [];
    this.windDirections = [];
    this.windDirectionNames = [];
    this.windSpeeds = [];
    this.windSpeedNames = [];
    this.temperatures = [];
    this.pressures = [];

    // Initialize
    this.xml = xml;
    this.container = container;

    // Run
    this.parseYrData();
}
/**
 * Return weather symbol sprites as laid out at http://om.yr.no/forklaring/symbol/
 */
Meteogram.prototype.getSymbolSprites = function (symbolSize) {
    return {
        '01d': {
            x: 0,
            y: 0
        },
        '01n': {
            x: symbolSize,
            y: 0
        },
        '16': {
            x: 2 * symbolSize,
            y: 0
        },
        '02d': {
            x: 0,
            y: symbolSize
        },
        '02n': {
            x: symbolSize,
            y: symbolSize
        },
        '03d': {
            x: 0,
            y: 2 * symbolSize
        },
        '03n': {
            x: symbolSize,
            y: 2 * symbolSize
        },
        '17': {
            x: 2 * symbolSize,
            y: 2 * symbolSize
        },
        '04': {
            x: 0,
            y: 3 * symbolSize
        },
        '05d': {
            x: 0,
            y: 4 * symbolSize
        },
        '05n': {
            x: symbolSize,
            y: 4 * symbolSize
        },
        '18': {
            x: 2 * symbolSize,
            y: 4 * symbolSize
        },
        '06d': {
            x: 0,
            y: 5 * symbolSize
        },
        '06n': {
            x: symbolSize,
            y: 5 * symbolSize
        },
        '07d': {
            x: 0,
            y: 6 * symbolSize
        },
        '07n': {
            x: symbolSize,
            y: 6 * symbolSize
        },
        '08d': {
            x: 0,
            y: 7 * symbolSize
        },
        '08n': {
            x: symbolSize,
            y: 7 * symbolSize
        },
        '19': {
            x: 2 * symbolSize,
            y: 7 * symbolSize
        },
        '09': {
            x: 0,
            y: 8 * symbolSize
        },
        '10': {
            x: 0,
            y: 9 * symbolSize
        },
        '11': {
            x: 0,
            y: 10 * symbolSize
        },
        '12': {
            x: 0,
            y: 11 * symbolSize
        },
        '13': {
            x: 0,
            y: 12 * symbolSize
        },
        '14': {
            x: 0,
            y: 13 * symbolSize
        },
        '15': {
            x: 0,
            y: 14 * symbolSize
        },
        '20d': {
            x: 0,
            y: 15 * symbolSize
        },
        '20n': {
            x: symbolSize,
            y: 15 * symbolSize
        },
        '20m': {
            x: 2 * symbolSize,
            y: 15 * symbolSize
        },
        '21d': {
            x: 0,
            y: 16 * symbolSize
        },
        '21n': {
            x: symbolSize,
            y: 16 * symbolSize
        },
        '21m': {
            x: 2 * symbolSize,
            y: 16 * symbolSize
        },
        '22': {
            x: 0,
            y: 17 * symbolSize
        },
        '23': {
            x: 0,
            y: 18 * symbolSize
        }
    };
};


/**
 * Function to smooth the temperature line. The original data provides only whole degrees,
 * which makes the line graph look jagged. So we apply a running mean on it, but preserve
 * the unaltered value in the tooltip.
 */
Meteogram.prototype.smoothLine = function (data) {
    var i = data.length,
        sum,
        value;

    while (i--) {
        data[i].value = value = data[i].y; // preserve value for tooltip

        // Set the smoothed value to the average of the closest points, but don't allow
        // it to differ more than 0.5 degrees from the given value
        sum = (data[i - 1] || data[i]).y + value + (data[i + 1] || data[i]).y;
        data[i].y = Math.max(value - 0.5, Math.min(sum / 3, value + 0.5));
    }
};

/**
 * Callback function that is called from Highcharts on hovering each point and returns
 * HTML for the tooltip.
 */
Meteogram.prototype.tooltipFormatter = function (tooltip) {

    // Create the header with reference to the time interval
    var index = tooltip.points[0].point.index,
        ret = '<small>' + Highcharts.dateFormat('%A, %b %e, %H:%M', tooltip.x) + '-' +
            Highcharts.dateFormat('%H:%M', tooltip.points[0].point.to) + '</small><br>';

    // Symbol text
    ret += '<b>' + this.symbolNames[index] + '</b>';

    ret += '<table>';

    // Add all series
    Highcharts.each(tooltip.points, function (point) {
        var series = point.series;
        ret += '<tr><td><span style="color:' + series.color + '">\u25CF</span> ' + series.name +
            ': </td><td style="white-space:nowrap">' + Highcharts.pick(point.point.value, point.y) +
            series.options.tooltip.valueSuffix + '</td></tr>';
    });

    // Add wind
    ret += '<tr><td style="vertical-align: top">\u25CF Wind</td><td style="white-space:nowrap">' + this.windDirectionNames[index] +
        '<br>' + this.windSpeedNames[index] + ' (' +
        Highcharts.numberFormat(this.windSpeeds[index], 1) + ' m/s)</td></tr>';

    // Close
    ret += '</table>';


    return ret;
};

/**
 * Draw the weather symbols on top of the temperature series. The symbols are sprites of a single
 * file, defined in the getSymbolSprites function above.
 */
Meteogram.prototype.drawWeatherSymbols = function (chart) {
    var meteogram = this,
        symbolSprites = this.getSymbolSprites(30);

    $.each(chart.series[0].data, function (i, point) {
        var sprite,
            group;

        if (meteogram.resolution > 36e5 || i % 2 === 0) {

            sprite = symbolSprites[meteogram.symbols[i]];
            if (sprite) {

                // Create a group element that is positioned and clipped at 30 pixels width and height
                group = chart.renderer.g()
                    .attr({
                        translateX: point.plotX + chart.plotLeft - 15,
                        translateY: point.plotY + chart.plotTop - 30,
                        zIndex: 5
                    })
                    .clip(chart.renderer.clipRect(0, 0, 30, 30))
                    .add();

                // Position the image inside it at the sprite position
                chart.renderer.image(
                    'https://www.highcharts.com/samples/graphics/meteogram-symbols-30px.png',
                    -sprite.x,
                    -sprite.y,
                    90,
                    570
                )
                    .add(group);
            }
        }
    });
};

/**
 * Create wind speed symbols for the Beaufort wind scale. The symbols are rotated
 * around the zero centerpoint.
 */
Meteogram.prototype.windArrow = function (name) {
    var level,
        path;

    // The stem and the arrow head
    path = [
        'M', 0, 7, // base of arrow
        'L', -1.5, 7,
        0, 10,
        1.5, 7,
        0, 7,
        0, -10 // top
    ];

    level = $.inArray(name, ['Calm', 'Light air', 'Light breeze', 'Gentle breeze', 'Moderate breeze',
        'Fresh breeze', 'Strong breeze', 'Near gale', 'Gale', 'Strong gale', 'Storm',
        'Violent storm', 'Hurricane']);

    if (level === 0) {
        path = [];
    }

    if (level === 2) {
        path.push('M', 0, -8, 'L', 4, -8); // short line
    } else if (level >= 3) {
        path.push(0, -10, 7, -10); // long line
    }

    if (level === 4) {
        path.push('M', 0, -7, 'L', 4, -7);
    } else if (level >= 5) {
        path.push('M', 0, -7, 'L', 7, -7);
    }

    if (level === 5) {
        path.push('M', 0, -4, 'L', 4, -4);
    } else if (level >= 6) {
        path.push('M', 0, -4, 'L', 7, -4);
    }

    if (level === 7) {
        path.push('M', 0, -1, 'L', 4, -1);
    } else if (level >= 8) {
        path.push('M', 0, -1, 'L', 7, -1);
    }

    return path;
};

/**
 * Draw the wind arrows. Each arrow path is generated by the windArrow function above.
 */
Meteogram.prototype.drawWindArrows = function (chart) {
    var meteogram = this;

    $.each(chart.series[0].data, function (i, point) {
        var arrow, x, y;

        if (meteogram.resolution > 36e5 || i % 2 === 0) {

            // Draw the wind arrows
            x = point.plotX + chart.plotLeft + 7;
            y = 255;
            if (meteogram.windSpeedNames[i] === 'Calm') {
                arrow = chart.renderer.circle(x, y, 10).attr({
                    fill: 'none'
                });
            } else {
                arrow = chart.renderer.path(
                    meteogram.windArrow(meteogram.windSpeedNames[i])
                ).attr({
                    rotation: parseInt(meteogram.windDirections[i], 10),
                    translateX: x, // rotation center
                    translateY: y // rotation center
                });
            }
            arrow.attr({
                stroke: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                'stroke-width': 1.5,
                zIndex: 5
            })
            .add();

        }
    });
};

/**
 * Draw blocks around wind arrows, below the plot area
 */
Meteogram.prototype.drawBlocksForWindArrows = function (chart) {
    var xAxis = chart.xAxis[0],
        x,
        pos,
        max,
        isLong,
        isLast,
        i;

    for (pos = xAxis.min, max = xAxis.max, i = 0; pos <= max + 36e5; pos += 36e5, i += 1) {

        // Get the X position
        isLast = pos === max + 36e5;
        x = Math.round(xAxis.toPixels(pos)) + (isLast ? 0.5 : -0.5);

        // Draw the vertical dividers and ticks
        if (this.resolution > 36e5) {
            isLong = pos % this.resolution === 0;
        } else {
            isLong = i % 2 === 0;
        }
        chart.renderer.path(['M', x, chart.plotTop + chart.plotHeight + (isLong ? 0 : 28),
            'L', x, chart.plotTop + chart.plotHeight + 32, 'Z'])
            .attr({
                'stroke': chart.options.chart.plotBorderColor,
                'stroke-width': 1
            })
            .add();
    }
};

/**
 * Get the title based on the XML data
 */
//Meteogram.prototype.getTitle = function () {
//  return 'Meteogram for ' + this.xml.location.name + ', ' + this.xml.location.country;
//};
Meteogram.prototype.getTitle = function () {
    return 'Meteogram for ' + this.xml.location.name + ', ' + this.xml.location.country;
};

/**
 * Build and return the Highcharts options structure
 */
Meteogram.prototype.getChartOptions = function () {
    var meteogram = this;

    return {
        chart: {
            renderTo: this.container,
            marginBottom: 70,
            marginRight: 40,
            marginTop: 50,
            plotBorderWidth: 1,
            width: 800,
            height: 310
        },

        title: {
            text: this.getTitle(),
            align: 'left'
        },

        credits: {
            text: 'Forecast from <a href="http://yr.no">yr.no</a>',
            href: this.xml.credit.link['@attributes'].url,
            position: {
                x: -40
            }
        },

        tooltip: {
            shared: true,
            useHTML: true,
            formatter: function () {
                return meteogram.tooltipFormatter(this);
            }
        },

        xAxis: [{ // Bottom X axis
            type: 'datetime',
            tickInterval: 2 * 36e5, // two hours
            minorTickInterval: 36e5, // one hour
            tickLength: 0,
            gridLineWidth: 1,
            gridLineColor: (Highcharts.theme && Highcharts.theme.background2) || '#F0F0F0',
            startOnTick: false,
            endOnTick: false,
            minPadding: 0,
            maxPadding: 0,
            offset: 30,
            showLastLabel: true,
            labels: {
                format: '{value:%H}'
            }
        }, { // Top X axis
            linkedTo: 0,
            type: 'datetime',
            tickInterval: 24 * 3600 * 1000,
            labels: {
                format: '{value:<span style="font-size: 12px; font-weight: bold">%a</span> %b %e}',
                align: 'left',
                x: 3,
                y: -5
            },
            opposite: true,
            tickLength: 20,
            gridLineWidth: 1
        }],

        yAxis: [{ // temperature axis
            title: {
                text: null
            },
            labels: {
                format: '{value}°',
                style: {
                    fontSize: '10px'
                },
                x: -3
            },
            plotLines: [{ // zero plane
                value: 0,
                color: '#BBBBBB',
                width: 1,
                zIndex: 2
            }],
            // Custom positioner to provide even temperature ticks from top down
            tickPositioner: function () {
                var max = Math.ceil(this.max) + 1,
                    pos = max - 12, // start
                    ret;

                if (pos < this.min) {
                    ret = [];
                    while (pos <= max) {
                        ret.push(pos += 1);
                    }
                } // else return undefined and go auto

                return ret;

            },
            maxPadding: 0.3,
            tickInterval: 1,
            gridLineColor: (Highcharts.theme && Highcharts.theme.background2) || '#F0F0F0'

        }, { // precipitation axis
            title: {
                text: null
            },
            labels: {
                enabled: false
            },
            gridLineWidth: 0,
            tickLength: 0

        }, { // Air pressure
            allowDecimals: false,
            title: { // Title on top of axis
                text: 'hPa',
                offset: 0,
                align: 'high',
                rotation: 0,
                style: {
                    fontSize: '10px',
                    color: Highcharts.getOptions().colors[2]
                },
                textAlign: 'left',
                x: 3
            },
            labels: {
                style: {
                    fontSize: '8px',
                    color: Highcharts.getOptions().colors[2]
                },
                y: 2,
                x: 3
            },
            gridLineWidth: 0,
            opposite: true,
            showLastLabel: false
        }],

        legend: {
            enabled: false
        },

        plotOptions: {
            series: {
                pointPlacement: 'between'
            }
        },


        series: [{
            name: 'Temperature',
            data: this.temperatures,
            type: 'spline',
            marker: {
                enabled: false,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            zIndex: 1,
            color: '#FF3333',
            negativeColor: '#48AFE8'
        }, {
            name: 'Precipitation',
            data: this.precipitations,
            type: 'column',
            color: '#68CFE8',
            yAxis: 1,
            groupPadding: 0,
            pointPadding: 0,
            borderWidth: 0,
            shadow: false,
            dataLabels: {
                enabled: true,
                formatter: function () {
                    if (this.y > 0) {
                        return this.y;
                    }
                },
                style: {
                    fontSize: '8px'
                }
            },
            tooltip: {
                valueSuffix: 'mm'
            }
        }, {
            name: 'Air pressure',
            color: Highcharts.getOptions().colors[2],
            data: this.pressures,
            marker: {
                enabled: false
            },
            shadow: false,
            tooltip: {
                valueSuffix: ' hPa'
            },
            dashStyle: 'shortdot',
            yAxis: 2
        }]
    };
};

/**
 * Post-process the chart from the callback function, the second argument to Highcharts.Chart.
 */
Meteogram.prototype.onChartLoad = function (chart) {

    this.drawWeatherSymbols(chart);
    this.drawWindArrows(chart);
    this.drawBlocksForWindArrows(chart);

};

/**
 * Create the chart. This function is called async when the data file is loaded and parsed.
 */
Meteogram.prototype.createChart = function () {
    var meteogram = this;
    this.chart = new Highcharts.Chart(this.getChartOptions(), function (chart) {
        meteogram.onChartLoad(chart);
    });
};

/**
 * Handle the data. This part of the code is not Highcharts specific, but deals with yr.no's
 * specific data format
 */
Meteogram.prototype.parseYrData = function () {

    var meteogram = this,
        xml = this.xml,
        pointStart;

    if (!xml || !xml.forecast) {
        $('#loading').html('<i class="fa fa-frown-o"></i> Failed loading data, please try again later');
        return;
    }

    // The returned xml variable is a JavaScript representation of the provided XML,
    // generated on the server by running PHP simple_load_xml and converting it to
    // JavaScript by json_encode.
    $.each(xml.forecast.tabular.time, function (i, time) {
        // Get the times - only Safari can't parse ISO8601 so we need to do some replacements
        var from = time['@attributes'].from + ' UTC',
            to = time['@attributes'].to + ' UTC';

        from = from.replace(/-/g, '/').replace('T', ' ');
        from = Date.parse(from);
        to = to.replace(/-/g, '/').replace('T', ' ');
        to = Date.parse(to);

        if (to > pointStart + 4 * 24 * 36e5) {
            return;
        }

        // If it is more than an hour between points, show all symbols
        if (i === 0) {
            meteogram.resolution = to - from;
        }

        // Populate the parallel arrays
        meteogram.symbols.push(time.symbol['@attributes']['var'].match(/[0-9]{2}[dnm]?/)[0]); // eslint-disable-line dot-notation
        meteogram.symbolNames.push(time.symbol['@attributes'].name);

        meteogram.temperatures.push({
            x: from,
            y: parseInt(time.temperature['@attributes'].value, 10),
            // custom options used in the tooltip formatter
            to: to,
            index: i
        });

        meteogram.precipitations.push({
            x: from,
            y: parseFloat(time.precipitation['@attributes'].value)
        });
        meteogram.windDirections.push(parseFloat(time.windDirection['@attributes'].deg));
        meteogram.windDirectionNames.push(time.windDirection['@attributes'].name);
        meteogram.windSpeeds.push(parseFloat(time.windSpeed['@attributes'].mps));
        meteogram.windSpeedNames.push(time.windSpeed['@attributes'].name);

        meteogram.pressures.push({
            x: from,
            y: parseFloat(time.pressure['@attributes'].value)
        });

        if (i === 0) {
            pointStart = (from + to) / 2;
        }
    });

    // Smooth the line
    this.smoothLine(this.temperatures);

    // Create the chart when the data is loaded
    this.createChart();
};
// End of the Meteogram protype



$(function () { // On DOM ready...

    // Set the hash to the yr.no URL we want to parse
    if (!location.hash) {
        var place = 'Taiwan/Taiwan/Taichung';
        //'Taiwan/Taiwan/Taichung'
        //'Taiwan/Taiwan/Taichung~1668392'
        //'Taiwan/Annet/Taichung_Tw-Afb'
        //place = 'United_Kingdom/England/London'
        //place = 'France/Rhône-Alpes/Val_d\'Isère~2971074';
        //place = 'Norway/Sogn_og_Fjordane/Vik/Målset';
        //place = 'United_States/California/San_Francisco';
        //place = 'United_States/Minnesota/Minneapolis';
        location.hash = 'https://www.yr.no/place/' + place + '/forecast_hour_by_hour.xml';

    }

    // Then get the XML file through Highcharts' jsonp provider, see
    // https://github.com/highcharts/highcharts/blob/master/samples/data/jsonp.php
    // for source code.
    $.getJSON(
        'https://www.highcharts.com/samples/data/jsonp.php?url=' + location.hash.substr(1) + '&callback=?',
        function (xml) {
            window.meteogram = new Meteogram(xml, 'container');
        }
    );

});
		</script>

	</head>
	<body>
 
		<!-- Header -->
			<div id="header">

				<div class="top">

					<!-- Logo -->
						<div id="logo">
							<span class="image avatar48"><img src="images/avatar.jpg" alt="" /></span>
							<h1 id="title">Daniel Chi</h1>
							<p>Intelligent Pest Monitoring</p>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<!--

								Prologue's nav expects links in one of two formats:

								1. Hash link (scrolls to a different section within the page)

								   <li><a href="#foobar" id="foobar-link" class="icon fa-whatever-icon-you-want skel-layers-ignoreHref"><span class="label">Foobar</span></a></li>

								2. Standard link (sends the user to another page/site)

								   <li><a href="http://foobar.tld" id="foobar-link" class="icon fa-whatever-icon-you-want"><span class="label">Foobar</span></a></li>

							-->
							<ul>
								<li><a href="#top" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-home">Intro</span></a></li>
								<li><a href="#about" id="about-link" class="skel-layers-ignoreHref"><span class="icon fa-area-chart">Insect Number</span></a></li>
                                <li><a href="#portfolio" id="portfolio-link" class="skel-layers-ignoreHref"><span class="icon fa-line-chart">Time - Insect Number</span></a></li>
                                <li><a href="#tem" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-tasks">Sensor Report</span></a></li>
                                <li><a href="#weather" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-sun-o">Weather</span></a></li>
                                <li><a href="#warning" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-warning">Warning</span></a></li>

								<li><a href="#contact" id="contact-link" class="skel-layers-ignoreHref"><span class="icon fa-envelope">Contact</span></a></li>
                    
							</ul>
						</nav>

				</div>

				<div class="bottom">

					<!-- Social Icons -->
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
							<li><a href="#" class="icon fa-envelope"><span class="label">Email</span></a></li>
						</ul>

				</div>

			</div>

		<!-- Main -->
			<div id="main">

				<!-- Intro -->
					<section id="top" class="one dark cover">
						<div class="container">

							<header>
								<h2 class="alt"><strong>Intalligent Pest Monitoring</strong><br />
								</h2>
								<p style="font-weight: bold;color:white">智慧害蟲監控系統，我們希望利用這個系統去監控農作物區，利用樹莓派加上鏡頭，定期的去拍攝農作物區上的黏蟲紙，並圖片分析出此區域的害蟲數目，已告之使用者何時需要噴灑農藥，用來降低農藥量，在配合上Arduino的感測器，量測該單位系統附近的溫度、濕度，再結合當地天氣狀況，以達到監控的目的，協助使用者維護農作物區域。</p>
							</header>

							<footer>
								<a href="#about" class="button scrolly">START</a>
							</footer>

						</div>
					</section>

				
				<!-- Insect Number -->
					<section id="about" class="three">
						<div class="container">

							<header>
								<h2>Insect Number</h2>
							</header>
                            <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
                            <script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
                            <script src="http://code.highcharts.com/maps/modules/map.js"></script>
                            
                            <div id="containerr" style="min-width: 300px; max-width: 1400px; height:600px;"></div>
							<p>計算出區塊單位面積的蟲子數目</p>

						</div>
					</section>
                 <!-- Time-InsectNumber -->
					<section id="portfolio" class="two">
						<div class="container">

							<header>
								<h2>Time - Insect Number</h2>
							</header>
                            	<form method="post" action="daniel.php" onsubmit="checkForm()">
	                            	<?php
									  $timezone = "Asia/Colombo";
									  date_default_timezone_set($timezone);
									  $today = date("Y-m-d");
									?>
                                    <h4>Date <input type = "date" name="theDay" value="<?php echo $today; ?>"/>
                                    
                                    <input type = "submit" value = "查詢" style="width:2.7cm;height:1.35cm;font-size:18pt;text-align:center;padding:2pt"/>
                                    <input type = "reset" value = "清除" style="width:2.7cm;height:1.35cm;font-size:18pt;text-align:center;padding:2pt"/></h4>
                                </form><br>
                                <script type="text/javascript">
                                	function checkForm(){
                                		document.getElementsByName("theDay").value="<?php echo $a; ?>";
                                	}
                                </script>

                            <div id="cont" style="min-width: 300px; max-width: 1400px; height:600px;margin: 0 auto"></div>

							<p>各個點的時間蟲數比較</p>


						</div>
					</section>
                
                				<!-- Sensor Report -->
					<section id="tem" class="two">
						<div class="container">

							<header>
								<h2>Sensor Report</h2>
							</header>

								<form method="post" action="daniel.php" >
	                            	
                                    <h4>Date <input type = "date" name="theSensorDay" value="<?php echo $today; ?>"/>
                                    
                                    <input type = "submit" value = "查詢" style="width:2.7cm;height:1.35cm;font-size:18pt;text-align:center;padding:2pt"/>
                                    <input type = "reset" value = "清除" style="width:2.7cm;height:1.35cm;font-size:18pt;text-align:center;padding:2pt"/></h4>
                                </form><br>

                            <div id="containerrr" style="min-width: 300px; max-width: 1400px; height:600px;"></div>
							<p>Sensor所感應到的 溫度 濕度 光度  </p>

						</div>
					</section>
                				<!-- Weather -->
					<section id="weather" class="two">
						<div class="container">

							<header>
								<h2>Weather</h2>
							</header>
                            <link href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
                            <div id="container" style="width: 800px; height: 310px; margin: 0 auto">
                                <div style="margin-top: 100px; text-align: center" id="loading">
                                    <i class="fa fa-spinner fa-spin"></i> Loading data from external source
                                </div>
                            </div>
							<p>顯示當地近況的天氣預報 </p>

						</div>
					</section>
                				<!-- Warning -->
					<section id="warning" class="two">
						<div class="container">

							<header>
								<h2>Warning</h2>
							</header>
							<p>Ｃ和Ｄ區域目前的蟲數偏高，需立即進行處理。<br>
                                Ｂ區的平均濕度過高，請小心留意。<br>
                                下禮拜會有大雨發生，請小心留意。
                            </p>

						</div>
					</section>


                
				<!-- Contact -->
					<section id="contact" class="four">
						<div class="container">

							<header>
								<h2>Contact</h2>
							</header>

							<p></p>

							<form method="post" action="#">
								<div class="row">
									<div class="6u 12u$(mobile)"><input type="text" name="name" placeholder="Name" /></div>
									<div class="6u$ 12u$(mobile)"><input type="text" name="email" placeholder="Email" /></div>
									<div class="12u$">
										<textarea name="message" placeholder="Message"></textarea>
									</div>
									<div class="12u$">
										<input type="submit" value="Send Message" />
									</div>
								</div>
							</form>

						</div>
					</section>
               


			</div>

		<!-- Footer -->
			<div id="footer">

				<!-- Copyright -->
					<ul class="copyright">
						<li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>

			</div>

		<!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
		  	<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollzer.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>