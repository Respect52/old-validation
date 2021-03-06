<?php
/**
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Rogerio Prado de Jesus <rogeriopradoj@gmail.com>
 */
abstract class Respect52_Validation_Rules_AbstractComposite extends Respect52_Validation_Rules_AbstractRule
{

    protected $rules = array();

    public function __construct()
    {
        $this->addRules(func_get_args());
    }

    public function addRule($validator, $arguments=array())
    {
        //var_dump($validator);exit;
        if (!$validator instanceof Respect52_Validation_Validatable)
            $this->appendRule(Respect52_Validation_Validator::buildRule($validator, $arguments));
        else
            $this->appendRule($validator);

        return $this;
    }

    public function sanitize($input)
    {
        foreach ($this->getRules() as $f)
            if ($f instanceof Respect52_Validation_Sanitizable)
                $input = $f->sanitize($input);
        return $input;
    }

    
    public function filter($input)
    {
        foreach ($this->getRules() as $f)
            $input = $f->filter($input);
        return $input;
    }

    public function removeRules()
    {
        $this->rules = array();
    }

    public function addRules(array $validators)
    {
        foreach ($validators as $key => $spec)
            if ($spec instanceof Respect52_Validation_Validatable)
                $this->appendRule($spec);
            elseif (is_numeric($key) && is_array($spec))
                $this->addRules($spec);
            elseif (is_array($spec))
                $this->addRule($key, $spec);
            else
                $this->addRule($spec);

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function hasRule($validator)
    {
        if (empty($this->rules))
            return false;

        if ($validator instanceof Respect52_Validation_Validatable)
            return isset($this->rules[spl_object_hash($validator)]);

        if (is_string($validator))
            foreach ($this->rules as $rule)
                if (get_class($rule) == __NAMESPACE__ . '\\' . $validator) //:TODO
                    return true;

        return false;
    }

    protected function appendRule(Respect52_Validation_Validatable $validator)
    {
        $this->rules[spl_object_hash($validator)] = $validator;
    }

    protected function validateRules($input)
    {
        $validators = $this->getRules();
        $exceptions = array();
        foreach ($validators as $v)
            try {
                $v->assert($input);
            } catch (Respect52_Validation_Exceptions_ValidationException $e) {
                $exceptions[] = $e;
            }
        return $exceptions;
    }

}

/**
 * LICENSE
 *
 * Copyright (c) 2009-2012, Alexandre Gomes Gaigalas.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     * Neither the name of Alexandre Gomes Gaigalas nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
