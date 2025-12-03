<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class Role
{
    public $role;
    public $roles;

    public function __construct($role, $roles)
    {
        $this->role = $role;
        $this->roles = $roles;
    }
}

class AuthService
{
    public function getUserRole()
    {
        // if (!App::environment(['local', 'staging'])) {
        //     $serverRemoteUser = request()->server('REMOTE_USER');
        //     if ($serverRemoteUser !== null) {
        //         if (strpos($serverRemoteUser, '//')) { // Windows
        //             $remoteUser = substr($serverRemoteUser, 1 + strpos($serverRemoteUser, '\\'));
        //         } else if (strpos($serverRemoteUser, '@')) { // Linux
        //             $remoteUser = substr($serverRemoteUser, 0, strpos($serverRemoteUser, '@'));
        //         }
        //         // Авторизуем пользователя 
        //         config()->set('database.connections.pgsql.username', $remoteUser);
        //         DB::purge('pgsql');
        //     } else {
        //         return [
        //             "login"     => null,
        //             "role"      => null,
        //             "roles"     => null,
        //             "tabNum"    => null,
        //         ];
        //     }
        // } else {
        // }
        $remoteUser = '02338430'; // Таб. номер локальной разработки
        $task = '%ptt05%';

        $tabNum = substr($remoteUser, 3);
        $tabNum = ltrim($tabNum, 0);
        // $getUserRoles = DB::SELECT(
        //     'SELECT UPPER(pr.rolname) AS role FROM pg_roles pr 
        //                             JOIN pg_auth_members pam ON pr.oid = pam.roleid AND LOWER(pr.rolname) LIKE :task::name
        //                             JOIN pg_roles pr1 ON pr1.oid = pam.member WHERE pr1.rolname = :remoteUser',
        //     [
        //         "remoteUser" => $remoteUser,
        //         "task" => $task,
        //     ]
        // );
        // if (!empty($getUserRoles)) {
        //     sort($getUserRoles);
        //     $role = $getUserRoles[0]->role;
        // } else {
        // }
        $role = 'PTT05A_1';
        $getUserRoles = ['PTT05A, PTT05A_1'];
        return [
            "login"     => $remoteUser,
            "role"      => $role,
            "roles"     => $getUserRoles,
            "tabNum"    => $tabNum,
        ];
    }
}
