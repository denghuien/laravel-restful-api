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
     * @return mixed
     * @throws ApiException
     */
    public function register()
    {
        $userId = $this->userId;
        $agent = new Agent();
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $system = $agent->platform();
        $systemVersion = $agent->version($system);
        $language = implode(',', $agent->languages());
        $name = $agent->device();
        if ($agent->isTablet()) {
            $type = 'tablet';
        } else if ($agent->isMobile()) {
            $type = 'mobile';
            $name = $this->matchMobile();
        } else if ($agent->isRobot()) {
            $type = 'robot';
        } else {
            $type = 'desktop';
        }
        $manufacturer = $this->getManufacturer($name);
        $userAgent = $agent->getUserAgent();
        $uuid = md5($userAgent);
        DB::beginTransaction();
        try {
            UserDevice::firstOrCreate(['device_id' => $uuid, 'user_id' => $userId], []);
            $device = Device::firstOrCreate(['uuid' => $uuid], ['name' => $name, 'type' => $type, 'manufacturer' => $manufacturer, 'system' => $system, 'system_version' => $systemVersion, 'language' => $language, 'browser' => $browser, 'browser_version' => $browserVersion, 'user_agent' => $userAgent]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getCode(), $e->getMessage());
        }

        return $device;
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
