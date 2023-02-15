<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Determine whether the user can update the entity.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Entity  $entity
     * @return mixed
     */
    public function update(?User $user, $entity)
    {
        if (!$user) {
            return false;
        }
        return auth()->user()->can('update', $entity->organisation) ||
            auth()->user()->can('update', $entity->character);
    }


    /**
     * Determine whether the user can update the entity.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Entity  $entity
     * @return mixed
     */
    public function delete(?User $user, $entity)
    {
        if (!$user) {
            return false;
        }
        return auth()->user()->can('delete', $entity->organisation) ||
            auth()->user()->can('delete', $entity->character);
    }
}
