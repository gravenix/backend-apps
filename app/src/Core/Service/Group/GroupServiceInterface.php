<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;

interface GroupServiceInterface
{
    public function createGroup(string $name, User $user): bool;

    /**
     * @return Group[]
     */
    public function searchGroup(array $groupSearchCriteria): array;
}