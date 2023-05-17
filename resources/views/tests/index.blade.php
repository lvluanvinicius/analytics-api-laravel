@extends('master.index')

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
        integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

    {{-- <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css"> --}}
    {{-- <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts-dark.css"> --}}


    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
@endsection

@section('content')
    <form id="form_filter_graph">
        <div
            class="grid md:grid-cols-2 dark:bg-dracula-darker m-2 px-4 py-2 pb-6 rounded-md border shadow-md shadow-dracula-darker/10">
            <div class="col-span-1 mb-2 px-4">
                <div class="">
                    <label for="onus_equipament" class="leading-10">Equipamento:</label>
                    <select name="onus_equipament" id="onus_equipament"
                        class="dark:text-white dark:bg-dracula-darker-900 outline-none p-0 w-full px-4 py-1 rounded-md border dark:border-dracula-darker-50/20">
                        <option value="">Selecione um equipamento</option>
                        @foreach ($equipaments as $equipament)
                            <option value="{{ $equipament->name }}">{{ $equipament->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="">
                    <label for="onus_port" class="leading-10">Porta:</label>
                    <select name="onus_port" id="onus_port" disabled
                        class="dark:text-white dark:bg-dracula-darker-900 outline-none p-0 w-full px-4 py-1 rounded-md border dark:border-dracula-darker-50/20">
                        <option value="">Selecione um valor</option>
                    </select>
                </div>
            </div>

            <div class="col-span-1 px-4">
                <div class="mb-2 ">
                    <label class="leading-10">Selecione a data:</label>
                    <div class="flex gap-1 flex-wrap justify-between">
                        <div class="relative w-full md:w-[49%]">
                            <i class="fa-regular fa-calendar-days dark:text-white absolute top-2 left-2 opacity-70"></i>
                            <input type="text" id="time_from_range" placeholder="Início" name="time_from"
                                class="dark:bg-dracula-darker-900 p-1 rounded-md pl-7 outline-none w-full" />
                        </div>

                        <div class="relative w-full md:w-[49%]">
                            <i class="fa-regular fa-calendar-days dark:text-white absolute top-2 left-2 opacity-70"></i>
                            <input type="text" id="time_till_range" placeholder="Final" name="time_till"
                                class="dark:bg-dracula-darker-900 p-1 rounded-md pl-7 outline-none w-full" />
                        </div>
                    </div>
                </div>

                <div class="">
                    <label for="onus_names" class="leading-10">Selecione um nome:</label>
                    <div class="flex justify-between gap-2">
                        <div id="load_onu_clients"
                            class="border px-2 rounded-md dark:border-dracula-darker-50/20 py-1 cursor-pointer">
                            <i class="fa-solid fa-arrows-rotate dark:text-white dark:bg-dracula-darker-900"></i>
                        </div>
                        <select disabled name="onus_names" id="onus_names"
                            class="dark:text-white dark:bg-dracula-darker-900 outline-none p-0 w-full px-4 py-1 rounded-md border dark:border-dracula-darker-50/20"></select>
                    </div>
                </div>
            </div>


            <div class="col-span-2 px-4 mt-4 flex justify-between">
                <div></div>
                <div>
                    <button type="submit"
                        class="dark:bg-dracula-darker font-bold border rounded-md px-6 py-0.5 dark:opacity-80 dark:border-dracula-darker-50/20">
                        Carregar
                    </button>
                </div>
            </div>

        </div>
    </form>

    <div class="border m-2 px-4 py-2 pb-6 rounded-md shadow-md shadow-dracula-darker/10">
        <figure class="highcharts-figure">
            <div id="dbm_container" class="w-full"></div>
        </figure>
    </div>
@endsection


@section('js-content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.4/d3.min.js"
        integrity="sha512-nfUlp2ZWPKWlnAH/OsMeAqRSYBxOdPYeBXwceyw6QqqZ7uTT/s5/eS1hMKphjVBouk0jf/JQ8ULVJRnEqSHkXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>


    <script>
        // Renderizador dos seletor de timerange
        function renderTimeFlatpickr(selectorInput, theme) {
            document.addEventListener('DOMContentLoaded', () => {
                const flatpickrInput = document.getElementById(selectorInput);
                if (flatpickrInput) {
                    flatpickr(flatpickrInput, {

                        enableTime: true,
                        dateFormat: 'Y-m-d H:i',
                        theme: theme,
                    });
                }
            });
        }

        // Renderizando campos de range de data.
        renderTimeFlatpickr('time_from_range', 'dark');
        renderTimeFlatpickr('time_till_range', 'dark');


        // Criando evendo de alteração no valor de equipamentos.
        document.getElementById('onus_equipament').addEventListener('change', function() {
            // Recuperando valor de equipamento.
            let equipament = this.value;

            // Limpando campo de portas.
            document.getElementById('onus_port').innerHTML = "";
            document.getElementById('onus_port').disabled = true;

            // Limpando campo de nomes.
            document.getElementById('onus_names').innerHTML = "";
            document.getElementById('onus_names').disabled = true;

            // Criando rota.
            let route_action = `{{ route('onus.proxy.ports.get', ['equipament' => '_EQUIPAMENT_']) }}`.replace(
                '_EQUIPAMENT_', equipament);

            if (equipament == "" || equipament == null || equipament == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Nenhum equipamento selecionada.",
                    position: 'topRight'
                });
            } else {
                // Realizando requisição.
                fetch(route_action).then((response) => {
                    if (!response.ok) {
                        iziToast.error({
                            title: "Ooops!",
                            message: 'Houve um problema na resposta com a rota de proxy de portas.',
                            position: 'topRight'
                        });
                        console.error("Houve um problema na resposta com a rota de proxy de portas.");
                    }

                    return response.json(); // Retorna nova promessa com os dados da requisição.
                }).then((response) => {

                    if (response.status) {
                        const data = response.data;

                        // Buscando elemento de portas.
                        let onus_port = document.getElementById('onus_port');
                        onus_port.removeAttribute('disabled'); // Habilitando campo.

                        // Criando elementos Options.
                        data.forEach(port => {
                            let option = document.createElement('option');
                            option.value = port.port;
                            option.innerText = port.port

                            onus_port.appendChild(option);
                        });

                    } else {
                        iziToast.error({
                            title: "Ooops!",
                            message: response.message,
                            position: 'topRight'
                        });
                        console.error(response.message);
                    }

                }).catch((err) => {
                    iziToast.error({
                        title: "Ooops!",
                        message: "Houve um problema ao buscar as portas.",
                        position: 'topRight'
                    });
                    console.error(err);
                });;
            }
        });

        // Carrega os nome de Onus.
        document.getElementById('load_onu_clients').addEventListener('click', function() {
            // Recuperando valor de porta.
            let port = document.getElementById('onus_port').value;
            // Recuperando valor de equipamento.
            let equipament = document.getElementById('onus_equipament').value

            // Verificando se o equipament foi selecionado.
            if (equipament == "" || equipament == null || equipament == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Nenhum equipamento selecionada.",
                    position: 'topRight'
                });
            }
            // Verificando se a porta foi selecionada.
            else if (port == "" || port == null || port == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Nenhuma porta selecionada.",
                    position: 'topRight'
                });
            } else {
                const searchParams = new URLSearchParams({
                    equipament,
                    port
                }).toString();

                // Criando rota.
                let route_action = `{{ route('onus.proxy.names.get') }}?`;

                // Realizando requisição.
                fetch(route_action + searchParams).then((response) => {
                    if (!response.ok) {
                        iziToast.error({
                            title: "Ooops!",
                            message: 'Houve um problema na resposta com a rota de proxy de nomes.',
                            position: 'topRight'
                        });
                        console.error("Houve um problema na resposta com a rota de proxy de nomes.");
                    }

                    return response.json(); // Retorna nova promessa com os dados da requisição.
                }).then((response) => {
                    if (response.status) {
                        const data = response.data;

                        // // Buscando elemento de names.
                        let onus_port = document.getElementById('onus_names');
                        onus_port.removeAttribute('disabled'); // Habilitando campo.
                        onus_port.innerHTML = "";


                        // Criando elementos Options.
                        data.forEach(name => {
                            let option = document.createElement('option');
                            option.value = name;
                            option.innerText = name;

                            onus_port.appendChild(option);
                        });

                    } else {
                        iziToast.error({
                            title: "Ooops!",
                            message: response.message,
                            position: 'topRight'
                        });
                        console.error(response.message);
                    }

                }).catch((err) => {
                    iziToast.error({
                        title: "Ooops!",
                        message: "Houve um problema ao buscar as portas.",
                        position: 'topRight'
                    });
                    console.error(err);
                });
            }

        });

        let chartDBM;
        let dataDBMRx = [];
        let dataDBMTx = [];
        let dataTimes = [];

        function renderChartDBM() {
            chartDBM = Highcharts.chart('dbm_container', {
                chart: {
                    type: 'area',
                    height: '310px',
                    backgroundColor: 'transparent',
                    height: '300px'
                },
                title: {
                    text: 'Selecione um Filtro',
                },
                colors: ['#90caf9', '#9fa8da', '#b39ddb', '#ce93d8', '#f48fb1', '#ffcc80', '#ffab91'],
                yAxis: {
                    labels: {
                        formatter: function() {
                            return this.value
                        },
                    },
                    minPadding: 0.2,
                    maxPadding: 0.2,
                    title: {
                        text: false,
                        margin: 20
                    },
                    min: -30,
                    max: 10
                },
                xAxis: {
                    type: 'datetime',
                    categories: dataTimes,
                    labels: {
                        formatter: function() {
                            const date = new Date(this.value);
                            const formattedTime = date.getHours().toString().padStart(2, "0") + ":" + date
                                .getMinutes().toString().padStart(2, "0");
                            return formattedTime;
                        }
                    }
                },
                plotOptions: {
                    area: {
                        marker: {
                            enabled: false,
                            symbol: "circle",
                            radius: 1,
                            states: {
                                hover: {
                                    enabled: true,
                                },
                            },
                        },
                    },
                },
                legend: {
                    enabled: false
                },
                tooltip: {

                    formatter: function() {
                        var tooltipText = '<b> Coleta: ' + this.x + '</b>';

                        $.each(this.points, function() {
                            tooltipText += '<br/>' + this.series.name + ': ' + this.y;
                        });

                        return tooltipText;
                    },
                    shared: true
                },
                series: [{
                        name: 'TX',
                        data: dataDBMTx,
                        color: 'rgba(107, 163, 246, .8)',
                    },
                    {
                        name: 'RX',
                        data: dataDBMRx,
                        color: 'rgba(31, 96, 196,.7)',
                    },
                ],
            });
        }

        // Iniciando gráfico.
        renderChartDBM();

        // Carregando dados de Onus e renderizando gráfico.
        document.getElementById('form_filter_graph').addEventListener('submit', function(event) {
            event.preventDefault(); // Segura evento de submit.

            // Recuperando valores.
            let equipament = document.getElementById('onus_equipament').value;
            let port = document.getElementById('onus_port').value;
            let time_from = document.getElementById('time_from_range').value;
            let time_till = document.getElementById('time_till_range').value;
            let onu_name = document.getElementById('onus_names').value;

            // Validando valores.
            // Verificando se valor de equipamento foi selecionado.
            if (equipament == "" || equipament == null || equipament == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Nenhum equipamento selecionado.",
                    position: 'topRight'
                });
            }
            // Verificando se valor de porta foi selecionado.
            else if (port == "" || port == null || port == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Nenhuma porta selecionada.",
                    position: 'topRight'
                });
            }
            // Verificando se uma data de início foi selecionada.
            else if (time_from == "" || time_from == null || time_from == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Selecione a data de início.",
                    position: 'topRight'
                });
            }

            // Verificando se uma data fim foi selecionada.
            else if (time_till == "" || time_till == null || time_till == undefined) {
                iziToast.warning({
                    title: "Ooops!",
                    message: "Selecione a data final.",
                    position: 'topRight'
                });
            } else {
                const searchParams = new URLSearchParams({
                    equipament,
                    port,
                    time_from,
                    time_till,
                    onu_name
                }).toString();

                // Criando rota.
                let route_action = `{{ route('onus.proxy.datas.onus.get') }}?`;

                // Realizando requisição.
                fetch(route_action + searchParams).then((response) => {
                    if (!response.ok) {
                        iziToast.error({
                            title: "Ooops!",
                            message: 'Houve um problema na resposta com a rota de proxy de nomes.',
                            position: 'topRight'
                        });
                        console.error("Houve um problema na resposta com a rota de proxy de nomes.");
                    }

                    return response.json(); // Retorna nova promessa com os dados da requisição.
                }).then((response) => {
                    if (response.status) {
                        const data = response.data;

                        let txdBm = [];
                        let rxdBm = [];
                        let dateTime = [];

                        // Carregando novos dados.
                        data.forEach(data => {
                            txdBm.push(parseFloat(data.m_tx));
                            rxdBm.push(parseFloat(data.m_rx));
                            dateTime.push(data.collection_date);
                        });

                        // Limpando array auxiliar de dados.
                        dataDBMTx = txdBm
                        dataDBMRx = rxdBm
                        dataTimes = dateTime

                        chartDBM.series[0].setData([0]);
                        chartDBM.series[1].setData([0]);

                        if (!chartDBM) {
                            // Renderizando grafico.
                            renderChartDBM();
                        } else {
                            chartDBM.series[0].setData(dataDBMTx);
                            chartDBM.series[1].setData(dataDBMRx);
                            chartDBM.xAxis[0].setCategories(dataTimes);
                        }

                        // Atualiza o titulo com o nome da interface selecionada.
                        chartDBM.setTitle({
                            text: onu_name
                        });

                    } else {
                        iziToast.error({
                            title: "Ooops!",
                            message: response.message,
                            position: 'topRight'
                        });
                        console.error(response.message);
                    }

                }).catch((err) => {
                    iziToast.error({
                        title: "Ooops!",
                        message: "Houve um problema ao buscar as portas.",
                        position: 'topRight'
                    });
                    console.error(err);
                });

            }
        });

    </script>
@endsection
