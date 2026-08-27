<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <h5 class="mb-0 text-info text-gradient text-sm">Select desired range:</h5>
                    <div class="col-md-6">
                        <label for="from">From:</label>
                        <input type="date" class="form-control date-helper" placeholder="From" id="from">
                    </div>
                    <div class="col-md-6">
                        <label for="to">To:</label>
                        <input type="date" class="form-control date-helper" placeholder="To" id="to">
                    </div>
                </div>

                <div class="chart">
                    <div id="number-of-books-per-month-canvas">
                        <canvas id="number-of-books-per-month"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">

                        </canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div class="row">
                    <h5 class="mb-0 text-info text-gradient text-sm">Select desired range:</h5>
                    <div class="col-md-6">
                        <label for="from">From:</label>
                        <input type="date" class="form-control date-helper" placeholder="From" id="from">
                    </div>
                    <div class="col-md-6">
                        <label for="to">To:</label>
                        <input type="date" class="form-control date-helper" placeholder="To" id="to">
                    </div>
                </div>

                <div class="chart">
                    <div id="number-of-pages-per-month-canvas">
                        <canvas id="number-of-pages-per-month"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">

                        </canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart">
                    <div id="number-of-books-per-status-canvas">
                        <canvas id="number-of-books-per-status"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">

                        </canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart">
                    <div id="number-of-books-per-page-count-canvas">
                        <canvas id="number-of-books-per-page-count"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">

                        </canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-4">
                <div class="chart">
                    <div id="number-of-genres-canvas">
                        <canvas id="number-of-genres"
                                style="min-height: 250px; height: 300px; max-height: none; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">
<!--                            promeni height ako se neka labela ne vidi-->
                        </canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="chart">
                            <div id="books-vs-pages-per-month-canvas">
                                <canvas id="books-vs-pages-per-month"
                                        style="min-height: 300px; height: 300px; max-width: 100%;"
                                        class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        generateNumberOfBooksPerMonth();
        generateNumberOfPagesPerMonth();

        $(".date-helper").change(function (){
            generateNumberOfBooksPerMonth();
            generateNumberOfPagesPerMonth();
        });

        generateNumberOfGenres();
        generateNumberOfBooksPerPageCount();
        generateNumberOfBooksPerStatus();

        generateBooksVsPagesPerMonth();

    });

    function generateNumberOfBooksPerMonth() {
        $("#number-of-books-per-month-canvas").empty();
        $("#number-of-books-per-month-canvas").append(
            '<canvas id="number-of-books-per-month"' +
            'style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"' +
            'class="chartjs-render-monitor"></canvas>'
        );

        let from =$("#from").val();
        let to =$("#to").val();
        let url = `/getNumberOfBooksPerMonth?from=${from}&to=${to}`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.month; //ovi podaci se menjaju iz grafa u graf
            });

            let values = result.map(function (e) {
                return e.number_of_books; //ovi podaci se menjaju iz grafa u graf
            });

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books per month",
                        data: values,
                        barThickness: 25,
                        maxBarThickness: 25

                    }]
            }

            let options = {
                plugins: {
                    title: {
                        display: true,
                        text: "Read books per month"
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            // prikazuje samo cele brojeve
                            callback: function (value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        beginAtZero: true
                    }
                }
            }

            let graph = $("#number-of-books-per-month").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'bar', options);
        });
    }

    function generateNumberOfPagesPerMonth() {

        $("#number-of-pages-per-month-canvas").empty();
        $("#number-of-pages-per-month-canvas").append(
            '<canvas id="number-of-pages-per-month"' +
            'style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"' +
            'class="chartjs-render-monitor"></canvas>'
        );
    
        let from =$("#from").val();
        let to =$("#to").val();
        let url = `/getNumberOfPagesPerMonth?from=${from}&to=${to}`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.month; //ovi podaci se menjaju iz grafa u graf
            });

            let values = result.map(function (e) {
                return e.number_of_pages; //ovi podaci se menjaju iz grafa u graf
            });

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of pages per month",
                        data: values,
                        tension: 0.1
                    }]
            }

            let options = {
                plugins: {
                    title: {
                        display: true,
                        text: "Read Pages per month"
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            // prikazuje samo cele brojeve
                            callback: function (value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        beginAtZero: false
                    }
                }
            }

            let graph = $("#number-of-pages-per-month").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'line', options);
        });
    }

    function generateNumberOfBooksPerStatus() {
        let url = `/getNumberOfBooksPerStatus`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.status; //ovi podaci se menjaju iz grafa u graf, i to u zavisnosti sta izvlacimo sa SELECT u queryju
            });

            let values = result.map(function (e) {
                return e.books_count; //ovi podaci se menjaju iz grafa u graf
            });

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books per reading status",
                        data: values
                    }]
            }

            let options = {
                plugins: {
                    title: {
                        display: true,
                        text: "Books per reading status"
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }

            let graph = $("#number-of-books-per-status").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'pie', options);
        });
    }

    function generateNumberOfBooksPerPageCount() {
        let url = `/getNumberOfBooksPerPageCount`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.page_range; //ovi podaci se menjaju iz grafa u graf
            });

            let values = result.map(function (e) {
                return e.books_count; //ovi podaci se menjaju iz grafa u graf
            });

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books per page count",
                        data: values
                    }]
            }

            let options = {
                plugins: {
                    title: {
                        display: true,
                        text: "Read books per page number"
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }

            let graph = $("#number-of-books-per-page-count").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'pie', options);
        });
    }



    function generateNumberOfGenres() {
        let url = `/getNumberOfGenres`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.genre; //ovi podaci se menjaju iz grafa u graf
            });

           // $("#number-of-genres").attr('height', labels.length * 30); //menjaj visinu dinamicki

            let values = result.map(function (e) {
                return e.genre_count; //ovi podaci se menjaju iz grafa u graf
            });

            let colors = [
                "rgba(6,78,149,0.7)",
                "rgba(54, 162, 235, 0.7)",
                "rgba(7,133,143,0.7)",
                "rgba(84,223,223,0.7)",
                "rgba(170,255,232,0.7)",
                "rgba(129,204,65,0.7)",
                "rgba(255, 159, 64, 0.7)",
                "rgba(253,249,20,0.7)",
                "rgba(159,122,205,0.7)",
                "rgba(255,128,210,0.7)",
                "rgba(230,13,71,0.7)",
            ];

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books per genre",
                        data: values,
                        backgroundColor: colors.slice(0, values.length),
                        borderColor: "rgba(255, 255, 255, 0.8)",
                        borderWidth: 1
                    }]
            }

            let options = {
                indexAxis: 'y',
                plugins: {
                    title: {
                        display: true,
                        text: "Top genres read"
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }

            let graph = $("#number-of-genres").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'bar', options);
        });
    }

    function generateBooksVsPagesPerMonth() {
        $("#books-vs-pages-per-month-canvas").empty();
        $("#books-vs-pages-per-month-canvas").append(
            '<canvas id="books-vs-pages-per-month" style="min-height: 300px; height: 300px; max-width: 100%;" class="chartjs-render-monitor"></canvas>'
        );

        $.when(
            $.getJSON("/getNumberOfBooksPerMonth"),
            $.getJSON("/getNumberOfPagesPerMonth")
        ).done(function (booksResult, pagesResult) {
            // jQuery vraća svaki kao [data, status, xhr]
            let booksData = booksResult[0];
            let pagesData = pagesResult[0];

            let labels = booksData.map(e => e.month);

            let bookValues = booksData.map(e => e.number_of_books);
            let pageValues = pagesData.map(e => e.number_of_pages);

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books",
                        data: bookValues,
                        borderColor: "#36A2EB",
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        yAxisID: 'y',
                        tension: 0.3
                    },
                    {
                        label: "Number of pages",
                        data: pageValues,
                        borderColor: "#FF6384",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        yAxisID: 'y1',
                        tension: 0.3
                    }
                ]
            };

            let options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "Books vs Pages per Month"
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Books'
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Pages'
                        }
                    }
                }
            };

            let graph = $("#books-vs-pages-per-month").get(0).getContext('2d');
            createGraph(setData, graph, 'line', options);
        });
    }




    function createGraph(setData, graph, chartType, options) {
        new Chart(graph, {
            type: chartType,
            data: setData,
            options: options
        });
    }
</script>