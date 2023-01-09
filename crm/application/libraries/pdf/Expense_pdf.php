<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Expense_pdf extends App_pdf
{
    protected $expense;

    public function __construct($expense, $tag = '')
    {
        parent::__construct();

        $this->tag     = $tag;
        $this->expense = $expense;
    }

    public function prepare()
    {
        $this->SetTitle($this->expense->category_name);

        $this->set_view_vars([
            'expense' => $this->expense,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'expense';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/admin/expenses/my_expense_pdf.php';
        $actualPath = APPPATH . 'views/admin/expenses/expense_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}
