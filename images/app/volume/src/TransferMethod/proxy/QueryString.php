<?php

namespace DockWave\Core\TransferMethod\proxy;

class QueryString
{
    public function __construct(
        private readonly array $params = []
    )
    {
    }

    public function toString(): string
    {
        $res = [];
        foreach ($this->params as $key => $val) {
            $res[] = urlencode($key) . '=' . urlencode($val);
        }

        return implode('&', $res);
    }
}