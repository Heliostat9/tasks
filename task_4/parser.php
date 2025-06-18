<?php

function parse_url_custom(string $url): array
{
    $result = [
        'scheme' => null,
        'host' => null,
        'port' => null,
        'user' => null,
        'pass' => null,
        'path' => null,
        'query' => null,
        'fragment' => null,
    ];

    $remain = $url;

    if (preg_match('/^([a-zA-Z][a-zA-Z0-9+.-]*):\/\/(.*)$/', $remain, $m)) {
        $result['scheme'] = $m[1];
        $remain = $m[2];
    }

    if (($pos = strpos($remain, '#')) !== false) {
        $result['fragment'] = substr($remain, $pos + 1);
        $remain = substr($remain, 0, $pos);
    }

    if (($pos = strpos($remain, '?')) !== false) {
        $result['query'] = substr($remain, $pos + 1);
        $remain = substr($remain, 0, $pos);
    }

    if (preg_match('/^([^@]+)@(.*)$/', $remain, $m)) {
        $auth = $m[1];
        $remain = $m[2];
        if (($pos = strpos($auth, ':')) !== false) {
            $result['user'] = substr($auth, 0, $pos);
            $result['pass'] = substr($auth, $pos + 1);
        } else {
            $result['user'] = $auth;
        }
    }

    if (preg_match('/^\[([^\]]+)\](?::(\d+))?(.*)$/', $remain, $m)) {
        $result['host'] = $m[1];
        if ($m[2] !== '') {
            $result['port'] = (int)$m[2];
        }
        $remain = $m[3];
    } else if (preg_match('/^([^:\/]+)(?::(\d+))?(.*)$/', $remain, $m)) {
        $result['host'] = $m[1];
        if ($m[2] !== '') {
            $result['port'] = (int)$m[2];
        }
        $remain = $m[3];
    }

    $result['path'] = $remain !== '' ? $remain : null;

    return array_filter($result, static fn($v) => $v !== null);
}