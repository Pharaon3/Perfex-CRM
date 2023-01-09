<?php

namespace app\services;

class NumToWordIndian
{
    private bool $hasPaisa;
    private string $amount;
    private string $rupees;
    private string $paisa;

    public function __construct(string $amount)
    {
        $this->amount = $amount;
        $this->hasPaisa = false;
        $arr = explode(".", $this->amount);
        $this->rupees = $arr[0];
        if (isset($arr[1]) && ((int) $arr[1]) > 0) {
            if (strlen($arr[1]) > 2) {
                $arr[1] = substr($arr[1], 0, 2);
            }
            $this->hasPaisa = true;
            $this->paisa = $arr[1];
        }
    }

    public function get_words(): string
    {
        $w = "";
        $crore = (int) ($this->rupees / 10000000);
        $this->rupees = $this->rupees % 10000000;
        $w .= $this->single_word($crore, $this->get_word('num_word_crore') . " ");
        $lakh = (int) ($this->rupees / 100000);
        $this->rupees = $this->rupees % 100000;
        $w .= $this->single_word($lakh, $this->get_word('num_word_lakhs') . " ");
        $thousand = (int) ($this->rupees / 1000);
        $this->rupees = $this->rupees % 1000;
        $w .= $this->single_word($thousand, "{$this->get_word('thousand')}  ");
        $hundred = (int) ($this->rupees / 100);
        $w .= $this->single_word($hundred, "{$this->get_word('hundred')} ");
        $ten = $this->rupees % 100;
        $w .= $this->single_word($ten, "");
        $w .= $this->get_word('num_word_INR') . " ";
        if ($this->hasPaisa) {
            if ($this->paisa[0] == "0") {
                $this->paisa = (int) $this->paisa;
            } else {
                if (strlen($this->paisa) == 1) {
                    $this->paisa = $this->paisa * 10;
                }
            }
            $w .= " " . $this->get_word('number_word_and') . " " . $this->single_word($this->paisa, " " . $this->get_word("num_word_paisa"));
        }
        return $w . " " . $this->get_word('number_word_only');
    }

    private function single_word(int $num, string $txt): string
    {
        $str = "";
        $r = (int) ($num / 1000);
        $x = ($num / 100) % 10;
        $y = $num % 100;
        // do hundreds
        if ($x > 0) {
            $str = $this->get_word('num_word_' . $x) . ' ' . $this->get_word('hundred');
            // do ones and tens
            $str .= $this->common_loop_indian($y, ' ' . $this->get_word('number_word_and') . ' ', '');
        } elseif ($r > 0) {
            // do ones and tens
            $str .= $this->common_loop_indian($y, ' ' . $this->get_word('number_word_and') . ' ', '');
        } else {
            // do ones and tens
            $str .= $this->common_loop_indian($y);
        }
        if ($num == 0) {
            $txt = "";
        }
        return $str . " " . $txt;
    }

    private function common_loop_indian(int $val, string $str1 = '', ?string $str2 = ''): string
    {
        $string = '';
        if ($val == 0) {
            $string .= $this->get_word($val);
        } elseif ($val < 100) {
            $string .= $str1 . $this->get_word('num_word_' . $val) . $str2;
        }

        return $string;
    }

    private function get_word(string $string): string
    {
        return _l($string);
    }
}
