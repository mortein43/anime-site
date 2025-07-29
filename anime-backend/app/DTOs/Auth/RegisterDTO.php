<?php

namespace AnimeSite\DTOs\Auth;

use AnimeSite\DTOs\BaseDTO;

class RegisterDTO extends BaseDTO
{
    /**
     * Create a new RegisterDTO instance.
     *
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    protected static array $fields = [ 'email', 'password'];

}
