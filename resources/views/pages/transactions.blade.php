

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="min-height: 500px;">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-wrap items-center justify-between -m-2 mb-4">
                        <div class="w-full md:w-1/2 p-2">
                          <p class="font-semibold text-xl text-coolGray-800">Todas las transacciones</p>
                          <p class="font-medium text-sm text-coolGray-500">{{ count($list) }} @if(count($list) > 0) transacciones @else transaccion @endif</p>
                        </div>
                        <div class="md:w-1/2 p-2 justify-end">
                            <x-primary-button 
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'create-transaction')">
                                {{ __('Crear transacción') }}
                            </x-primary-button>
                        </div>
                    </div>
                        
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                          <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                              <table class="w-full min-w-full text-left text-sm font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                  <tr>
                                    <th scope="col" class="px-6 py-4" style="max-width: 50px;">#</th>
                                    <th scope="col" class="px-6 py-4">Detalle</th>
                                    <th scope="col" class="px-6 py-4">Tipo</th>
                                    <th scope="col" class="px-6 py-4">Monto</th>
                                    <th scope="col" class="px-6 py-4">Fecha</th>
                                    <th scope="col" class="px-6 py-4">Accion</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $transaction)
                                        <x-table-row
                                            :id="$transaction->id"
                                            :type="$transaction->type"
                                            :detail="$transaction->detail"
                                            :amount="$transaction->amount"
                                            :updated="$transaction->updated_at->format('Y-m-d h:i:s')"
                                        >
                                        </x-table-row>
                                    @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>


                </div>
            </div>
        </div>
    </div>

    {{ $errors->createTransaction }}

    <x-modal name="create-transaction" :show="$errors->createTransaction->isNotEmpty()" focusable>
        <form method="post" action="{{ route('transaction.create') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Crear transaccion') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="detail" :value="__('Detalle')"/>
                <x-text-input 
                    id="detail" 
                    name="detail" 
                    type="text" 
                    class="mt-1 block w-full" 
                    :value="old('detail')"
                 />
                <x-input-error :messages="$errors->createTransaction->get('detail')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="amount" :value="__('Cantidad')" />
                <x-text-input 
                    id="amount" 
                    name="amount" 
                    type="number" 
                    class="mt-1 block w-full" 
                    :value="old('amount')"
                 />
                <x-input-error :messages="$errors->createTransaction->get('amount')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="type" :value="__('Tipo de transacción')" />
                <x-input-select 
                    id="type" 
                    name="type" 
                    class="mt-1 block w-full"
                    :options="$options"
                 />
                <x-input-error :messages="$errors->createTransaction->get('type')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Crear') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</x-app-layout>