<?php

declare(strict_types=1);

namespace App\Contract;

interface DictionaryItemInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getSlug(): ?string;
}
