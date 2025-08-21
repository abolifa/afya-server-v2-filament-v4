<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransferInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('view_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('update_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('delete_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('force_delete_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('restore_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, TransferInvoice $transferInvoice): bool
    {
        return $user->can('replicate_transfer::invoices::transfer::invoice');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_transfer::invoices::transfer::invoice');
    }
}
