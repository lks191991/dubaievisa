<?php

namespace App\Exports;

use App\Models\VoucherActivity;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class TicketOnlyReportExcelExport implements FromView
{
    use Exportable;
	
	protected $records;


    public function __construct($records)
    {
		$this->records = $records;
    }
	
	public function view(): View
    {
		
        return view('exports.voucher-ticket-only-report-export', [
            'records' => $this->records
        ]);
    }
	
    
}
