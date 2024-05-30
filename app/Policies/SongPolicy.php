<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SongPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any songs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true; // Tout le monde peut voir les chansons
    }

    /**
     * Determine whether the user can view the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Song  $song
     * @return mixed
     */
    public function view(User $user, Song $song)
    {
        return true; // Tout le monde peut voir une chanson spécifique
    }

    /**
     * Determine whether the user can create songs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Seul un utilisateur ayant le rôle "CO" (Chef Orchestre) peut créer des chansons
        return $user->roles->contains('name', 'CO');
    }

    /**
     * Determine whether the user can update the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Song  $song
     * @return mixed
     */
    public function update(User $user, Song $song)
    {
        // Seul un utilisateur ayant le rôle "CO" et ayant enregistré la chanson peut la mettre à jour
        return $user->roles->contains('name', 'CO') && $song->recorded_by == $user->id;
    }

    /**
     * Determine whether the user can delete the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Song  $song
     * @return mixed
     */
    public function delete(User $user, Song $song)
    {
        // Seul un utilisateur ayant le rôle "CO" et ayant enregistré la chanson peut la supprimer
        return $user->roles->contains('name', 'CO') && $song->recorded_by == $user->id;
    }

    /**
     * Determine whether the user can restore the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Song  $song
     * @return mixed
     */
    public function restore(User $user, Song $song)
    {
        // Implémentation si nécessaire
        return false;
    }

    /**
     * Determine whether the user can permanently delete the song.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Song  $song
     * @return mixed
     */
    public function forceDelete(User $user, Song $song)
    {
        // Implémentation si nécessaire
        return false;
    }
}