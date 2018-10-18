<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Users Blades Language Lines
    |--------------------------------------------------------------------------
    */

    'showing-all-users'     => 'Liste des utilisateurs',
    'users-menu-alt'        => 'Montrer le menu de gestion des utilisateurs',
    'create-new-user'       => 'Créer un nouvel utilisateur',
    'show-deleted-users'    => 'Afficher l\'utilisateur supprimé',
    'editing-user'          => 'Modifier l\'utilisateur :name',
    'showing-user'          => 'Afficher l\'utilisateur :name',
    'showing-user-title'    => 'Information de :name',

    'users-table' => [
        'caption'   => '{1} :userscount utilisateurs|[2,*] :users utilisateurs',
        'id'        => 'ID',
        'name'      => 'Nom',
        'email'     => 'Email',
        'role'      => 'Role',
        'created'   => 'Créé le',
        'updated'   => 'Mis à jour le',
        'actions'   => 'Actions'
    ],

    'buttons' => [
        'create-new'    => '<span class="hidden-xs hidden-sm">Nouvel Utilisateur</span>',
        'delete'        => '<i class="far fa-trash-alt fa-fw" aria-hidden="true"></i>  <span class="hidden-xs hidden-sm">Supprimer</span>',
        'show'          => '<i class="fas fa-eye fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Afficher</span>',
        'edit'          => '<i class="fas fa-pencil-alt fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Modifier</span>',
        'back-to-users' => '<span class="hidden-sm hidden-xs">Retourner </span><span class="hidden-xs">à la liste d\'utilisateurs</span>',
        'back-to-user'  => 'Retourner  <span class="hidden-xs">à l\'utilisateur</span>',
        'delete-user'   => '<i class="far fa-trash-alt fa-fw" aria-hidden="true"></i>  <span class="hidden-xs">Supprimer</span><span class="hidden-xs"> l\'utilisateur</span>',
        'edit-user'     => '<i class="fas fa-pencil-alt fa-fw" aria-hidden="true"></i> <span class="hidden-xs">Modifier</span><span class="hidden-xs"> l\'utilisateur</span>',
    ],

    'tooltips' => [
        'delete'        => 'Supprimer',
        'show'          => 'Afficher',
        'edit'          => 'Modifier',
        'create-new'    => 'Créer un nouvel utilisateur',
        'back-users'    => 'Retourner à la liste d\'utilisateur',
        'email-user'    => 'Email :user',
        'submit-search' => 'Démarrer la recherche',
        'clear-search'  => 'Nettoyer le résultat de recherche',
    ],

    'messages' => [
        'userNameTaken'          => 'Le nom d\'utilisateur est déjà pris',
        'userNameRequired'       => 'Le nom d\'utilisateur est obligatoire',
        'fNameRequired'          => 'Le prénom est obligatoire',
        'lNameRequired'          => 'Le nom est obligatoire',
        'emailRequired'          => 'L\'Email est obligatoire',
        'emailInvalid'           => 'L\'Email n\'est pas valide',
        'passwordRequired'       => 'Le mot de passe est obligatoire',
        'PasswordMin'            => 'La taille du mot de passe doit au moins être de 6 caractères',
        'PasswordMax'            => 'La taille du mot de passe maximum est de 20 caractères',
        'captchaRequire'         => 'Le Captcha est obligatoire',
        'CaptchaWrong'           => 'Le Captcha est faux, merci de réessayer',
        'roleRequired'           => 'Le role est obligatoire.',
        'user-creation-success'  => 'Utilisateur créé avec succès!',
        'update-user-success'    => 'Utilisateur modifié avec succès!',
        'delete-success'         => 'Utilisateur supprimé avec succès!',
        'cannot-delete-yourself' => 'Vous ne oouvez pas vous supprimer!!',
    ],

    'show-user' => [
        'id'                => 'ID utilisateur',
        'name'              => 'Nom d\'utilisateur',
        'email'             => 'Email <span class="hidden-xs"> utilisateur</span>',
        'role'              => 'Role',
        'created'           => 'Créé <span class="hidden-xs">le</span>',
        'updated'           => 'Mis à jour <span class="hidden-xs">le</span>',
        'labelRole'         => 'Role',
        'labelAccessLevel'  => '<span class="hidden-xs">U+tilisateur</span> Access Level|<span class="hidden-xs">User</span> Access Levels',
    ],

    'search'  => [
        'title'         => 'Affichage des résultats',
        'found-footer'  => ' Lignes trouvées',
        'no-results'    => 'Aucun résultat',
    ],
];
