<?php

namespace App\Http\Controllers;

use App\Repositories\PassportRepositories as Repositories;

class PassportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function index(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $repositories =  new Repositories;

        return $repositories->index();
    }

    /**
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function login(): array
    {
        $repositories =  new Repositories;

        return $repositories->login();
    }

    /**
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function register(): array
    {
        $repositories =  new Repositories;

        return $repositories->register();
    }

    /**
     * @return bool
     * @throws \App\Exceptions\ApiException
     */
    public function logout(): bool
    {
        $repositories =  new Repositories;

        return $repositories->logout();
    }

    /**
     * @return bool
     * @throws \App\Exceptions\ApiException
     */
    public function passwordUpdate(): bool
    {
        $repositories =  new Repositories;

        return $repositories->passwordUpdate();
    }
}
