<?php

use app\modules\exports\services\ContactCSVExport;
use app\modules\exports\services\CSVExport;
use app\modules\exports\services\CustomerCSVExport;
use app\modules\exports\services\ExpenseCSVExport;
use app\modules\exports\services\LeadCSVExport;
use app\modules\exports\services\PaymentCSVExport;

class Exports_module
{
    private const FEATURE_CUSTOMER = 'customers';

    private const FEATURE_CONTACTS = 'contacts';

    private const FEATURE_LEADS = 'leads';

    private const FEATURE_EXPENSES = 'expenses';

    private const FEATURE_PAYMENTS = 'payments';

    private CI_Controller $ci;

    private array $features = [];

    public function __construct()
    {
        $this->ci = &get_instance();

        $this->features = hooks()->apply_filters('csv_export_features', [
            self::FEATURE_CUSTOMER => CustomerCSVExport::class,
            self::FEATURE_CONTACTS => ContactCSVExport::class,
            self::FEATURE_LEADS    => LeadCSVExport::class,
            self::FEATURE_EXPENSES => ExpenseCSVExport::class,
            self::FEATURE_PAYMENTS => PaymentCSVExport::class,
        ]);
    }

    public function getFeatures() :array
    {
        return $this->features;
    }

    public function getFeaturesWithNames(): array
    {
        $features = [];
        foreach ($this->getFeatures() as $feature => $className) {
            $features[] = ['feature' => $feature, 'name' => _l($feature)];
        }

        return $features;
    }

    public function export(array $payload, ?string $filename = null)
    {
        $type = $payload['export_type'];

        [$startDate, $stopDate] = $this->getDatesFromPeriod($payload['period'], $payload['start_date'], $payload['stop_date']);

        $filename = $filename ?: $this->getCSVFileName($type, $startDate, $stopDate);

        $csv = $this->getFeatureInstance($type, $startDate, $stopDate)->getCSVData();
        header("Content-Description: {$type} Export");
        header("Content-Disposition: attachment; filename=$filename");
        header('Content-Type: application/csv;');

        $file = fopen('php://output', 'wb');
        fwrite($file, $csv);
        fclose($file);
        exit();
    }

    private function getFeatureInstance(string $feature, ?string $startDate, ?string $stopDate): CSVExport
    {
        $className = $this->getFeatures()[$feature] ?? null;

        if (!$className) {
            throw new RuntimeException("Cannot get class name for the feature: \"$feature\"");
        }

        return new $className($startDate, $stopDate);
    }

    private function getCSVFileName(string $type, ?string $startDate, ?string $stopDate)
    {
        $filename = $type;
        if ($startDate && $stopDate) {
            $filename .= "_{$startDate}_-_{$stopDate}";
        }

        return "$filename.csv";
    }

    private function getDatesFromPeriod($period, ?string $startDate, ?string $stopDate): array
    {
        $now = Carbon\Carbon::now();
        switch ($period) {
            case 'this_month':
                $startDate = $now->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'last_month':
                $now->subMonth();
                $startDate = $now->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'this_year':
                $startDate = $now->startOfYear()->toDateString();
                $stopDate  = $now->endOfYear()->toDateString();

                return [$startDate, $stopDate];
            case 'last_year':
                $now->subYear();
                $startDate = $now->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'last_3_months':
                $startDate = $now->clone()->submonths(2)->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'last_6_months':
                $startDate = $now->clone()->subMonths(5)->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'last_12_months':
                $startDate = $now->clone()->subMonths(11)->startOfMonth()->toDateString();
                $stopDate  = $now->endOfMonth()->toDateString();

                return [$startDate, $stopDate];
            case 'all_time':
                $startDate = null;
                $stopDate  = null;

                return [$startDate, $stopDate];
            default:
                $startDate = to_sql_date($startDate);
                $stopDate  = to_sql_date($stopDate);
        }

        return [$startDate, $stopDate];
    }
}
