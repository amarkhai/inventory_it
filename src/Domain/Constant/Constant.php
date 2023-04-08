<?php

namespace App\Domain\Constant;

class Constant
{
    public const ITEM_PATH_REGEX = "/^([0-9]*\.)*[0-9]+$/";

    public const USER_NAME_MIN_LENGTH = 3;
    public const USER_NAME_MAX_LENGTH = 50;
    public const USER_FIRST_AND_SECOND_NAME_MIN_LENGTH = 2;
    public const USER_FIRST_AND_SECOND_NAME_MAX_LENGTH = 100;
    public const PASSWORD_MIN_LENGTH = 6;
    public const PASSWORD_MAX_LENGTH = 20;
    public const PASSWORD_HASH_LENGTH = 60;
}
