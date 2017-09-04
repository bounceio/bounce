<?php
namespace Bounce\Bounce\Listener;

interface AcceptorInterface
{


    public function addListener($argument1, $argument2, $argument3);

    public function listenersFor($argument1);
}
