<x-modal name="convert-currency" focusable>
    <div class="p-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
            Convert Currency
        </h2>
        <div class="p-4">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                Convert to GBP (&#163;) from another currency
            </h3>
            <div class="flex flex-col items-center">
                <div class="flex">
                    <div class="py-1 ml-3">
                        <x-input-label for="Convert_From_Currency" :value="__('From')"/>
                        <select id="Convert_From_Currency" name="Convert_From_Currency"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                            @foreach($ExchangeRates as $ExchangeRate)
                                <option>{{$ExchangeRate->currencyCode}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="py-1 ml-3">
                        <x-input-label for="Convert_From_Amount" :value="__('Amount')"/>
                        <x-text-input id="Convert_From_Amount" name="Convert_From_Amount" type="text" class="mt-1 block w-full"
                                      :value="old('Convert_From_Amount')"/>
                    </div>
                </div>
                <div class="py-1">
                    <x-input-label :value="__('Result:')"/>
                    <span id="Convert_From_Result_Amount" class="dark:text-gray-300">&nbsp;</span>&nbsp;<span
                        id="Convert_From_Result_Currency" class="dark:text-gray-300">GBP</span>
                </div>
            </div>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                Convert from GBP (&#163;) to another currency
            </h3>
            <div class="flex flex-col items-center">
                <div class="flex">
                    <div class="py-1 ml-3">
                        <x-input-label for="Convert_To_Amount" :value="__('Amount (GBP)')"/>
                        <x-text-input id="Convert_To_Amount" name="Convert_To_Amount" type="text" class="mt-1 block w-full"
                                      :value="old('Convert_To_Amount')"/>
                    </div>
                    <div class="py-1 ml-3">
                        <x-input-label for="Convert_To_Currency" :value="__('To')"/>
                        <select id="Convert_To_Currency" name="Convert_To_Currency"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                            @foreach($ExchangeRates as $ExchangeRate)
                                <option>{{$ExchangeRate->currencyCode}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-1">
                    <x-input-label :value="__('Result:')"/>
                    <span id="Convert_To_Result_Amount" class="dark:text-gray-300">&nbsp;</span>&nbsp;<span
                        id="Convert_To_Result_Currency" class="dark:text-gray-300">&nbsp;</span>
                </div>
            </div>
        </div>
    </div>
</x-modal>

<script>
    $(document).ready(function () {
        ConvertSys.Setup();
    });

    const Rates = {
        @foreach($ExchangeRates as $ExchangeRate)
            {{$ExchangeRate->currencyCode}} : {{$ExchangeRate->rateNew}},
        @endforeach
    }

    const ConvertSys = {
        _ : {
            From : {
                AmountEl : document.getElementById("Convert_From_Amount"),
                CurrencyEl : document.getElementById("Convert_From_Currency"),
                ResultAmountEl : document.getElementById("Convert_From_Result_Amount"),
            },
            To : {
                AmountEl : document.getElementById("Convert_To_Amount"),
                CurrencyEl : document.getElementById("Convert_To_Currency"),
                ResultAmountEl : document.getElementById("Convert_To_Result_Amount"),
                ResultCurrencyEl : document.getElementById("Convert_To_Result_Currency"),
            }
        },

        Setup() {
            ConvertSys.update("From");
            $(ConvertSys._.From.AmountEl).on("input propertychange paste", function (e) {
                ConvertSys.update("From");
            });
            $(ConvertSys._.From.CurrencyEl).on("change", function (e) {
                ConvertSys.update("From");
            });

            ConvertSys.update("To");
            $(ConvertSys._.To.AmountEl).on("input propertychange paste", function (e) {
                ConvertSys.update("To");
            });
            $(ConvertSys._.To.CurrencyEl).on("change", function (e) {
                ConvertSys.update("To");
            });
        },
        update(mode) {
            if(mode === "To") {
                $(ConvertSys._[mode].ResultCurrencyEl).text(ConvertSys._[mode].CurrencyEl.value);
            }
            $(ConvertSys._[mode].ResultAmountEl).text(ConvertSys._CalculateResult(mode))
        },

        _CalculateResult(mode) {
            let rate = Rates[ConvertSys._[mode].CurrencyEl.value];

            let amount

            if(mode === "From") {
                amount = ConvertSys._[mode].AmountEl.value / rate
            } else {
                amount = ConvertSys._[mode].AmountEl.value * rate
            }
            return amount;
        }
    }
</script>
