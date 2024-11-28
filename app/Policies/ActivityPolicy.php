<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow users to view their own activity logs
        return true; // Adjust permissions as needed
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        // Restrict access to logs the user caused
        return $activity->causer_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_activity');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        // Prevent updates unless explicitly allowed
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        // Allow deletion only for specific roles or permissions
        return $user->can('delete_activity');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_activity');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return false; // Default to not allowing force delete
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return false; // Default to not allowing bulk force delete
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return false; // Default to not allowing restore
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return false; // Default to not allowing bulk restore
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Activity $activity): bool
    {
        return false; // Default to not allowing replication
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return false; // Default to not allowing reordering
    }
}