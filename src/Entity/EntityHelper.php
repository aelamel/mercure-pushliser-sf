<?php

namespace App\Entity;


trait EntityHelper
{
    public function setValue($key, $value)
    {
        if (method_exists($this, 'set' . ucfirst($key))) {
            $this->{'set'.ucfirst($key)}($value);
        }
    }
}