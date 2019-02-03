<?php
declare(strict_types=1);

namespace App\Reports;

use Illuminate\Http\Request;

class XmlReport extends AbstractReport
{
    /**
     * @param Request $request
     *
     * @return
     */
    public function toResponse($request)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $report = $dom->createElement('report');
        $payments = $dom->createElement('payments');

        foreach ($this->payments as $item) {
            $payment = $dom->createElement('payment');
            $payment->setAttribute('purse_from', $item->purse_from ? $item->purseFrom->user->name : '');
            $payment->setAttribute('purse_to', $item->purseTo->user->name);
            $payment->setAttribute('amount_from', $item->purse_from ? $item->amount_from . ' ' . $item->purseFrom->getCurrency() : '0');
            $payment->setAttribute('amount_to', $item->amount_to . ' ' . $item->purseTo->getCurrency());
            $payment->setAttribute('created_at', $item->created_at->format('Y-m-d H:i:s'));
            $payments->appendChild($payment);
        }

        $report->appendChild($payments);

        if (count($this->total) > 0) {
            $total = $this->total[0];
            $totalEl = $dom->createElement('total');
            $totalEl->setAttribute('amountFrom', $total->totalFrom . $this->user->purse->getCurrency());
            $totalEl->setAttribute('amountFromUsd', $total->totalFromUsd . '$');
            $totalEl->setAttribute('amountTo', $total->totalTo . $this->user->purse->getCurrency());
            $totalEl->setAttribute('amountToUsd', $total->totalToUsd . '$');
            $report->appendChild($totalEl);
        }

        $dom->appendChild($report);

        return response($dom->saveXML(), 200, ['Content-Type' => 'application/xml', 'charset' => 'utf-8']);
    }
}
