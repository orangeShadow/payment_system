<?php
declare(strict_types=1);

namespace App\Reports;

use Illuminate\Http\Request;

class HtmlReport extends AbstractReport
{
    /**
     * @param Request $request
     *
     * @return
     */
    public function toResponse($request)
    {
        return view('report', array('user' => $this->user, 'payments' => $this->payments, 'total' => $this->total));
    }
}
