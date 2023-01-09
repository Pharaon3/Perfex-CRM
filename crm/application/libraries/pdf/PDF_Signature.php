<?php

defined('BASEPATH') or exit('No direct script access allowed');

trait PDF_Signature
{
    public function processSignature()
    {
        return $this->process_signature();
    }

    public function process_signature()
    {
        $dimensions       = $this->getPageDimensions();
        $leftColumnExists = false;
        $companySignature = $this->getCompanySignature();

        if ($companySignature) {
            $this->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, _l('authorized_signature_text') . ' ' . $companySignature, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);

            $leftColumnExists = true;
        }

        // Customer signature
        $record = $this->getSignatureableInstance();
        $path   = $this->getSignaturePath();

        if (!empty($path) && file_exists($path)) {
            $signature = _l('document_customer_signature_text');

            if ($this->type() == 'contract') {
                $signature .= '<br /><br /><span style="font-weight:bold;text-align: right;">';
                $signature .= _l('contract_signed_by') . ": {$record->acceptance_firstname} {$record->acceptance_lastname}<br />";
                $signature .= _l('contract_signed_date') . ': ' . _dt($record->acceptance_date) . '<br />';
                $signature .= _l('contract_signed_ip') . ": {$record->acceptance_ip}";
                $signature .= '</span><br />';
            }

            if ($this->type() == 'proposal' || $this->type() == 'estimate') {
                $signature .= '<br /><br /><span style="font-weight:bold;text-align: right;">';
                $signature .= _l('proposal_signed_by') . ": {$record->acceptance_firstname} {$record->acceptance_lastname}<br />";
                $signature .= _l('proposal_signed_date') . ': ' . _dt($record->acceptance_date) . '<br />';
                $signature .= _l('proposal_signed_ip') . ": {$record->acceptance_ip}";
                $signature .= '</span><br />';
            }

            $signature .= str_repeat(
                '<br />',
                hooks()->apply_filters('pdf_signature_break_lines', 1)
            );

            $width = ($dimensions['wk'] / 2) - $dimensions['rm'];

            if (!$leftColumnExists) {
                $width = $dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm']);
                $this->ln(13);
            }

            $hookData = [
                'pdf_instance'       => $this,
                'type'               => $this->type(),
                'signatureCellWidth' => $width,
            ];

            hooks()->do_action('before_customer_pdf_signature', $hookData);

            $imageData = file_get_contents($path);
            $this->MultiCell($width, 0, $signature, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);

            $customerSignatureSize = hooks()->apply_filters('customer_pdf_signature_size', 0);

            $this->Image('@' . $imageData, $this->getX(), $this->getY(), $customerSignatureSize, 0, 'PNG', '', 'R', true, 300, 'R', false, false, 0, true);

            hooks()->do_action('after_customer_pdf_signature', $hookData);
        }
    }

    public function getCompanySignature()
    {
        if (($this->type() == 'invoice' && get_option('show_pdf_signature_invoice') == 1)
        || ($this->type() == 'estimate' && get_option('show_pdf_signature_estimate') == 1)
        || ($this->type() == 'contract' && get_option('show_pdf_signature_contract') == 1)
        || ($this->type() == 'proposal' && get_option('show_pdf_signature_proposal') == 1)
        || ($this->type() == 'credit_note') && get_option('show_pdf_signature_credit_note') == 1) {
            $signatureImage = get_option('signature_image');

            $signaturePath   = FCPATH . 'uploads/company/' . $signatureImage;
            $signatureExists = file_exists($signaturePath);

            $blankSignatureLine = hooks()->apply_filters('blank_signature_line', '_________________________');

            if ($signatureImage != '' && $signatureExists) {
                $blankSignatureLine = '';
            }

            $this->ln(13);

            if ($signatureImage != '' && $signatureExists) {
                $imageData = base64_encode(file_get_contents($signaturePath));
                $blankSignatureLine .= str_repeat('<br />', hooks()->apply_filters('pdf_signature_break_lines', 1)) . '<img src="@' . $imageData . '" / />';
            }

            return $blankSignatureLine;
        }

        return false;
    }

    public function getSignaturePath()
    {
        $instance = $this->getSignatureableInstance();

        if (!$instance) {
            return '';
        }

        $path = get_upload_path_by_type($this->type()) . $instance->id . '/' . $instance->signature;

        return hooks()->apply_filters(
            'pdf_customer_signature_image_path',
            $path,
            $this->type()
        );
    }

    public function getSignatureableInstance()
    {
        if (isset($GLOBALS['estimate_pdf']) && !empty($GLOBALS['estimate_pdf']->signature)) {
            return $GLOBALS['estimate_pdf'];
        } elseif (isset($GLOBALS['proposal_pdf']) && !empty($GLOBALS['proposal_pdf']->signature)) {
            return $GLOBALS['proposal_pdf'];
        } elseif (isset($GLOBALS['contract_pdf']) && !empty($GLOBALS['contract_pdf']->signature)) {
            return $GLOBALS['contract_pdf'];
        }
    }

    public function hasAnySignature()
    {
        return $this->getSignaturePath() || $this->getCompanySignature();
    }
}
