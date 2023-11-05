<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Exchange Rates
        </h2>
        <small class="text-sm text-gray-400 dark:text-gray-600">Last Updated : {{$ExchangeRates->first()->updated_at}}</small>
    </x-slot>

    @if (Route::has('exchangeRates.update'))
        <div class="pt-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    @if (session('success'))
                        <p class="text-sm text-green-600 dark:text-green-400"
                           x-data="{ show: true }"
                           x-show="show"
                           x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                        >{{session('success')}}</p>
                    @endif
                    @if (session('error'))
                        <p class="text-sm text-red-600 dark:text-red-400"
                           x-data="{ show: true }"
                           x-show="show"
                           x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                        >{{session('error')}}</p>
                    @endif
                </div>
                <div class="flex justify-end">
                    @if($ExchangeRates && $ExchangeRates->count() > 0)
                    <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'convert-currency')">
                        {{ __('Convert Currency ') }} <i class="fa-solid fa-right-left"></i>
                    </x-secondary-button>
                    @endif
                    <x-secondary-button class="ml-3" x-data="" x-on:click.prevent="$dispatch('open-modal', 'new-data')">
                        {{ __('New Rates ') }} <i class="fa-solid fa-upload"></i>
                    </x-secondary-button>
                </div>
            </div>
        </div>
    @endif

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($ExchangeRates && $ExchangeRates->count() > 0)
                    <table id="ExchangeRates" class="display">
                        <thead>
                            <tr>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                <th>Currency Name</th>
                                <th>Currency Code</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($ExchangeRates as $ExchangeRate)
                            <tr>
                                <td>{{$ExchangeRate->countryName}}</td>
                                <td>{{$ExchangeRate->countryCode}}</td>
                                <td>{{$ExchangeRate->currencyName}}</td>
                                <td>{{$ExchangeRate->currencyCode}}</td>
                                <td>{{$ExchangeRate->rateNew}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                <th>Currency Name</th>
                                <th>Currency Code</th>
                                <th>Rate</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                        <h2 class="text-gray-600 dark:text-gray-200 text-center">No Records, please upload.</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@if (Route::has('exchangeRates.update'))
    @include('exchangeRates.add')
@endif

@include('exchangeRates.convert')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function () {
        new DataTable('#ExchangeRates');
    });
</script>
