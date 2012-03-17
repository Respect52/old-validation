<?php
//namespace Respect\Validation\Rules;
class Respect52_Validation_Rules_PrimeNumber extends Respect52_Validation_Rules_AbstractRule
{
    public function validate($input)
    {
        if (is_numeric($input)&&$input>0):
            $cont = 0;
            for($i=1;$i<=$input;$i++)
                if (($input % $i)==0)
                    $cont = $cont + 1;
            if ($cont<=2)
                $input=1;
            else
                $input=0;
        else:
            $input=0;
        endif;
        return (boolean)$input;
    }
}
