<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Determine if the application should specify trusted hosts.
     *
     * @return bool
     */
    protected function shouldSpecifyTrustedHosts()
    {
        return true;
    }

    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        $kbDomain = $this->app['config']->get('knowledge-base.domain');

        return [
            $this->allSubdomainsOfApplicationUrl(),
            '^(.+\.)?' . preg_quote($kbDomain) . '$'
        ];
    }
}
