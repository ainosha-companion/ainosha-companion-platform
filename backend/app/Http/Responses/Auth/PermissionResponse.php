<?php

declare(strict_types=1);

namespace App\Http\Responses\Auth;

use App\Domain\Auth\Entities\Permission;
use App\Http\Responses\AbstractResponse;
use Illuminate\Http\Response;
class PermissionResponse extends AbstractResponse
{
    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct(Response::HTTP_CREATED);

        $this->setSuccess(true);
        $this->setResult([
            'id' => $permission->getId(),
            'name' => $permission->getName(),
            'guard_name' => $permission->getGuardName(),
        ]);
    }
}