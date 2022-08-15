<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/23
 * Time: 16:12
 */

namespace App\Services;

use App\Models\Device;
use App\Models\UserDevice;
use Jenssegers\Agent\Agent;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;


class DeviceServices
{
    /**
     * @var
     */
    private $userId;

    /**
     * @var array
     */
    public static $mobileRules = [
        'Meizu' => 'MZ[- ]?',
        'Huawei' => 'HUAWEI[- ]?|HUAWEIYAL[- ]?'
    ];

    /**
     * @var array
     */
    public static $manufacturerRules = [
        'iPhone,iPad,Macintosh' => 'Apple',
        'Meizu' => 'Meizu',
        'BlackBerry,BlackBerryTablet' => 'BlackBerry',
        'HTC,HTCtablet' => 'HTC',
        'Nexus,NexusTablet,GoogleTablet' => 'Google',
        'Dell,DellTablet' => 'Dell',
        'Motorola,MotorolaTablet' => 'Motorola',
        'NookTablet' => 'Nook',
        'AcerTablet' => 'Acer',
        'Samsung,SamsungTablet' => 'Samsung',
        'Kindle' => 'Amazon',
        'SurfaceTablet,Lumia' => 'Microsoft',
        'HPTablet' => 'HP',
        'LG,LGTablet' => 'LG',
        'Sony,SonyTablet' => 'Sony',
        'Asus,AsusTablet' => 'Asus',
        'NokiaLumia,NokiaLumiaTablet' => 'Nokia',
        'Micromax' => 'Micromax',
        'Palm' => 'Palm',
        'Vertu' => 'Vertu',
        'Pantech' => 'Pantech',
        'Fly' => 'Fly',
        'Wiko' => 'Wiko',
        'iMobile' => 'iMobile',
        'SimValley' => 'SimValley',
        'Wolfgang' => 'Wolfgang',
        'Alcatel' => 'Alcatel',
        'Nintendo' => 'Nintendo',
        'Amoi' => 'Amoi',
        'INQ' => 'INQ',
        'OnePlus' => 'OnePlus',
        'GenericPhone' => 'GenericPhone',
        'Huawei,Honor,HuaweiTablet' => 'Huawei',
        'Oppo' => 'Oppo',
        'Vivo' => 'Vivo',
        'iQoo' => 'iQoo',
        'Realme' => 'Realme',
        'Nubia' => 'Nubia',
        'Lenovo,LenovoTablet' => 'Lenovo',
        'ZTE' => 'ZTE',
        'Soaiy' => 'Soaiy',
    ];

    /**
     * DeviceServices constructor.
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     * @throws ApiException
     */
    public function register(): string
    {
        $userId = $this->userId;
        $agent = new Agent();
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $deviceSystem = $agent->platform();
        $deviceSystemVersion = $agent->version($deviceSystem);
        $language = implode(',', $agent->languages());
        $deviceName = $agent->device();
        if ($agent->isTablet()) {
            $deviceType = 'tablet';
        } else if ($agent->isMobile()) {
            $deviceType = 'mobile';
            $deviceName = $this->matchMobile();
        } else if ($agent->isRobot()) {
            $deviceType = 'robot';
        } else {
            $deviceType = 'desktop';
        }
        $deviceManufacturer = $this->getManufacturer($deviceName);
        $userAgent = $agent->getUserAgent();
        $deviceId = md5($userAgent);
        $device = Device::where(['uuid' => $deviceId])->first();
        $userDevice = UserDevice::where(['user_id' => $userId, 'device_id' => $deviceId])->first();
        DB::beginTransaction();
        try {
            if (! $device) {
                $device = new Device;
                $device->uuid = $deviceId;
                $device->name = $deviceName;
                $device->type = $deviceType;
                $device->manufacturer = $deviceManufacturer;
                $device->system = $deviceSystem;
                $device->system_version = $deviceSystemVersion;
                $device->language = $language;
                $device->browser = $browser;
                $device->browser_version = $browserVersion;
                $device->user_agent = $userAgent;
                $device->save();
            }
            if (! $userDevice) {
                $device->user()->save(new UserDevice(['device_id' => $deviceId, 'user_id' => $userId]));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $deviceId;
    }

    /**
     * @return int|null|string
     */
    public function matchMobile()
    {
        $agent = new Agent();
        $rules = array_merge(Agent::getPhoneDevices(), self::$mobileRules);
        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }
            if ($agent->match($regex)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param $device
     * @return mixed|string
     */
    public function getManufacturer($device)
    {
        $ret = 'Unknown';
        if ($device) {
            $rules = self::$manufacturerRules;
            foreach ($rules as $key => $value) {
                $devices =  explode(',', $key);
                if (in_array($device, $devices)) {
                    $ret = $value;
                    break;
                }
            }
        }

        return $ret;
    }
}
