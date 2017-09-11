<?php
namespace Bounce\Bounce\Middleware\Acceptor\Plugin;

interface AcceptorPluginInterface
{
    public function __invoke($map, $listener, $priority);
}
