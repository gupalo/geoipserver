<?php

namespace App\Request;

use App\Helper\ArrayHelper;
use Workerman\Protocols\Http\Request;

class RealIpExtractor
{
    public static ?string $defaultIp = null;

    public function __construct(
        private ?ArrayHelper $arrayHelper = null,
    ) {
        $this->arrayHelper ??= new ArrayHelper();
    }

    public function getRealIp(Request $request): string
    {
        return $this->getRealIps($request)[0] ?? 'unknown';
    }

    public function getRealIps(Request $request): array
    {
        $ipsString = $request->header('CF-Connecting-IP', $request->header('X-Real-IP', $request->header('X-Forwarded-For', self::$defaultIp)));

        return $this->arrayHelper->toUniqArray($ipsString);
    }
}
