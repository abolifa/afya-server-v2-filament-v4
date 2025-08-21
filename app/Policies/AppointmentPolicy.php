<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_appointments::appointment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->can('view_appointments::appointment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_appointments::appointment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return $user->can('update_appointments::appointment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->can('delete_appointments::appointment');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_appointments::appointment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->can('force_delete_appointments::appointment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_appointments::appointment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->can('restore_appointments::appointment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_appointments::appointment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Appointment $appointment): bool
    {
        return $user->can('replicate_appointments::appointment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_appointments::appointment');
    }
}
