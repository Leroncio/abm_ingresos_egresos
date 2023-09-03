<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calculos') }}
        </h2>
    </x-slot>
    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 500px;">

                <div class="flex flex-wrap items-center justify-between p-6 -m-2 mb-4">
                    <div class="w-full md:w-1/2 p-2">
                      <p class="font-semibold text-xl text-coolGray-800">Calculos mensuales</p>
                      <p class="font-medium text-sm text-coolGray-500">Comparativa entre {{ $current["monthName"] }} y {{ $comparative["monthName"] }}</p>
                    </div>
                    <div class="md:w-1/2 p-2 justify-end">
                        <x-primary-button 
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'change-month')">
                            {{ __('Modificar Mes') }}
                        </x-primary-button>
                    </div>
                </div>

                <div class="flex">
                    <div class="sm:w-1/1 md:w-1/2 p-6 text-gray-900">
                        <div class="detail-container p-6">
                            <div><b>{{ __("Flujo en ").$current["monthName"] }}:</b> <span class="@if($current["profits"] < 0) text-red @else text-green @endif">${{ $current["profits"] }}</span></div>
                            <div><b>{{ __("Flujo en ").$comparative["monthName"] }}:</b> <span class="@if($comparative["profits"] < 0) text-red @else text-green @endif">${{ $comparative["profits"] }}</span></div>
                            <div class="mt-4"><b>{{ __("Datos en ").$current["monthName"] }}</b></span></div>
                            <div class><b>Total ingresos:</b> <span class="text-green">$ {{ $earned }}</span></div>
                            <div class=""><b>Total gastos:</b> <span class="text-red">${{ $bill }}</span></div>
                            <div><b>Rendimiento:</b> {{ $difference }}</div>
                        </div>
                    </div>
                    <div class="flex sm:w-1/1 md:w-1/2 p-6 text-gray-900 justify-center">
                        <div>
                            <canvas id="lineChart" style="height: 300px"></canvas>
                        </div>
                        <div>
                            <canvas id="pieChart" style="height: 100px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="w-full flex bg-white overflow-hidden shadow-sm  justify-end">
                    <div class="p-6 text-bold">
                        <x-primary-button 
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'generate-values')">
                            {{ __('Generar nuevos valores') }}
                        </x-primary-button>
                    </div>
                </div>
            
            </div>
        </div>
    </div>

    <x-modal name="change-month" focusable>
        <form method="POST" action="{{ route('calculation.change') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Cambiar mes de consulta') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="up-type" :value="__('Mes de consulta')" />
                <x-input-select 
                    class="mt-1 block w-full"
                    name="month"
                    :options=$months
                 />
                <x-input-error :messages="$errors->updateTransaction->get('type')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Consultar') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="generate-values" focusable>
        <form method="post" action="{{ route('calculation.generate') }}" class="p-6">
            @csrf

            <input type="hidden" name="month" value="{{ (int) $current["month"] }}">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Generar registros para el mes seleccionado?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Esto generará 10 registros aleatorios con información aleatoria para el mes de comparación.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Generar') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>











    @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const ctx = document.getElementById('lineChart');

        new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach (range(1,$total) as $r)
                    {!! "'".__("")."'," !!}
                 @endforeach
            ],
            datasets: [
                {
                    label: ['Flujo de ganancias en {{ $current["monthName"] }}'],
                    data: [
                        @foreach ($current["flow"] as $f)
                            {!! __($f)."," !!}
                        @endforeach
                    ],
                    borderWidth: 1
                },
                {
                    label: ['Flujo de ganancias en {{ $comparative["monthName"] }}'],
                    data: [
                        @foreach ($comparative["flow"] as $f)
                            {!! __($f)."," !!}
                        @endforeach
                    ],
                    borderWidth: 1
                },
                
            ]
        },
        options: {
            responsive: true,
            plugins: {
            title: {
                display: false,
            },
            },
        }});

        new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: [ 'Ingresos', 'Gastos' ],
            datasets: [
                {
                    data: [ {{ $current["incomes"] }} , {{ $current["bills"] }} ],
                    borderWidth: 1
                },
                {
                    data: [ {{ $comparative["incomes"] }} , {{ $comparative["bills"] }} ],
                    borderWidth: 1
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
            title: {
                display: false,
            },
            },
        }});




    </script>

    @endpush

</x-app-layout>