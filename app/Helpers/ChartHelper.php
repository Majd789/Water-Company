<?php

namespace App\Helpers;

class ChartHelper
{
    public static function renderBarChart($labels, $data, $colors)
    {
        return "
            <canvas class='chart' width='400' height='200'></canvas>
            <script>
                var ctx = document.querySelector('.chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: $labels,
                        datasets: [{
                            label: 'الإحصائيات',
                            data: $data,
                            backgroundColor: $colors,
                            borderColor: $colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: { 
                                beginAtZero: true 
                            }
                        }
                    }
                });
            </script>
        ";
    }
}
