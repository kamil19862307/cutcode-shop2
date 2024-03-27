<?php

namespace Domain\Cart\Contracts;

interface CartIdentityStorageContract
{
    // Будет забирать идентификатор нашего стораджа
    public function get(): string;
}
