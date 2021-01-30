<?php

namespace App\Services\Auth\Contracts;

use Lcobucci\JWT\Token;

interface Service
{
    /**
     * Attempt to authenticate user by username.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attempt(string $username, string $password): bool;

    /**
     * Attempt to authenticate bar service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForBarService(string $username, string $password): bool;

    /**
     * Attempt to authenticate baz service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForBazService(string $username, string $password): bool;

    /**
     * Attempt to authenticate foo service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForFooService(string $username, string $password): bool;

    /**
     * Generate JWT token.
     *
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(): Token;
}
