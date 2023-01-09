<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$html = '<h1>' . $expense->category_name . '</h1>';

if (!empty($expense->expense_name)) {
    $html .= '<h3>' . $expense->expense_name . '</h3>';
}

$html .= '<p>' . _l('expense_amount') . ' <strong>' . app_format_money($expense->amount, $expense->currency_data) . '</strong>';

if ($expense->paymentmode != '0' && !empty($expense->paymentmode)) {
    $html .= '<br />' . _l('expense_paid_via', $expense->payment_mode_name);
}

$html .= '</p>';

if ($expense->tax != 0 || $expense->tax2 != 0) {
    $html .= '<p>';
    if ($expense->tax != 0) {
        $html .= '<strong>' . _l('tax_1') . ':</strong> ' . $expense->taxrate . '% (' . $expense->tax_name . ')';
        $total = $expense->amount;
        $total += ($total / 100 * $expense->taxrate);
    }
    if ($expense->tax2 != 0) {
        $html .= '<br /><strong>' . _l('tax_2') . ':</strong> ' . $expense->taxrate2 . '% (' . $expense->tax_name2 . ')';
        $total += ($expense->amount / 100 * $expense->taxrate2);
    }

    $html .= '</p>';
    $html .= '<p><strong>' . _l('total_with_tax') . ': ' . app_format_money($total, $expense->currency_data) . '</strong></p>';
}

$html .= '<p><strong>' . _l('expense_date') . '</strong> ' . _d($expense->date) . '</p>';

if ($expense->billable == 1) {
    if ($expense->invoiceid == null) {
        $html .= '<p><strong>' . _l('invoice') . ':</strong> ' . _l('expense_invoice_not_created');
    } else {
        $html .= '<p><strong>' . _l('invoice') . ':</strong> ' . format_invoice_number($invoice->id);
        if ($invoice->status == 2) {
            $html .= '<br />' . _l('expense_billed');
        } else {
            $html .= '<br />' . _l('expense_not_billed');
        }
        $html .= '</p>';
    }
}

if (!empty($expense->reference_no)) {
    $html .= '<p><strong>' . _l('expense_ref_noe') . '</strong> ' . $expense->reference_no . '</p>';
}

if ($expense->clientid != 0) {
    $html .= '<p><strong>' . _l('expense_customer') . '</strong> ' . $expense->company . '</p>';
}

if ($expense->project_id != 0) {
    $html .= '<p><strong>' . _l('project') . ':</strong> ' . $expense->project_data->name . '</p>';
}

if (!empty($expense->note)) {
    $html .= '<p><strong>' . _l('expense_note') . '</strong> ' . $expense->note . '</p>';
}

$custom_fields = get_custom_fields('expenses');

foreach ($custom_fields as $field) {
    $value = get_custom_field_value($expense->expenseid, $field['id'], 'expenses');

    if ($value == '') {
        continue;
    }

    $html .= '<p><strong>' . $field['name'] . ':</strong> ' . $value . '</p>';
}

$pdf->writeHTML($html, true, false, false, false, '');
