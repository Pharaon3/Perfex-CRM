<?php

use GuzzleHttp\Client;

defined('BASEPATH') or exit('No direct script access allowed');

class EnhanceSecurity
{
    protected $client;

    protected function retrieveBadData($filename)
    {
        $cache = $this->getCachedResults($filename);

        if ($cache && ! $this->isCacheExpired($filename)) {
            return $cache;
        }

        $results = [];

        try {
            $response = $this->getClient()->get($filename . '.list');

            if ($response->getStatusCode() === 200) {
                $results = explode("\n", $response->getBody()->getContents());
            }
        } catch (\Exception $e) {
        }

        return $results;
    }

    protected function getBadReferrers()
    {
        return $this->retrieveBadData('bad-referrers');
    }

    protected function getBadIps()
    {
        return $this->retrieveBadData('bad-ip-addresses');
    }

    protected function getBadUserAgents()
    {
        return $this->retrieveBadData('bad-user-agents');
    }

    protected function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new Client([
                 'base_uri' => 'https://raw.githubusercontent.com/mitchellkrogza/nginx-ultimate-bad-bot-blocker/master/_generator_lists/',
             ]);
        }

        return $this->client;
    }

    protected function isCacheExpired($filename)
    {
        $path           = $this->cachePath($filename);
        $cacheValidFor  = 1; // 1 day
        $cacheInSeconds = ($cacheValidFor * 24 * 60 * 60);

        return (time() - filemtime($path)) > $cacheInSeconds;
    }

    protected function cacheResults($results, $filename)
    {
        file_put_contents(
            $this->cachePath($filename),
            '<?php return ' . var_export($results, true) . ";\n"
        );

        return $results;
    }

    protected function getCachedResults($filename)
    {
        $path = $this->cachePath($filename);

        if (!file_exists($path)) {
            return false;
        }

        $cache = include_once($path);

        return $cache;
    }

    protected function cachePath($filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $filename . '.php';
    }

    public function protect()
    {
        if (! defined('APP_ENHANCE_SECURITY') || (defined('APP_ENHANCE_SECURITY') && !APP_ENHANCE_SECURITY)) {
            return;
        }

        if (in_array($_SERVER['HTTP_USER_AGENT'], $this->getBadUserAgents())) {
            $this->forbidden();
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? null;

        if ($referer && in_array($referer, $this->getBadReferrers())) {
            $this->forbidden();
        }

        if (in_array($this->getRealIpAddr(), $this->getBadIps())) {
            $this->forbidden();
        }
    }

    protected static function forbidden()
    {
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' 403 Forbidden');
        exit();
    }
}
