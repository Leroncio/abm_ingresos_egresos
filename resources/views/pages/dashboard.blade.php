<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 500px;">
                <div class="sm:w-1/1 md:w-1/2 p-6 text-gray-900">
                    <div class="detail-container p-6">
                        <div class="font-semibold uppercase">{{ __("Prueba tecnica para DentiDesk") }}</div>
                        <div class="mt-4"><b>Autor:</b> {{ __("Eduardo Araya") }}</div>
                        <div class=""><b>Fecha entrega:</b> {{ __("03-09-2023") }}</div>
                        <div class="mt-4 font-semibold">{{ __("Informacion de desarrollo") }}</div>
                        <div><b>Version PHP:</b> {{ __("v8.2.0") }}</div>
                        <div><b>Version Laravel:</b> {{ __("v10.21.0") }}</div>
                        <div><b>Version MySql:</b> {{ __("v8.0.31") }}</div>
                        <div class="mt-4 font-semibold">{{ __("Recursos adiconales integrados") }}</div>
                        <div>{{ __("Breeze") }}</div>
                        <div>{{ __("Tailwind CSS") }}</div>
                        <div>{{ __("Chart.js") }}</div>
                    </div>
                </div>
                <div class="sm:w-1/1 md:w-1/2 p-6 text-gray-900 justify-center">
                    <div>
                        <canvas id="myChart" style="height: 400px"></canvas>
                    </div>
                </div>
            </div>

            <div class="w-full flex bg-white overflow-hidden shadow-sm  justify-end">
                <div class="p-6 text-bold">
                    @if($profits < 0)
                        <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
                            {{ __("Perdidas del mes en curso") }} <div class="ml-2 text-red">${{ $profits }}</div>
                        </h2>
                    @else
                        <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
                            {{ __("Ganancias del mes en curso") }} <div class="ml-2 text-green">${{ $profits }}</div>
                        </h2>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach ($list as $transaction)
                    {!! "'".__($transaction->updated_at->format('Y-m-d h:i:s'))."'," !!}
                 @endforeach
            ],
            datasets: [{
            label: ['Flujo de ganancias'],
            data: [
                @foreach ($flow as $f)
                    {!! __($f)."," !!}
                 @endforeach
            ],
            borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
            y: {
                beginAtZero: true
            }
            }
        }
        });
    </script>

    @endpush

</x-app-layout>