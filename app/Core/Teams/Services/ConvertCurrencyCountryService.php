<?php

namespace App\Core\Teams\Services;

use App\Core\Teams\Models\Country;

/**
 * Class ConvertCurrencyCountry
 * @package App\Services
 */
class ConvertCurrencyCountryService
{

    /**
     * @param        $slides
     * @param        $codeCountry
     * @param string $fromCurrency
     * @return mixed
     */
    public function execute($slides, $codeCountry, $fromCurrency = 'USD')
    {
        return $slides->map($this->mapSetConvertJackPot($codeCountry));
    }

    /**
     * @param $codeCountry
     * @return callable
     */
    private function mapSetConvertJackPot($codeCountry): callable
    {
        return function ($slide, $key) use ($codeCountry) {
            $amount   = (float) $slide->jack_pot;
            $exchange = $this->convert($codeCountry, $amount);

            $slide[ 'jackpot_convert_amount' ]        = $exchange[ 'amount' ];
            $slide[ 'jackpot_convert_amount_symbol' ] = $exchange[ 'amountWithSymbol' ];
            $slide[ 'jackpot_convert_symbol' ]        = $exchange[ 'symbol' ];

            return $slide;
        };
    }

    private function convert($codeCountry, $amount, $fromCurrency = 'USD')
    {
        $exchange = Country::query()
            ->where('country_Iso', $codeCountry)
            ->join('countries_info as ci', 'ci.country_id', 'countries.country_id')
            ->join('currency_exchange as ce', 'ce.curr_code_to', 'ci.country_currency')
            ->join('currencies as cu', 'cu.curr_code', 'ce.curr_code_to')
            ->where('ce.curr_code_from', $fromCurrency)
            ->where('ce.active', 1)
            ->orderBy('ce.exch_regdate', 'desc')
            ->firstFromCache([ 'ce.exch_factor', 'cu.curr_symbol' ]);

        if (null === $exchange) {
            return [
                'amount'           => $amount,
                'symbol'           => '$',
                'amountWithSymbol' => $amount . ' $',
            ];
        }

        $finalAmount = $amount * $exchange->exch_factor;

        return [
            'amount'           => $finalAmount,
            'symbol'           => $exchange->curr_symbol,
            'amountWithSymbol' => $finalAmount . ' ' . $exchange->curr_symbol,
        ];
    }

}
