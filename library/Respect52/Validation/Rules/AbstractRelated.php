<?php
/**
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Rogerio Prado de Jesus <rogeriopradoj@gmail.com>
 */
abstract class Respect52_Validation_Rules_AbstractRelated extends Respect52_Validation_Rules_AbstractRule implements Respect52_Validation_Validatable
{

    public $mandatory = true;
    public $reference = '';
    public $validator;

    abstract public function hasReference($input);

    abstract public function getReferenceValue($input);

    public function __construct($reference, Respect52_Validation_Validatable $validator=null,
                                $mandatory=true)
    {
        $this->setName($reference);
        $this->reference = $reference;
        $this->validator = $validator;
        $this->mandatory = $mandatory;
    }

    public function assert($input)
    {
        $hasReference = $this->hasReference($input);

        if ($this->mandatory && !$hasReference)
            throw $this->reportError($input, array('hasReference' => false));
        elseif ((!$this->mandatory && !$hasReference) || !$this->validator)
            return true;

        try {
            return $this->validator->assert($this->getReferenceValue($input));
        } catch (Respect52_Validation_Exceptions_ValidationException $e) {
            throw $this
                ->reportError($this->reference, array('hasReference' => true))
                ->addRelated($e);
        }
    }

    public function check($input)
    {
        $hasReference = $this->hasReference($input);

        if ($this->mandatory && !$hasReference)
            throw $this->reportError($input, array('hasReference' => false));
        elseif ((!$this->mandatory && !$hasReference) || !$this->validator)
            return true;

        return $this->validator->check($this->getReferenceValue($input));
    }

    public function validate($input)
    {
        $hasReference = $this->hasReference($input);

        if ($this->mandatory && !$hasReference)
            return false;
        elseif (!$this->mandatory && !$hasReference)
            return true;

        return is_null($this->validator)
        || $this->validator->validate($this->getReferenceValue($input));
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