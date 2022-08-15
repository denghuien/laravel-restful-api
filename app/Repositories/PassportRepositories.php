<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Token;
use App\Models\Agent;
use App\Libraries\ErrorCode;
use App\Services\TokenServices;
use App\Services\DeviceServices;
use App\Exceptions\ApiException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Passport\LoginRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\Passport\RegisterRequest;
use App\Http\Requests\Passport\PasswordUpdateRequest;

class PassportRepositories
{
    /**
     * @return Authenticatable|null
     */
    public function index(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function login(): array
    {
        $parameter = request()->all();
        $password = request()->input('password');
        $email = request()->input('email');
        $validatorRequest = new LoginRequest();
        $validator = Validator::make($parameter, $validatorRequest->rules(), $validatorRequest->messages(), $validatorRequest->attributes());
        if ($validator->fails()) {
            throw new ApiException(ErrorCode::PARAMETER_VALIDATE_FAILED, $validator->errors()->first());
        }
        $user = User::where('email', $email)->where('status', true)->first();
        if (! $user) {
            throw new ApiException(ErrorCode::USER_NOT_EXIST);
        }
        if ($password) {
            if (! Hash::check($password . $user->salt, $user->getAuthPassword())) {
                throw new ApiException(ErrorCode::PASSWORD_NOT_MATCH);
            }
        }
        DB::beginTransaction();
        try {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $expire = JWTAuth::getClaim('exp');
            $serviceDevice = new DeviceServices($user->id);
            $deviceId = $serviceDevice->register();
            $serviceToken = new TokenServices($user->id, $deviceId, $token, $expire);
            $serviceToken->register();
            $ret = [
                'type' => 'Bearer',
                'expire' => $expire,
                'token' => $token,
            ];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $ret;
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function register(): array
    {
        $parameter = request()->all();
        $password = request()->input('password');
        $email = request()->input('email');
        $validatorRequest = new RegisterRequest();
        $validator = Validator::make($parameter, $validatorRequest->rules(), $validatorRequest->messages(), $validatorRequest->attributes());
        if ($validator->fails()) {
            throw new ApiException(ErrorCode::PARAMETER_VALIDATE_FAILED, $validator->errors()->first());
        }
        $salt = getRandomString(10);
        $password = $password . $salt;
        DB::beginTransaction();
        try {
            $user = new User;
            $user->email = $email;
            $user->salt = $salt;
            $user->password = bcrypt($password);
            $user->save();
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $expire = JWTAuth::getClaim('exp');
            $serviceDevice = new DeviceServices($user->id);
            $deviceId = $serviceDevice->register();
            $serviceToken = new TokenServices($user->id, $deviceId, $token, $expire);
            $serviceToken->register();
            $ret = [
                'type' => 'Bearer',
                'expire' => $expire,
                'token' => $token,
            ];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $ret;
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function logout(): bool
    {
        $token = JWTAuth::getToken();
        $tokenId = md5($token);
        DB::beginTransaction();
        try {
            Token::where('uuid', $tokenId)->update(['revoked' => true]);
            JWTAuth::invalidate($token);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return true;
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function passwordUpdate(): bool
    {
        $auth = Auth::user();
        $parameter = request()->all();
        $passwordOriginal = request()->input('password_original');
        $password = request()->input('password');
        $validatorRequest = new PasswordUpdateRequest();
        $validator = Validator::make($parameter, $validatorRequest->rules(), $validatorRequest->messages(), $validatorRequest->attributes());
        if ($validator->fails()) {
            throw new ApiException(ErrorCode::PARAMETER_VALIDATE_FAILED, $validator->errors()->first());
        }
        if (! Hash::check($passwordOriginal . $auth->salt, $auth->getAuthPassword())) {
            throw new ApiException(ErrorCode::PASSWORD_NOT_MATCH);
        }
        $password = $password . $auth->salt;
        $auth->password = bcrypt($password);
        $auth->save();

        return true;
    }
}
