<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\GetConversionAmount;
use App\GetConversionAmountResponse;


class TestController extends Controller
{
    //
    protected $soapWrapper;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapWrapper
     */
    public function __construct(SoapWrapper $soapWrapper)
    {
      $this->soapWrapper = $soapWrapper;
    }

    public function show()
  {
    //   return phpinfo();
    libxml_disable_entity_loader(false);
    $this->soapWrapper->add('Currency', function ($service) {
      $service
        ->wsdl('http://currencyconverter.kowabunga.net/converter.asmx?wsdl')
        ->trace(true)
        ->options([
            'user_agent' => 'PHPSoapClient',      // Add this as options
        ])
        ->classmap([
          GetConversionAmount::class,
          GetConversionAmountResponse::class,
        ]);
    });

    // Without classmap
    $response = $this->soapWrapper->call('Currency.GetConversionAmount', [
      'CurrencyFrom' => 'USD',
      'CurrencyTo'   => 'EUR',
      'RateDate'     => '2014-06-05',
      'Amount'       => '1000',
    ]);

    var_dump($response);

    // With classmap
    $response = $this->soapWrapper->call('Currency.GetConversionAmount', [
      new GetConversionAmount('USD', 'EUR', '2014-06-05', '1000')
    ]);

    dd($response);
    //return json_encode($response->getAmount());
    return var_dump($response);
   // exit;
  }

}
