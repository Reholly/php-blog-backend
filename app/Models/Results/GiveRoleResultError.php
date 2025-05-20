<?php

namespace App\Models\Results;

enum GiveRoleResultError
{
    const NONE = '';
    const GRANT_ADMIN = 'Нельзя выдать роль администратора';
    const UNEXISTING_ROLE = 'Такой роли не существует';
}
