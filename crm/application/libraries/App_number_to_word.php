<?php

use app\services\NumToWordIndian;

defined('BASEPATH') or exit('No direct script access allowed');

class App_number_to_word
{
    // TODO
    // add to options
    // words without spaces
    // array of possible numbers => words
    private $word_array = [];

    // thousand array,
    private $thousand = [];

    // variables
    private $val;

    private $currency0;

    private $currency1;

    // codeigniter instance
    private $ci;

    private $val_array;

    private $dec_value;

    private $dec_word;

    private $num_value;

    private $num_word;

    private $val_word;

    private $original_val;

    private $language;

    public function __construct($params = [])
    {
        $l        = '';
        $this->ci = & get_instance();
        $lastLangFileLanguage = $this->ci->lang->last_loaded ?: 'english';

        $this->ci->lang->load($lastLangFileLanguage . '_num_words_lang', $lastLangFileLanguage);
          // Load again the custom lang file in case any overwrite for the num_words_lang.php file
        load_custom_lang_file($lastLangFileLanguage);
        $this->language = $lastLangFileLanguage;

        array_push($this->thousand, '');
        array_push($this->thousand, _l('num_word_thousand') . ' ');
        array_push($this->thousand, _l('num_word_million') . ' ');
        array_push($this->thousand, _l('num_word_billion') . ' ');
        array_push($this->thousand, _l('num_word_trillion') . ' ');
        array_push($this->thousand, _l('num_word_zillion') . ' ');
        for ($i = 1; $i < 100; $i++) {
            $this->word_array[$i] = _l('num_word_' . $i);
        }
        for ($i = 100; $i <= 900; $i = $i + 100) {
            $this->word_array[$i] = _l('num_word_' . $i);
        }
    }

    public function convert($in_val = 0, $in_currency0 = '', $in_currency1 = true)
    {
        $this->original_val = $in_val;
        $this->val          = $in_val;

        $this->dec_value = null;
        $this->dec_word  = null;
        $this->val_array = null;
        $this->val_word  = null;
        $this->num_value = null;
        $this->num_word  = null;

        $this->currency0 = _l('num_word_' . mb_strtoupper($in_currency0, 'UTF-8'));

        if (strtolower($in_currency0) == 'inr') {
            $final_val = $this->convert_indian($in_val);
        } else {
            // Currency not found
            if (strpos($this->currency0, 'num_word_') !== false) {
                $this->currency0 = '';
            }
            if ($in_currency1 == false) {
                $this->currency1 = '';
            } else {
                $this->currency1 = _l('num_word_cents');
            }
            // remove commas from comma separated numbers
            $this->val = abs(floatval(str_replace(',', '', $this->val)));
            if ($this->val > 0) {
                // convert to number format
                $this->val = number_format($this->val, '2', ',', ',');
                // split to array of 3(s) digits and 2 digit
                $this->val_array = explode(',', $this->val);
                // separate decimal digit
                $this->dec_value = intval($this->val_array[count($this->val_array) - 1]);
                if ($this->dec_value > 0) {
                    $w_and = _l('number_word_and');
                    $w_and = ($w_and == ' ' ? '' : $w_and .= ' ');
                    // convert decimal part to word;
                    $this->dec_word = $w_and . '' . $this->word_array[$this->dec_value] . ' ' . $this->currency1;
                }
                // loop through all 3(s) digits in VAL array
                $t = 0;
                // initialize the number to word variable
                $this->num_word = '';

                for ($i = count($this->val_array) - 2; $i >= 0; $i--) {
                    // separate each element in VAL array to 1 and 2 digits
                    $this->num_value = intval($this->val_array[$i]);
                    // if VAL = 0 then no word
                    if ($this->num_value == 0) {
                        // e.q. zero dolars and 64 cents
                        if(count($this->val_array) === 2){
                           $this->num_word = _l('num_word_0') . ' ' . $this->num_word;
                       } else {
                          $this->num_word = ' ' . $this->num_word;
                      }
                    }

                    // if 0 < VAL < 100 or 2 digits
                    elseif (strlen($this->num_value . '') <= 2) {
                        $this->num_word = $this->word_array[$this->num_value] . ' ' . $this->thousand[$t] . $this->num_word;
                        // add 'and' if not last element in VAL
                        if ($i == 1) {
                            $w_and          = _l('number_word_and');
                            $w_and          = ($w_and == ' ' ? '' : $w_and .= ' ');
                            $this->num_word = $w_and . '' . $this->num_word;
                        }
                    }
                    // if VAL >= 100, set the hundred word
                    else {
                        @$this->num_word = $this->word_array[mb_substr($this->num_value, 0, 1) . '00'] . (intval(mb_substr($this->num_value, 1, 2)) > 0 ? (_l('number_word_and') != ' ' ? ' ' . _l('number_word_and') . ' ' : ' ') : '') . $this->word_array[intval(mb_substr($this->num_value, 1, 2))] . ' ' . $this->thousand[$t] . $this->num_word;
                    }
                    $t++;
                }
                // add currency to word
                if (!empty($this->num_word)) {
                    $this->num_word .= '' . $this->currency0;
                }
            }
            // join the number and decimal words
            $this->val_word = $this->num_word . ' ' . $this->dec_word;

            if (get_option('total_to_words_lowercase') == 1) {
                $final_val = trim(mb_strtolower($this->val_word, 'UTF-8'));
            } else {
                $final_val = trim($this->val_word);
            }
        }

        return hooks()->apply_filters('before_return_num_word', $final_val, [
            'original_number' => $this->original_val,
            'currency'        => $in_currency0,
            'language'        => $this->language,
        ]);
    }

    private function convert_indian($num)
    {
        return (new NumToWordIndian($num))->get_words();
    }
}
