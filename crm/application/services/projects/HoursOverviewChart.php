<?php

namespace app\services\projects;

use DateTime;

class HoursOverviewChart
{
    protected $id;

    protected $type;

    protected $ci;

    protected $canCreate = false;

    protected $timesheetsType = null;

    public function __construct($id, $type = 'this_week')
    {
        $this->id        = $id;
        $this->type      = $type;
        $this->ci        = &get_instance();
        $this->canCreate = has_permission('projects', '', 'create');
        // If don't have permission for projects create show only bileld time
        $this->timesheetsType = $this->determineTimesheetsType($id);
    }

    public function get()
    {
        $chart = $this->createChartDataAndDatasets();

        $chart['data']['labels'] = $this->isMonthType() ?
        $this->determineLabelsWhenMonthType() :
        $this->determineLabelsWhenWeekType();

        $totalLoggedTimeInWeeks = [];
        $weeks                  = $this->getWeeksSplitWhenTypeIsMonth();

        $loop_break = ($this->timesheetsType == 'billable_unbilled') ? 2 : 1;

        for ($i = 0; $i < $loop_break; $i++) {
            $totalLoggedTimeInWeeks = [];
            // Store the weeks in new variable for each loop to prevent duplicating
            $weeksSplitByMonths = $weeks;
            $unbilled           = $i === 1;
            $color              = $this->timesheetsType != 'total_logged_time_only' && $unbilled ? '252, 45, 66' : '3, 169, 244';
            $timesheets         = $this->getChartTimesheets($unbilled);

            foreach ($timesheets as $t) {
                $total_logged_time = $t['end_time'] == null ? time() - $t['start_time'] : $t['end_time'] - $t['start_time'];

                if ($this->isWeekType()) {
                    $weekday = date('N', $t['start_time']);
                    if (!isset($totalLoggedTimeInWeeks[$weekday])) {
                        $totalLoggedTimeInWeeks[$weekday] = 0;
                    }

                    $totalLoggedTimeInWeeks[$weekday] += $total_logged_time;
                } else {
                    // months - this and last
                    $w = 1;
                    foreach ($weeksSplitByMonths as $weekDays) {
                        if (!isset($weeksSplitByMonths[$w]['total'])) {
                            $weeksSplitByMonths[$w]['total'] = 0;
                        }

                        if (in_array(date('Y-m-d', $t['start_time']), $weekDays)) {
                            $weeksSplitByMonths[$w]['total'] += $total_logged_time;
                        }

                        $w++;
                    }
                }
            }

            if ($this->isWeekType()) {
                ksort($totalLoggedTimeInWeeks);

                for ($w = 1; $w <= 7; $w++) {
                    $total_logged_time = isset($totalLoggedTimeInWeeks[$w]) ? $totalLoggedTimeInWeeks[$w] : 0;
                    array_push($chart['data']['datasets'][$i]['data'], sec2qty($total_logged_time));
                    array_push($chart['data']['datasets'][$i]['backgroundColor'], 'rgba(' . $color . ',0.8)');
                    array_push($chart['data']['datasets'][$i]['borderColor'], 'rgba(' . $color . ',1)');
                }
            } else {
                // loop over $weeksSplitByMonths because the unbilled is shown twice because we auto increment twice
                // months - this and last
                foreach ($weeksSplitByMonths as $week) {
                    $total = 0;
                    if (isset($week['total'])) {
                        $total = $week['total'];
                    }
                    $total_logged_time = $total;
                    array_push($chart['data']['datasets'][$i]['data'], sec2qty($total_logged_time));
                    array_push($chart['data']['datasets'][$i]['backgroundColor'], 'rgba(' . $color . ',0.8)');
                    array_push($chart['data']['datasets'][$i]['borderColor'], 'rgba(' . $color . ',1)');
                }
            }
        }

        return $chart;
    }

    protected function getChartTimesheets($unbilled = false)
    {
        $betweenDates = $this->getBetweenDates();

        $where = 'task_id IN (SELECT id FROM ' . db_prefix() . 'tasks WHERE rel_type = "project" AND rel_id = "' . $this->ci->db->escape_str($this->id) . '"';

        if ($this->timesheetsType != 'total_logged_time_only') {
            $where .= ' AND billable=1';
            if ($unbilled) {
                $where .= ' AND billed = 0';
            }
        }

        $where .= ')';

        $this->ci->db->where('start_time BETWEEN ' . strtotime($betweenDates[0]) . ' AND ' . strtotime($betweenDates[1]));
        $this->ci->db->where($where);

        if (!$this->canCreate) {
            $this->ci->db->where('staff_id', get_staff_user_id());
        }

        return $this->ci->db->get('taskstimers')->result_array();
    }

    protected function isMonthType()
    {
        return ($this->type === 'this_month' || $this->type === 'last_month');
    }

    protected function isWeekType()
    {
        return ($this->type === 'this_week' || $this->type === 'last_week');
    }

    protected function determineLabelsWhenMonthType()
    {
        $weeks       = $this->getWeeksSplitWhenTypeIsMonth();
        $total_weeks = count($weeks);
        $labels      = [];

        for ($i = 1; $i <= $total_weeks; $i++) {
            $labels[] = split_weeks_chart_label($weeks, $i);
        }

        return $labels;
    }

    protected function determineLabelsWhenWeekType()
    {
        $labels  = get_weekdays();
        $weekDay = date('w', strtotime(date('Y-m-d H:i:s')));

        $i = 0;
        foreach (get_weekdays_original() as $day) {
            if ($weekDay != '0') {
                $labels[$i] = date('d', strtotime($day . ' ' . str_replace('_', ' ', $this->type))) . ' - ' . $labels[$i];
            } else {
                if ($this->type == 'this_week') {
                    $strtotime = 'last ' . $day;
                    if ($day == 'Sunday') {
                        $strtotime = 'sunday this week';
                    }
                    $labels[$i] = date('d', strtotime($strtotime)) . ' - ' . $labels[$i];
                } else {
                    $strtotime  = $day . ' last week';
                    $labels[$i] = date('d', strtotime($strtotime)) . ' - ' . $labels[$i];
                }
            }
            $i++;
        }

        return $labels;
    }

    protected function getBetweenDates()
    {
        if ($this->type == 'this_month') {
            // Begin this month, end this month
            return [date('Y-m-01'), date('Y-m-t 23:59:59')];
        } elseif ($this->type == 'last_month') {
            // Begin last month, end last month
            return [date('Y-m-01', strtotime('-1 MONTH')), date('Y-m-t 23:59:59', strtotime('-1 MONTH'))];
        } elseif ($this->type == 'last_week') {
            // Begin last week, end last week
            return [date('Y-m-d', strtotime('monday last week')), date('Y-m-d 23:59:59', strtotime('sunday last week'))];
        }
        // this_week
        // Begin this week, end this week
        return [date('Y-m-d', strtotime('monday this week')), date('Y-m-d 23:59:59', strtotime('sunday this week'))];
    }

    protected function getWeeksSplitWhenTypeIsMonth()
    {
        $betweenDates = $this->getBetweenDates();

        return get_weekdays_between_dates(
            new DateTime(date('Y-m-d', strtotime($betweenDates[0]))),
            new DateTime(date('Y-m-d', strtotime($betweenDates[1])))
        );
    }

    protected function createChartDataAndDatasets()
    {
        $chart['data']             = [];
        $chart['data']['labels']   = [];
        $chart['data']['datasets'] = [];

        $chart['data']['datasets'][] = [
            'label' => ($this->timesheetsType == 'billable_unbilled' ?
                str_replace(':', '', _l('project_overview_billable_hours')) :
                str_replace(':', '', _l('project_overview_logged_hours'))),
            'data'            => [],
            'backgroundColor' => [],
            'borderColor'     => [],
            'borderWidth'     => 1,
        ];

        if ($this->timesheetsType == 'billable_unbilled') {
            $chart['data']['datasets'][] = [
                'label'           => str_replace(':', '', _l('project_overview_unbilled_hours')),
                'data'            => [],
                'backgroundColor' => [],
                'borderColor'     => [],
                'borderWidth'     => 1,
            ];
        }

        return $chart;
    }

    protected function determineTimesheetsType($id)
    {
        $billing_type = get_project_billing_type($id);

        if (!$this->canCreate) {
            return 'total_logged_time_only';
        }

        if ($billing_type == 2 || $billing_type == 3) {
            return 'billable_unbilled';
        }

        return 'total_logged_time_only';
    }
}
