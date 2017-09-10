<?php

namespace Bounce\Bounce\Middleware\Emitter\Plugin;

interface EmitterPluginInterface
{
    public function __invoke($event, callable $next);
}
