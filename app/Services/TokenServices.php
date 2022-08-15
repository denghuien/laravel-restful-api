<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/23
 * Time: 16:21
 */

namespace App\Services;

use App\Models\Token;
use App\Models\UserToken;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;

class TokenServices
{
    /**
     * @var
     */
    private $userId;

    /**
     * @var
     */
    private $deviceId;

    /**
     * @var
     */
    private $token;

    /**
     * @var
     */
    private $expire;

    /**
     * @var
     */
    private $parentId;

    /**
     * TokenServices constructor.
     * @param $userId
     * @param $deviceId
     * @param $token
     * @param $expire
     * @param int $parentId
     */
    public function __construct($userId, $deviceId, $token, $expire, $parentId = 0)
    {
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->token = $token;
        $this->expire = $expire;
        $this->parentId = $parentId;
    }

    /**
     * @return string
     * @throws ApiException
     */
    public function register(): string
    {
        $tokenId = md5($this->token);
        $parentId = $this->parentId;
        $parent =  Token::when($parentId, function ($query) use ($parentId) {
            $query->where('uuid', $parentId);
        })->first();
        if ($parent) {
            $parentId = $parent->parent_id;
        }
        DB::beginTransaction();
        try {
            $token = new Token;
            $token->device_id = $this->deviceId;
            $token->data = $this->token;
            $token->uuid = $tokenId;
            $token->parent_id = $parentId;
            $token->expired_at = date('Y-m-d H:i:s', $this->expire);
            $token->save();
            $token->user()->save(new UserToken(['token_id' => $tokenId, 'user_id' => $this->userId]));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $tokenId;
    }
}
