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
                    <div id="books-per-user-canvas">
                        <canvas id="books-per-user"
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
                        <label for="from2">From:</label>
                        <input type="date" class="form-control date-helper-2" placeholder="From" id="from2">
                    </div>
                    <div class="col-md-6">
                        <label for="to2">To:</label>
                        <input type="date" class="form-control date-helper-2" placeholder="To" id="to2">
                    </div>
                </div>
                <div class="chart">
                    <div id="books-per-genre-canvas">
                        <canvas id="books-per-genre"
                                style="min-height: 250px; height: 300px; max-height: none; max-width: 100%; display: block; width: 634px;"
                                class="chartjs-render-monitor">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">

            </div>
            <div class="col-md-6">

            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-4">

            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        generateBooksPerUser();
        $(".date-helper").change(function (){
            generateBooksPerUser();
        });

        generateBooksPerGenre();
        $(".date-helper-2").change(function (){
            generateBooksPerGenre();
        });

    });

    function generateBooksPerUser() {
        $("#books-per-user-canvas").empty();
        $("#books-per-user-canvas").append(
            '<canvas id="books-per-user"' +
            'style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 634px;"' +
            'class="chartjs-render-monitor"></canvas>'
        );

        let from =$("#from").val();
        let to =$("#to").val();
        let url = `/getBooksPerUser?from=${from}&to=${to}`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.name; //ovi podaci se menjaju iz grafa u graf, koji podatak pratimo
            });

            let values = result.map(function (e) {
                return e.number_of_books; //ovi podaci se menjaju iz grafa u graf, koje su vrednosti za njega
            });

            let setData = {
                labels: labels,
                datasets: [
                    {
                        label: "Number of books per user",
                        data: values,
                        backgroundColor: "rgb(64,143,123)", // plava providna
                        borderColor: "rgb(8,89,89)",
                        borderWidth: 1
                    }]
            }

            let options = {
                plugins: {
                    title: {
                        display: true,
                        text: "Read books per user"
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

            let graph = $("#books-per-user").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'bar', options);
        });
    }

    function generateBooksPerGenre() {
        $("#books-per-genre-canvas").empty();
        $("#books-per-genre-canvas").append(
            '<canvas id="books-per-genre"' +
            'style="min-height: 250px; height: 300px; max-height: none; max-width: 100%; display: block; width: 634px;"' +
            'class="chartjs-render-monitor"></canvas>'
        );

        let from =$("#from2").val();
        let to =$("#to2").val();
        let url = `/getBooksPerGenre?from=${from}&to=${to}`;

        $.getJSON(url, function (result) {
            let labels = result.map(function (e) {
                return e.name; //ovi podaci se menjaju iz grafa u graf, koji podatak pratimo
            });

            let values = result.map(function (e) {
                return e.books_completed; //ovi podaci se menjaju iz grafa u graf, koje su vrednosti za njega
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
                        text: "Read books per genre"
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
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

            let graph = $("#books-per-genre").get(0).getContext('2d'); //kom canvasu prosledjujemo

            createGraph(setData, graph, 'bar', options);
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