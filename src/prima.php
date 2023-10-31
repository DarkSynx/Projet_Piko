<?php

namespace Piko;


use Closure;
use Exception;
use InvalidArgumentException;
use Iterator;

class prima extends primaLibrairie
{

    public static mixed $USE;


    public function __construct($value, $type, $debug)
    {
        //echo PHP_EOL;
        // var_dump($type, $value, $debug);
        $this->primaVar = $value;
        $this->type = $type;
        $this->debugMode = $debug;
        $this->ifDebugMode();
    }

    public static function _Get()
    {
        return self::instancesAction()->get();
    }

    public static function _Set($val): null
    {
        self::instancesAction()->set( $val );
        return null;
    }


    public static function _SetType($val): null
    {
        self::instancesAction()->setType( $val );
        return null;
    }

    public static function Exploit($value = null, $type = null, $debug = false): static
    {
        if ( $value !== null && $type !== null && $debug !== false ) {
            return self::init(
                aliasName: '',
                value: $value,
                type: $type,
                debug: $debug );
        }

        return self::use();
    }

    public static function use(): static
    {
        return self::instancesAction();
    }

    public function box($functions): static
    {
        foreach ($functions as $name => $function) {
            $boundFunction = $function->bindTo( $this, $this );  // lier la closure à cet objet
            $this->customFunctions[$name] = $boundFunction;
        }
        return $this;
    }

    public function declare(string $name, Closure $function): static
    {
        $this->customFunctions[$name] = function (...$args) use ($function) {
            $this->primaVar = $function( $this->primaVar, ...$args );
            return $this;
        };
        return $this;
    }


    /**
     * @throws Exception
     */
    public static function init($aliasName = '', $value = null, $type = null, $debug = false)
    {

        $calledClass = get_called_class();

        // If an instance already exists, return that instance
        if ( isset( self::$instances[$calledClass] ) ) {
            return self::$instances[$calledClass];
        }

        // Otherwise, create a new instance
        self::$instances[$calledClass] = new $calledClass( $value, $type, $debug );

        if ( $aliasName === '' && self::$instances[$calledClass]->aliasName === '' ) {
            throw new Exception( 'Alias name is empty' );
        } elseif ( $aliasName !== '' ) {
            self::$instances[$calledClass]->aliasName = $aliasName;  // Initialisation du nom de l'alias
        }


        define( __NAMESPACE__ . '\\' . $aliasName, self::use() );
        self::$USE = self::use();

        return self::$instances[$calledClass];
    }


    public static function instancesAction(): static
    {
        $calledClass = get_called_class();
        return self::$instances[$calledClass];
    }

    /**
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        // var_dump( $name, $arguments );
        // Vérifie si le nom commence par '_'
        if ( strpos( $name, '_' ) === 0 ) {
            // Convertit le reste du nom en camelCase pour l'adapter au format des méthodes d'instance
            $methodName = lcfirst( substr( $name, 1 ) );

            // Vérifiez si la méthode existe dans l'instance
            if ( method_exists( self::instancesAction(), $methodName ) ) {
                // Appelle la méthode de l'instance et renvoie le résultat
                return call_user_func_array( [ self::instancesAction(), $methodName ], $arguments );
            }
        }

        // Si la méthode n'est pas trouvée ou ne correspond pas au motif, lancez une exception ou gérez-le comme vous le souhaitez.
        throw new Exception( "Méthode statique {$name} not exist" );
    }

    public function __invoke(Closure $closure)
    {
        return $closure( $this );
    }

    /**
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        var_dump( $name, $arguments );
        if ( isset( $this->customFunctions[$name] ) ) {
            return call_user_func_array( $this->customFunctions[$name], $arguments );
        }

        throw new Exception( "$name : undefined" );
    }




    public function while_inf($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'inferior', false, $resultToPrimaVar );
    }

    public function while_infOrEq($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'inferiorOrEqual', false, $resultToPrimaVar );
    }

    public function while_sup($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'superior', false, $resultToPrimaVar );
    }

    public function while_supOrEq($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'superiorOrEqual', false, $resultToPrimaVar );
    }

    public function while_equal($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'equal', false, $resultToPrimaVar );
    }

    /*----------------------------------------------*/
    public function doWhile_inf($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'inferior', true, $resultToPrimaVar );
    }

    public function doWhile_infOrEq($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'inferiorOrEqual', true, $resultToPrimaVar );
    }

    public function doWhile_sup($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'superior', true, $resultToPrimaVar );
    }

    public function doWhile_supOrEq($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'superiorOrEqual', true, $resultToPrimaVar );
    }

    public function doWhile_equal($limit, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customWhile( $limit, $function, 'equal', true, $resultToPrimaVar );
    }

    /**
     * Execute a custom while or do-while loop based on the given condition.
     *
     * @param mixed $limit Value to compare against for the loop condition.
     * @param Closure $function The function to execute within the loop.
     * @param string $condition The loop condition (e.g., 'inferior', 'equal', etc.).
     * @param bool $isDoWhile Whether to execute a do-while loop instead of a while loop.
     * @param bool $resultToPrimaVar Whether to assign the result to $this->primaVar.
     *
     * @return static The current instance.
     * @throws InvalidArgumentException If an invalid condition is provided.
     */
    public function customWhile($limit, Closure $function, $condition = 'inferior', $isDoWhile = false, $resultToPrimaVar = false): static
    {
        $loopCondition = match ($condition) {
            'inferior' => fn($i) => $i < $limit,
            'inferiorOrEqual' => fn($i) => $i <= $limit,
            'superior' => fn($i) => $i > $limit,
            'superiorOrEqual' => fn($i) => $i >= $limit,
            'equal' => fn($i) => $i == $limit,
            default => throw new InvalidArgumentException( "Invalid condition: $condition" ),
        };

        if ( $isDoWhile ) {
            do {
                $this->resultPrimaVar = $function( $this->primaVar );
                $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage
            } while ($loopCondition( $this->primaVar ));

        } else {
            while ($loopCondition( $this->primaVar )) {
                $this->resultPrimaVar = $function( $this->primaVar );
                $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage
            }
        }

        if ( $resultToPrimaVar ) {
            $this->primaVar = $this->resultPrimaVar;
        }

        return $this;
    }

    /*----------------------------------------------*/
    /*
        //Utilisation de la méthode `map` pour doubler chaque élément.
        $processor->map(function ($num) {
            return $num * 2;
        }, true);

        // Utilisation de la méthode `filter` pour conserver uniquement les nombres pairs.
        $processor->filter(function ($num) {
            return $num % 2 == 0;
        }, true);

        // Utilisation de la méthode `reduce` pour obtenir la somme des éléments.
        $processor->reduce(function ($carry, $num) {
            return $carry + $num;
        }, 0, true);
     */
    public function walk(Closure $function): static
    {
        if (!is_array($this->primaVar)) {
            throw new InvalidArgumentException('The property primaVar must be an array.');
        }

        array_walk($this->primaVar, $function);
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage

        return $this;
    }

    public function filter(Closure $function, $resultToPrimaVar = true): static
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new InvalidArgumentException( 'The property primaVar must be an array.' );
        }

        $this->resultPrimaVar = array_filter( $this->primaVar, $function );
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage

        if ( $resultToPrimaVar ) $this->primaVar = $this->resultPrimaVar;


        return $this;
    }

    public function reduce(Closure $function, $initial = null, $resultToPrimaVar = true): static
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new InvalidArgumentException( 'The property primaVar must be an array.' );
        }

        $this->resultPrimaVar = array_reduce( $this->primaVar, $function, $initial );
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage

        if ( $resultToPrimaVar ) $this->primaVar = $this->resultPrimaVar;


        return $this;
    }


    public function map(Closure $function, $resultToPrimaVar = true): static
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new InvalidArgumentException( 'The property primaVar must be an array.' );
        }

        $this->resultPrimaVar = array_map( $function, $this->primaVar );
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage

        if ( $resultToPrimaVar ) $this->primaVar = $this->resultPrimaVar;

        return $this;
    }

    /*----------------------------------------------*/

    public function forEach(Closure $function, $resultToPrimaVar = true): static
    {
        if ( !is_array( $this->primaVar ) && !($this->primaVar instanceof Iterator) ) {
            throw new InvalidArgumentException( 'The property primaVar must be an array or an instance of Iterator.' );
        }

        foreach ($this->primaVar as $key => $value) {
            $this->resultPrimaVar = $function( $key, $value );
            $this->ifDebugMode(); // Capturez ce changement dans le tableau de débogage
        }

        if ( $resultToPrimaVar ) $this->primaVar = $this->resultPrimaVar;


        return $this;
    }

    public function for_inf($limit, $step, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customFor( $limit, $step, $function, 'inferior', $resultToPrimaVar );
    }

    public function for_infOrEq($limit, $step, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customFor( $limit, $step, $function, 'inferiorOrEqual', $resultToPrimaVar );
    }

    public function for_sup($limit, $step, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customFor( $limit, $step, $function, 'superior', $resultToPrimaVar );
    }

    public function for_supOrEqu($limit, $step, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customFor( $limit, $step, $function, 'superiorOrEqual', $resultToPrimaVar );
    }

    public function for_equal($limit, $step, Closure $function, $resultToPrimaVar = true): static
    {
        return $this->customFor( $limit, $step, $function, 'equal', $resultToPrimaVar );
    }

    public function customFor($limit, $step, Closure $function, $condition = 'inferior', $resultToPrimaVar = false): static
    {
        $loopCondition = match ($condition) {
            'inferior' => fn($i) => $i < $limit,
            'inferiorOrEqual' => fn($i) => $i <= $limit,
            'superior' => fn($i) => $i > $limit,
            'superiorOrEqual' => fn($i) => $i >= $limit,
            'equal' => fn($i) => $i == $limit,
            default => throw new InvalidArgumentException( "Invalid condition: $condition" ),
        };

        for ($i = $this->primaVar; $loopCondition( $i ); $i += $step) {
            $this->resultPrimaVar = $function( $this->primaVar );
            $this->ifDebugMode();
        }

        if ( $resultToPrimaVar ) $this->primaVar = $this->resultPrimaVar;

        return $this;
    }
 /*----------------------------------------------*/

    public function action(Closure $function): static
    {
        $this->primaVar = $function( $this->primaVar );
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage
        return $this; // retourne l'instance pour permettre le chaînage
    }

    public function set($value,$key=null): static
    {
        if($key !== null) {
            $this->primaVar[$key] = $value;
        } else {
            $this->primaVar = $value;
        }
        $this->ifDebugMode();  // Capturez ce changement dans le tableau de débogage
        return $this; // retourne l'instance pour permettre le chaînage
    }

    public function get($key=null)
    {
        if($key !== null) {
            return $this->primaVar[$key];
        }
        return $this->primaVar;
    }

    public function getTest()
    {
        return $this->testPrimaVar;
    }

    public function getResult()
    {
        return $this->resultPrimaVar;
    }

    public function end(): null
    {
        return null;
    }

    public function setType($type): static
    {
        $this->type = $type;
        $this->primaVar = $this->convertType( $this->primaVar, $type );
        $this->ifDebugMode(); // Ajoutez cette ligne
        return $this;
    }

    private function convertType($value, Types $type): mixed
    {
        return match ($type) {
            Types::BOOLEAN => (bool)$value,
            Types::INTEGER => (int)$value,
            Types::FLOAT => (float)$value,
            Types::STRING => (string)$value,
            Types::ARRAY => (array)$value,
            Types::OBJECT => (object)$value,
            Types::ITERABLE => is_array( $value ) ? $value : [],
            Types::NULL, Types::VOID => null,
            Types::FALSE => false,
            Types::INT_OR_STRING => is_numeric( $value ) ? (int)$value : (string)$value,
            Types::FLOAT_OR_INT => is_float( $value + 0 ) ? (float)$value : (int)$value,
            Types::ARRAY_OR_BOOL => is_array( $value ) ? $value : (bool)$value,
            default => $value,
        };
    }

    public function getDebugVar($echo = true): ?array
    {
        $className = get_called_class();
        if ( $echo ) {
            $implStr = '';
            foreach ($this->debugVar as $key => $val) {
                $exp = var_export( $val, true );
                $exp = str_replace(
                    [ 'array', '(', '=>', '\'', ',' . "\n)", ": \n ", '\Piko\Types::' ],
                    [ '', '', ':', '', PHP_EOL, ':', '' ],
                    $exp );
                $implStr .= '- STAGE ' . $key . PHP_EOL . str_repeat( '-', 50 ) . $exp . PHP_EOL;
            }
            echo PHP_EOL, 'debug mode for ', $className, '( ', $this->aliasName, ' ) { ', PHP_EOL, $implStr, '}', PHP_EOL;
        } else {
            return $this->debugVar;
        }
        return null;
    }

}