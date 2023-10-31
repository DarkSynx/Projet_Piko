<?php

namespace Piko;

enum Types: string
{
    // Types scalaires
    case BOOLEAN = 'bool';
    case INTEGER = 'int';
    case FLOAT = 'float';
    case STRING = 'string';

    // Types composés
    case ARRAY = 'array';
    case OBJECT = 'object';
    case CALLABLE = 'callable';
    case ITERABLE = 'iterable';

    // Type spécial
    case NULL = 'null';

    // PHP 8 et types pseudo
    case MIXED = 'mixed';
    case FALSE = 'false';
    case VOID = 'void';
    case RESOURCE = 'resource'; // Bien que le type "resource" ne soit pas un type déclarable, il existe en tant que type de données interne.

    // Exemples de types d'union (juste pour illustrer)
    case INT_OR_STRING = 'int|string';
    case FLOAT_OR_INT = 'float|int';
    case ARRAY_OR_BOOL = 'array|bool';
    // ... Vous pouvez ajouter d'autres types d'union selon vos besoins

    // Types pseudo pour les retours de fonctions
    case STATIC = 'static'; // Pour indiquer que la méthode retourne une instance du même type que la classe à laquelle elle appartient.

    // Experimental
    case HEXA = 'hex';
    const SESSION = 'session';
}
