<?php

namespace app\modules\exports\services;

use CI_Controller;
use CI_DB_mysqli_driver;

defined('BASEPATH') or exit('No direct script access allowed');

@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', 360);


abstract class CSVExport
{
    protected CI_Controller $ci;

    private ?string $startDate;

    private ?string $stopDate;

    private string $delimiter = ',';

    private string $newLine = "\r\n";

    private string $enclosure = '"';

    protected array $customFields = [];

    protected int $batchSize;

    public function __construct(?string $fromDate, ?string $toDate, ?string $customFieldFeatureTo = null)
    {
        $this->ci        = &get_instance();
        $this->startDate = $fromDate;
        $this->stopDate  = $toDate;
        $this->batchSize = hooks()->apply_filters('csv_export_batch_size', 100);

        // Fix for big queries. Some hosting have max_join_limit
        @$this->ci->db->query('SET SQL_BIG_SELECTS=1');

        if ($customFieldFeatureTo) {
            $this->customFields = get_custom_fields($customFieldFeatureTo);
        }
    }

    public function getCSVData()
    {
        $limit       = $this->batchSize;
        $offset      = 0;
        $queryResult = $this->queryData()->get('', $limit, $offset);
        $totalRows   = $queryResult->num_rows();


        $delim     = $this->getDelimiter();
        $newline   = $this->getNewLine();
        $enclosure = $this->getEnclosure();
        $result    = '';

        // First generate the headings from the table column names
        foreach ($queryResult->list_fields() as $name) {
            if (!in_array($name, $this->excludedFields())) {
                $result .= $enclosure . str_replace($enclosure, $enclosure . $enclosure, $this->formatHeading($name)) . $enclosure . $delim;
            }
        }

        $result = substr($result, 0, -strlen($delim)) . $newline;

        while ($totalRows === $limit || $offset === 0 || ($offset >= 0 && $totalRows !== 0 && $totalRows < $limit)) {
            {
                // Next blast through the result array and build result the rows
                while ($row = $queryResult->unbuffered_row('array')) {
                    $line = [];
                    foreach ($row as $name => $value) {
                        if (!in_array($name, $this->excludedFields())) {
                            $line[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $this->formatRow($name, $value, $row)) . $enclosure;
                        }
                    }
                    $result .= implode($delim, $line) . $newline;
                }

                $offset += $limit;
                $queryResult = $this->queryData()->get('', $limit, $offset);
                $totalRows   = $queryResult->num_rows();
            }
        }

        return $result;
    }

    abstract public function queryData(): CI_DB_mysqli_driver;

    public function excludedFields() : array
    {
        return [];
    }

    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    public function getNewLine(): string
    {
        return $this->newLine;
    }

    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    protected function formatHeading(string $header): string
    {
        return str_replace('_', ' ', ucfirst($header));
    }

    /**
     * @param  string  $name
     * @param  string|int|null  $value
     * @param  array  $row
     * @return string|int|null
     */
    protected function formatRow(string $name, $value, array $row)
    {
        return $value;
    }

    protected function applyDateFilter(string $column): void
    {
        if ($this->startDate && $this->stopDate) {
            $this->ci->db->where($column . ' BETWEEN "' . $this->getStartDate() . '" AND "' . $this->getStopDate() . '"');
        }
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getStopDate(): ?string
    {
        return $this->stopDate;
    }

    protected function selectCustomFields(string $joinColumn): void
    {
        $custom_fields_select = '';
        foreach ($this->customFields as $key => $field) {
            $custom_fields_select .= ',ctable_' . $key . '.value as ' . slug_it($field['name'], ['separator' => '_']);
            $this->ci->db->join(
                db_prefix() . 'customfieldsvalues as ctable_' . $key,
                $joinColumn . ' = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id'],
                'left'
            );
        }
        $this->ci->db->select($custom_fields_select);
    }
}
