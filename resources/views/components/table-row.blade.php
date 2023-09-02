
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

      

    </td>
  </tr>