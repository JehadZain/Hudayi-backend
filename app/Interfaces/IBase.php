<?php

namespace App\Interfaces;

interface IBase
{
    public function build(): void;

    public function filter(): object;

    public function byId(int $id): object;
}
