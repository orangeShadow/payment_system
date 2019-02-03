<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Currency;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->string('code', 3)->comment('Currency code by ISO 4217 ');
            $table->string('title')->comment('Currency name');

            $table->primary('code');
        });

        $fileCurrenciesPath = __DIR__ . '/../currencies.json';

        if (file_exists($fileCurrenciesPath)) {
            $currencies = json_decode(file_get_contents($fileCurrenciesPath));

            foreach ($currencies as $currency) {
                Currency::create(['code' => $currency->code, 'title' => $currency->name]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
