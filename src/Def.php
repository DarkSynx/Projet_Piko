<?php

namespace Piko;

use RuntimeException;

class Def
{
    private String $aliasName;
    private mixed $type;
    private mixed $value;
    private bool $debugMode;

    private function __construct($aliasName, $value, $type, $debug)
    {
        $this->aliasName = $aliasName;
        $this->value = $value;
        $this->type = $type;
        $this->debugMode = $debug;

        $this->createAlias();
    }

    public static function init($aliasName, $value = NULL, $type = Types::MIXED, $debug = false)
    {
        return (new self( $aliasName, $value, $type, $debug ))->createAlias();
    }

    private function createAlias()
    {
        $fullAliasName = __NAMESPACE__ . '\\' . $this->aliasName;

        if (!class_exists($fullAliasName, false)) {
            if (class_exists(prima::class)) {
                class_alias(prima::class, $fullAliasName);
            } else {
                throw new RuntimeException("Class Prima not found");
            }
        }

        return $fullAliasName::init(
            aliasName: $this->aliasName,
            value: $this->value,
            type: $this->type,
            debug: $this->debugMode
        );
    }



}