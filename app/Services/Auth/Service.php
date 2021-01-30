<?php

namespace App\Services\Auth;

use App\Services\Auth\Contracts\Service as ServiceContract;
use External\Baz\Auth\Responses\Success;
use External\Foo\Exceptions\AuthenticationFailedException;
use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration as TokenConfiguration;
use Lcobucci\JWT\Token;

class Service implements ServiceContract
{
    /**
     * Default username pattern.
     */
    const DEFAULT_USERNAME_PATTERN = '/^([A-Z]{3})_.*/';

    /**
     * Service config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * JWT token generator.
     *
     * @var \Lcobucci\JWT\Configuration
     */
    protected $tokenConfiguration;

    /**
     * Create auth service instance.
     *
     * @param \Illuminate\Config\Repository $config
     * @param \Lcobucci\JWT\Configuration   $tokenConfiguration
     */
    public function __construct(
        Repository $config,
        TokenConfiguration $tokenConfiguration
    )
    {
        $this->config = $config;
        $this->tokenConfiguration = $tokenConfiguration;
    }

    /**
     * Attempt to authenticate user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attempt(string $username, string $password): bool
    {
        try {
            // attempt to authenticate user by strategy
            $attemptMethod = $this->findServiceAttemptMethodNameByUsername($username);
            return $this->$attemptMethod($username, $password);
        } catch (\Exception $exception) {
            // if exception throw
            // authentication fails
            return false;
        }
    }

    /**
     * Attempt to authenticate bar service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForBarService(string $username, string $password): bool
    {
        return app()->call(
            'External\Bar\Auth\LoginService@login',
            ['login' => $username, 'password' => $password]
        );
    }

    /**
     * Attempt to authenticate baz service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForBazService(string $username, string $password): bool
    {
        $response = app()->call(
            'External\Baz\Auth\Authenticator@login',
            ['login' => $username, 'password' => $password]
        );

        return $response instanceof Success;
    }

    /**
     * Attempt to authenticate foo service user.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function attemptForFooService(string $username, string $password): bool
    {
        try {
            app()->call(
                'External\Foo\Auth\AuthWS@authenticate',
                ['login' => $username, 'password' => $password]
            );
            return true;
        } catch (AuthenticationFailedException $exception) {
            return false;
        }
    }

    /**
     * Generate JWT token.
     *
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(): Token
    {
        return $this->tokenConfiguration
            ->builder()
            ->expiresAt(
                now()->addHours(3)->toDateTimeImmutable()
            )
            ->getToken(
                $this->tokenConfiguration->signer(),
                $this->tokenConfiguration->signingKey()
            );
    }

    /**
     * Compose service attempt method name.
     *
     * @param string $servicePrefix
     *
     * @return string
     */
    protected function composeServiceAttemptMethodName(string $servicePrefix): string
    {
        $methodName = sprintf('attempt_for_%s_service', $servicePrefix);
        $methodName = Str::lower($methodName);
        $methodName = Str::camel($methodName);

        return $methodName;
    }

    /**
     * Find attempt method name by passed username.
     *
     * @param string $username
     *
     * @return string
     */
    protected function findServiceAttemptMethodNameByUsername(string $username): string
    {
        if (! $this->validateUsername($username)) {
            throw new \App\Services\Auth\Exceptions\AuthenticationFailedException(
                'Invalid login format.'
            );
        }

        return $this->composeServiceAttemptMethodName(
            $this->getServicePrefixFromUsername($username)
        );
    }

    /**
     * Get service prefix from username.
     * If not found, return empty string.
     *
     * @param string $username
     *
     * @return string
     */
    protected function getServicePrefixFromUsername(string $username): string
    {
        $matches = [];
        preg_match($this->getUsernamePattern(), $username, $matches, PREG_OFFSET_CAPTURE);

        // now we get the service prefix
        // that locates on key 0 of the array on key 1
        return Arr::get($matches, '1.0', '');
    }

    /**
     * Get username pattern.
     *
     * @return string
     */
    protected function getUsernamePattern(): string
    {
        return $this->config->get('username_pattern', static::DEFAULT_USERNAME_PATTERN);
    }

    /**
     * Validate passed username by config username pattern.
     *
     * @param string $username
     *
     * @return bool
     */
    protected function validateUsername(string $username): bool
    {
        return preg_match($this->getUsernamePattern(), $username);
    }
}
