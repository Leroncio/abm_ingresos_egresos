
<tr class="border-b dark:border-neutral-500">
    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $id }}</td>
    <td class="whitespace-nowrap px-6 py-4">{{ $detail }}</td>
    <td class="whitespace-nowrap px-6 py-4">
      @if($type == "1")
        <div class="label bg-green text-white">Ingreso</div>
      @elseif($type == "2")
        <div class="label bg-red text-white">Egresos</div>
      @endif
    </td>
    <td class="whitespace-nowrap px-6 py-4 @if($type == "1") text-green @else text-red @endif">$ {{ $amount }}</td>
    <td class="whitespace-nowrap px-6 py-4">{{ $updated }}</td>
    <td class="whitespace-nowrap px-6 py-4">

      <x-secondary-button
        x-data=""
        :selected=$id
        :detail=$detail
        :amount=$amount
        x-on:click.prevent="$dispatch('open-modal', 'update-transaction')"
      >
        <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
        width="15px" height="15px"  viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
     <path fill="#000000" d="M62.829,16.484L47.513,1.171c-1.562-1.563-4.094-1.563-5.657,0L0,43.031V64h20.973l41.856-41.855C64.392,20.577,64.392,18.05,62.829,16.484z M18,56H8V46l0.172-0.172l10,10L18,56z"/>
     </svg>
      </x-secondary-button>

      <x-danger-button
        x-data=""
        :selected=$id
        x-on:click.prevent="$dispatch('open-modal', 'confirm-data-deletion')"
      >
      <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="15px" height="15px" fill="#ffffff"><path d="M 13 3 A 1.0001 1.0001 0 0 0 11.986328 4 L 6 4 A 1.0001 1.0001 0 1 0 6 6 L 24 6 A 1.0001 1.0001 0 1 0 24 4 L 18.013672 4 A 1.0001 1.0001 0 0 0 17 3 L 13 3 z M 6 8 L 6 24 C 6 25.105 6.895 26 8 26 L 22 26 C 23.105 26 24 25.105 24 24 L 24 8 L 6 8 z"/></svg>
      </x-danger-button>


    </td>
  </tr>