<?php

namespace Sarfraznawaz2005\VisitLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Sarfraznawaz2005\VisitLog\Models\VisitLog as VisitLogModel;

class VisitLog
{
    protected $browser = null;
    protected $cachePrefix = 'visitlog';
    protected $freegeoipUrl = 'http://api.ipstack.com';
    protected $ipApiUrl = 'http://ip-api.com';
    protected $tokenString = '&output=json&legacy=1';
    protected $ip2locationIOUrl = 'https://api.ip2location.io';

    /**
     * VisitLog constructor.
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Saves visit info into db.
     *
     * @return mixed
     */
    public function save()
    {
        $data = $this->getData();

        if (config('visitlog.unique')) {
            $model = VisitLogModel::where('ip', $this->getUserIP())->first();

            if ($model) {
                // update record of same IP eg new visit times, etc
                $model->touch();
                return $model->update($data);
            }
        }

        return VisitLogModel::create($data);
    }

    /**
     * Returns all saved information.
     */
    public function all()
    {
        return VisitLogModel::all();
    }

    /**
     * Get's IP address of visitor.
     *
     * @return mixed
     */
    protected function getUserIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = @$_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip ?: '0.0.0.0';
    }

    /**
     * Gets OS information.
     *
     * @return string
     */
    protected function getBrowserInfo()
    {
        $browser = $this->browser->getBrowser() ?: 'Other';
        $browserVersion = $this->browser->getVersion();

        if (trim($browserVersion)) {
            return $browser . ' (' . $browserVersion . ')';
        }

        return $browser;
    }

    /**
     * Returns visit data to be saved in db.
     *
     * @return array
     */
    protected function getData()
    {
        $ip = $this->getUserIP();
        $cacheKey = $this->cachePrefix . $ip;
        $url_freegeoip = $this->freegeoipUrl . '/' . $ip . '?' . '&access_key=' . config('visitlog.token') . $this->tokenString;
        $url_ipApi = $this->ipApiUrl . '/json/' . $ip;
        $url_ip2locationIO = (config('visitlog.ip2locationio_key') == 'PASTE_YOUR_IP2LOCATION_IO_API_KEY') ? $this->ip2locationIOUrl . '/?ip=' . $ip : $this->ip2locationIOUrl . '/?ip=' . $ip . '&key=' . config('visitlog.ip2locationio_key');

        // basic info
        $data = [
            'ip' => $ip,
            'browser' => $this->getBrowserInfo(),
            'os' => $this->browser->getPlatform() ?: 'Unknown',
        ];

        // info from https://ip-api.com/
        if (config('visitlog.ip_api')) {
            if (config('visitlog.cache')) {
                $ipApiCacheKey = $cacheKey . '_apiip';
                $ipApiData = unserialize(Cache::get($ipApiCacheKey));

                if (!$ipApiData) {
                    $ipApiData = @json_decode(file_get_contents($url_ipApi), true);

                    if ($ipApiData) {
                        Cache::forever($ipApiCacheKey, serialize($ipApiData));
                    }
                }
            } else {
                $ipApiData = @json_decode(file_get_contents($url_ipApi), true);
            }

            if ($ipApiData && $ipApiData['status'] == 'success') {
                $parsedData = [
                    'country_name' => $ipApiData['country'],
                    'region_name' => $ipApiData['regionName'],
                    'city' => $ipApiData['city'],
                    'zip_code' => $ipApiData['zip'],
                    'time_zone' => $ipApiData['timezone'],
                    'latitude' => $ipApiData['lat'],
                    'longitude' => $ipApiData['lon'],
                ];

                $data = array_merge($data, $parsedData);
            }
        } elseif (config('visitlog.ip2locationio')) {
            // info from https://www.ip2location.io
            if (config('visitlog.cache')) {
                $ip2locationIOCacheKey = $cacheKey . '_iplio';
                $ip2locationIOData = unserialize(Cache::get($ip2locationIOCacheKey));

                if (!$ip2locationIOData) {
                    $ip2locationIOData = @json_decode(file_get_contents($url_ip2locationIO), true);

                    if ($ip2locationIOData) {
                        Cache::forever($ip2locationIOCacheKey, serialize($ip2locationIOData));
                    }
                }
            } else {
                $ip2locationIOData = @json_decode(file_get_contents($url_ip2locationIO), true);
            }

            if ($ip2locationIOData) {
                $parsedData = [
                    'country_name' => $ip2locationIOData['country_name'],
                    'region_name' => $ip2locationIOData['region_name'],
                    'city' => $ip2locationIOData['city_name'],
                    'zip_code' => $ip2locationIOData['zip_code'],
                    'time_zone' => $ip2locationIOData['time_zone'],
                    'latitude' => $ip2locationIOData['latitude'],
                    'longitude' => $ip2locationIOData['longitude'],
                ];

                $data = array_merge($data, $parsedData);
            }
        } else {
            // info from http://freegeoip.net
            if (config('visitlog.iptolocation')) {
                if (config('visitlog.cache')) {
                    $freegeoipCacheKey = $cacheKey . '_freegeoip';
                    $freegeoipData = unserialize(Cache::get($freegeoipCacheKey));
    
                    if (!$freegeoipData) {
                        $freegeoipData = @json_decode(file_get_contents($url_freegeoip), true);
    
                        if ($freegeoipData) {
                            Cache::forever($freegeoipCacheKey, serialize($freegeoipData));
                        }
                    }
                } else {
                    $freegeoipData = @json_decode(file_get_contents($url_freegeoip), true);
                }
    
                if ($freegeoipData) {
                    $data = array_merge($data, $freegeoipData);
                }
            }
        }


        $userData = $this->getUser();

        if ($userData) {
            $data = array_merge($data, $userData);
        }
                
        return $data;
    }

    /**
     * Gets logged user info.
     */
    protected function getUser()
    {
        $userData = [];

        if (config('visitlog.log_user')) {
            $name = '';
            $userNameFields = config('visitlog.user_name_fields');

            if (Auth::check()) {
                $user = Auth::user();
                $userData['user_id'] = $user->id;

                if (is_array($userNameFields)) {
                    foreach ($userNameFields as $userNameField) {
                        $name .= @$user->$userNameField . ' ';
                    }

                    $name = rtrim($name);
                } else {
                    $name = @$user->$userNameFields;
                }

                $userData['user_name'] = $name;
            } else {
                $userData['user_id'] = 0;
                $userData['user_name'] = 'Guest';
            }
        }

        return $userData;
    }
}
