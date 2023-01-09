<?php

namespace app\services;

use Carbon\CarbonInterval;
use CI_Controller;
use InvalidArgumentException;

class TicketsReportByStaff
{
    /**
     * @var CI_Controller|object
     */
    private $CI;

    /** @var string */
    private string $modeWhere;

    /** @var array<int, object> */
    private array $result;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * @param $mode string
     * @return array<int, object>
     */
    public function filterBy($mode)
    {
        $this->setModeWhere($mode);
        $this->query();
        $this->formatAverageReplyTime();
        return $this->result;
    }

    /**
     * @param  string  $mode
     * @return void
     */
    private function setModeWhere($mode)
    {
        $carbon = \Carbon\Carbon::now();
        switch ($mode) {
            case 'this_week':
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfWeek() . '" and "' . $carbon->endOfWeek() . '"';
                break;
            case 'last_week':
                $carbon->subWeek();
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfWeek() . '" and "' . $carbon->endOfWeek() . '"';
                break;
            case 'this_month':
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfMonth() . '" and "' . $carbon->endOfMonth() . '"';
                break;
            case 'last_month':
                $carbon->subMonth();
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfMonth() . '" and "' . $carbon->endOfMonth() . '"';
                break;
            case 'this_year':
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfYear() . '" and "' . $carbon->endOfYear() . '"';
                break;
            case 'last_year':
                $carbon->subYear();
                $this->modeWhere = 'and ' . db_prefix() . 'tickets.date between "' . $carbon->startOfYear() . '" and "' . $carbon->endOfYear() . '"';
                break;
            default:
                throw new InvalidArgumentException("Invalid Mode Provided");
        }
    }

    private function query()
    {
        $this->result = $this->CI->db
            ->select(implode(',', [
                    'staffid',
                    'firstname',
                    'lastname',
                    '(SELECT count(ticketid) from ' . db_prefix() . 'tickets where assigned = staffid ' . $this->modeWhere . ') as total_assigned',
                    '(SELECT count(ticketid) from ' . db_prefix() . 'tickets where assigned = staffid and status = 1 ' . $this->modeWhere . ') as total_open_tickets',
                    '(SELECT count(ticketid) from ' . db_prefix() . 'tickets where assigned = staffid and status = 5 ' . $this->modeWhere . ') as total_closed_tickets',
                    '(SELECT count(ticketid) from ' . db_prefix() . 'ticket_replies where ' . db_prefix() . 'ticket_replies.admin = staffid ' . str_replace('tickets',
                        'ticket_replies', $this->modeWhere) . ') as total_replies',
                ])
            )
            ->get('staff')
            ->result();
    }

    public function formatAverageReplyTime()
    {
        $this->result = collect($this->result)->map(function ($staffReport) {
            $staffReport->average_reply_time = $this->CI->db->select('(SELECT avg(response_seconds) FROM (SELECT time_to_sec(timediff(min(r.date), t.date)) AS response_seconds FROM ' . db_prefix() . 'tickets t JOIN ' . db_prefix() . 'ticket_replies r  ON t.ticketid = r.ticketid WHERE r.admin != 0 AND t.assigned = ' . $staffReport->staffid . ' ' . str_replace(db_prefix() . 'tickets', 't', $this->modeWhere) . ' GROUP BY t.ticketid) AS r) as average_reply_time')
                ->get('staff')->row()->average_reply_time;

            if ($staffReport->average_reply_time === null || $staffReport->average_reply_time < 60) {
                $staffReport->average_reply_time = '-';
            } else {
                $period = CarbonInterval::seconds($staffReport->average_reply_time);
                if ($period->totalHours < 1) {
                    $staffReport->average_reply_time = (int) $period->totalMinutes . ' ' . _l('minutes');
                } elseif ($period->totalDays <= 4) {
                    $staffReport->average_reply_time = (int) $period->totalHours . ' ' . _l('hours');
                } else {
                    $staffReport->average_reply_time = (int) $period->totalDays . ' ' . _l('days');
                }
            }
            return $staffReport;
        })->toArray();
    }
}
