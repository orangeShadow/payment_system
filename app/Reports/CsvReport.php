<?php
declare(strict_types=1);


namespace App\Reports;


class CsvReport extends AbstractReport
{


    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $content = ['from', 'to', 'amount_from', 'amount_to', 'created_at'];


        $csvString = $this->str_putcsv($content) . "\r\n";

        foreach ($this->payments as $payment) {
            $item = [
                $payment->purse_from ? $payment->purseFrom->user->name : '',
                $payment->purseTo->user->name,
                $payment->purse_from ? $payment->amount_from . ' ' . $payment->purseFrom->getCurrency() : 0,
                $payment->amount_to . ' ' . $payment->purseTo->getCurrency(),
                $payment->created_at
            ];

            $csvString .= $this->str_putcsv($item) . "\r\n";
        }

        if (count($this->total) > 0) {
            $total = $this->total[0];
            $csvString .= $this->str_putcsv([
                    'Total',
                    '',
                    $total->totalFrom . $this->user->purse->getCurrency() . '(' . $total->totalFromUsd . '$)',
                    $total->totalTo . $this->user->purse->getCurrency() . '(' . $total->totalToUsd . '$)',
                    ''
                ]) . "\r\n";
        }


        return response(
            $csvString,
            200,
            ['Content-type' => 'text/csv']
        );
    }

    function str_putcsv($input, $delimiter = ',', $enclosure = '"')
    {
        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $input, $delimiter, $enclosure);
        $len = ftell($fp) + 1;
        rewind($fp);
        $data = fread($fp, $len);
        fclose($fp);

        return rtrim($data, "\n");
    }
}
