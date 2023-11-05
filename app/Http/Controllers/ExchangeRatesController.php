<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ExchangeRate;
use App\Models\ExchangeRateBk;

class ExchangeRatesController extends Controller
{
    public function view(): View
    {
        $ExchangeRates = ExchangeRate::all();
        return view('exchangeRates.view',compact("ExchangeRates"));
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            if ($request->hasFile('XML_File')) {
                $xml = file_get_contents($request->file('XML_File')->getRealPath());
            } else {
                $xml = file_get_contents($request->XML_URL);
            }
            $xml_object = simplexml_load_string($xml);
            foreach ( (array) $xml_object as $index => $node ) {
                $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
            }
            $xml_array = $out["exchangeRate"];
        } catch (\Exception $exception) {
            return redirect()->route("exchangeRates.view")->with('error', 'Error Updating, Could not convert XML data.');
        }

        try {
            ExchangeRateBk::truncate();
            ExchangeRate::query()
                ->each(function ($oldExchangeRate) {
                    $newExchangeRate = $oldExchangeRate->replicate();
                    $newExchangeRate->setTable('exchange_rates_bk');
                    $newExchangeRate->save();
                });
            ExchangeRate::truncate();

            foreach ($xml_array as $xml_value){
                $ExchangeRate = new ExchangeRate;
                $ExchangeRate->countryName = $xml_value->countryName;
                $ExchangeRate->countryCode = $xml_value->countryCode;
                $ExchangeRate->currencyName = $xml_value->currencyName;
                $ExchangeRate->currencyCode = $xml_value->currencyCode;
                $ExchangeRate->rateNew = $xml_value->rateNew;

                $ExchangeRate->save();
            }
        } catch (\Exception $exception) {
            ExchangeRateBk::query()
                ->each(function ($oldExchangeRate) {
                    $newExchangeRate = $oldExchangeRate->replicate();
                    $newExchangeRate->setTable('exchange_rates');
                    $newExchangeRate->save();
                });
            return redirect()->route("exchangeRates.view")->with('error', 'Error Updating, Could not save to database.');
        }

        return redirect()->route("exchangeRates.view")->with('success', 'Updated with provided XML data');
    }
}
