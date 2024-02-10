<?php

namespace App\Console\Commands;

use App\Exports\ProductsByDayExport;
use App\Mail\ReportProductsCreatedByDay as MailReportProductsCreatedByDay;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportProductsCreatedByDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:report-products-created-by-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Report of products created per day';

    /**
     * Execute the console command.
     */

    const ROUTE_REPORT = 'reports/products_by_day.xlsx';

    public function handle()
    {
        Excel::store(new ProductsByDayExport, self::ROUTE_REPORT);

        $super_user = User::Where('is_superadmin', '=', true)->first();

        if (Storage::disk('local')->exists(self::ROUTE_REPORT)) {
            Mail::to($super_user)->send(new MailReportProductsCreatedByDay(self::ROUTE_REPORT));
        }
    }
}
