<?php

namespace App\Security;

use App\Helper\ArrayHelper;
use Workerman\Protocols\Http\Request;

class Authenticator
{
    public function __construct(
        private array $keys = [],
        ?ArrayHelper $arrayHelper = null,
    ) {
        $arrayHelper ??= new ArrayHelper();

        $this->keys = $this->keys ?: $arrayHelper->toUniqArray($_ENV['API_KEYS'] ?? '');
    }

    public function isAuthenticated(Request $request): bool
    {
        if (empty($this->keys)) {
            return true;
        }

        $key = $request->header('X-Api-Key', $request->post('apikey', $request->get('apikey', '')));

        return in_array($key, $this->keys, true);
    }
}
