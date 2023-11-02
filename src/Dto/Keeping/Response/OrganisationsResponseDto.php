<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\OrganisationDto;

readonly class OrganisationsResponseDto
{
    /**
     * @param OrganisationDto[] $organisations
     */
    public function __construct(
        public array $organisations
    ) {
    }
}
