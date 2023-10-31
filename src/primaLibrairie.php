<?php

namespace Piko;

use DateTime;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOStatement;

class primaLibrairie
{
    protected static array $instances = [];
    protected string $aliasName = '';
    protected mixed $primaVar;
    protected mixed $type;
    protected array $debugVar = [];
    protected bool $debugMode = false;
    protected array $customFunctions = [];
    protected string $messageDebug = "";
    protected int $startTimeAnalyse = 0;
    protected int $endTimeAnalyse = 0;
    protected int $difTimeAnalyse = 0;
    protected mixed $testPrimaVar = 'N/A'; // Résultat des tests effectués sur $this->primaVar
    protected mixed $resultPrimaVar = 'N/A';
    protected mixed $pdo;

    /****************************************************/
    /** BASE */

    // Supposons que vous avez déjà une méthode 'ifDebugMode' et une constante 'Types : : INTEGER' définie quelque part

    public function base_echo(): object
    {
        echo $this->primaVar;
        return $this->ifDebugMode();
    }

    protected function ifDebugMode(): static
    {
        if ( $this->debugMode ) {
            // Capturez la trace de la pile
            $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
            $caller = $backtrace[1] ?? null;

            $file = $caller['file'] ?? 'unknown';
            $line = $caller['line'] ?? 'unknown';

            // Capturez la date et l'heure actuelle
            $dateTime = new DateTime();
            $formattedDateTime = $dateTime->format( 'Y-m-d H:i:s.u' );


            // Ajoutez les informations au tableau debugvar
            $this->debugVar[] = [
                'msgDebug' => $this->messageDebug,
                'timestamp' => $formattedDateTime,
                'function' => $caller['function'] ?? 'unknown',
                'val' => $this->primaVar,
                'length' => strlen( (string)$this->primaVar ),
                'type' => $this->type,
                '------' => '-------',
                'prev_val' => isset( $this->debugVar[$this->last_key( $this->debugVar )] ) ? $this->debugVar[$this->last_key( $this->debugVar )]['val'] : 'N/A', // Vous devrez définir une fonction last_key pour cela ou utiliser une autre méthode pour obtenir la dernière clé.
                'prev_type' => isset( $this->debugVar[$this->last_key( $this->debugVar )] ) ? $this->debugVar[$this->last_key( $this->debugVar )]['type'] : 'N/A', // Vous devrez définir une fonction last_key pour cela ou utiliser une autre méthode pour obtenir la dernière clé.
                'prev_length' => isset( $this->debugVar[$this->last_key( $this->debugVar )] ) ? $this->debugVar[$this->last_key( $this->debugVar )]['length'] : 'N/A', // Vous devrez définir une fonction last_key pour cela ou utiliser une autre méthode pour obtenir la dernière clé.
                '-------' => '-------',
                'test' => $this->testPrimaVar,
                'prev_test' => isset( $this->debugVar[$this->last_key( $this->debugVar )] ) ? $this->debugVar[$this->last_key( $this->debugVar )]['test'] : 'N/A', // Vous devrez définir une fonction last_key pour cela ou utiliser une autre méthode pour obtenir la dernière clé.
                'result' => $this->resultPrimaVar,
                'prev_resultPrimaVar' => isset( $this->debugVar[$this->last_key( $this->debugVar )] ) ? $this->debugVar[$this->last_key( $this->debugVar )]['result'] : 'N/A', // Vous devrez définir une fonction last_key pour cela ou utiliser une autre méthode pour obtenir la dernière clé.
                '--------' => '-------',
                'file' => $file,
                'line' => $line,
                'startTimeAnalyse' => $this->startTimeAnalyse,
                'endTimeAnalyse' => $this->endTimeAnalyse,
                'duration' => $this->difTimeAnalyse
            ];
        }
        return $this;
    }

    protected function last_key(array $array)
    {
        if ( empty( $array ) ) {
            return null;
        }

        return array_keys( $array )[count( $array ) - 1];
    }

    public function base_print(): object
    {
        print $this->primaVar;
        return $this->ifDebugMode();
    }

    #[NoReturn] public function base_die(): void
    {
        die( $this->primaVar );
    }

    #[NoReturn] public function base_exit(): void
    {
        exit( $this->primaVar );
    }

    public function base_isset(): object
    {
        $this->testPrimaVar = isset( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_empty(): object
    {
        $this->testPrimaVar = empty( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_gettype(): object
    {
        $this->testPrimaVar = gettype( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_setType(string $type): object
    {
        settype( $this->primaVar, $type );
        return $this->ifDebugMode();
    }

    public function base_isArray(): object
    {
        $this->testPrimaVar = is_array( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_isBool(): object
    {
        $this->testPrimaVar = is_bool( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_isFloat(): object
    {
        $this->testPrimaVar = is_float( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_isInt(): object
    {
        $this->testPrimaVar = is_int( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_isNull(): object
    {
        $this->testPrimaVar = is_null( $this->primaVar );
        return $this->ifDebugMode();
    }

    /****************************************************/

    public function base_isString(): object
    {
        $this->testPrimaVar = is_string( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function base_unSet(): object
    {
        unset( $this->primaVar );
        return $this->ifDebugMode();
    }

    /** str_
     * @throws Exception
     */
    public function str_trim(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_trim' => type STRING" );
        }

        $this->primaVar = trim( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_lTrim(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_ltrim' => type STRING" );
        }

        $this->primaVar = ltrim( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_rTrim(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_rtrim' => type STRING" );
        }

        $this->primaVar = rtrim( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function str_len(): object
    {
        $this->testPrimaVar = strlen( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function str_pos($needle): object
    {
        $this->testPrimaVar = strpos( $this->primaVar, $needle );
        return $this->ifDebugMode();
    }

    public function str_rPos($needle): object
    {
        $this->testPrimaVar = strrpos( $this->primaVar, $needle );
        return $this->ifDebugMode();
    }

    public function str_str($needle): object
    {
        $this->testPrimaVar = strstr( $this->primaVar, $needle );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_replace($search, $replace): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_replace' => type STRING" );
        }

        $this->primaVar = str_replace( $search, $replace, $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_substr($start, $length = null): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_substr' => type STRING" );
        }

        $this->primaVar = is_null( $length ) ? substr( $this->primaVar, $start ) : substr( $this->primaVar, $start, $length );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_toUpper(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_toUpper' => type STRING" );
        }

        $this->primaVar = strtoupper( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_toLower(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_toLower' => type STRING" );
        }

        $this->primaVar = strtolower( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_ucFirst(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_ucFirst' => type STRING" );
        }

        $this->primaVar = ucfirst( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_ucWords(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_ucWords' => type STRING" );
        }

        $this->primaVar = ucwords( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_rev(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_rev' => type STRING" );
        }

        $this->primaVar = strrev( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_htmlEntities(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_htmlEntities' => type STRING" );
        }

        $this->primaVar = htmlentities( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_htmlSpecialChars(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_htmlSpecialChars' => type STRING" );
        }

        $this->primaVar = htmlspecialchars( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_addSlashes(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_addSlashes' => type STRING" );
        }

        $this->primaVar = addslashes( $this->primaVar );
        return $this->ifDebugMode();
    }

    /***********************************************/

    /**
     * @throws Exception
     */
    public function str_ipSlashes(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_ipSlashes' => type STRING" );
        }

        $this->primaVar = stripslashes( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function str_nl2br(): object
    {
        if ( gettype( $this->primaVar ) !== 'string' ) {
            throw new Exception( "'str_nl2br' => type STRING" );
        }

        $this->primaVar = nl2br( $this->primaVar );
        return $this->ifDebugMode();
    }

    /** array_
     * @throws Exception
     */
    public function array_push($value): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_push' => Array" );
        }
        $this->primaVar[] = $value;
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_pop(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_pop' => Array" );
        }
        $this->testPrimaVar = array_pop( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_shift(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_shift' => Array" );
        }
        $this->testPrimaVar = array_shift( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_unShift($value): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_unshift' => Array" );
        }
        array_unshift( $this->primaVar, $value );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_merge($arraysToMerge): object
    {
        if ( !is_array( $this->primaVar ) || !is_array( $arraysToMerge ) ) {
            throw new Exception( "'array_merge' => Array" );
        }
        $this->primaVar = array_merge( $this->primaVar, $arraysToMerge );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_combine($keys, $values): object
    {
        if ( !is_array( $this->primaVar ) || !is_array( $keys ) || !is_array( $values ) ) {
            throw new Exception( "'array_combine' => Array" );
        }
        $this->primaVar = array_combine( $keys, $values );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_map($callback): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_map' => Array" );
        }
        $this->primaVar = array_map( $callback, $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_filter(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_filter' => Array" );
        }
        $this->primaVar = array_filter( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_reduce($callback, $initial = null): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_reduce' => Array" );
        }
        $this->testPrimaVar = array_reduce( $this->primaVar, $callback, $initial );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_inArray($needle): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'in_array' => Array" );
        }
        $this->testPrimaVar = in_array( $needle, $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_search($needle): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_search' => Array" );
        }
        $this->testPrimaVar = array_search( $needle, $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_keyExists($key): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_key_exists' => Array" );
        }
        $this->testPrimaVar = array_key_exists( $key, $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_flip(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_flip' => Array" );
        }
        $this->primaVar = array_flip( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_reverse(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_reverse' => Array" );
        }
        $this->primaVar = array_reverse( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_sort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'sort' => Array" );
        }
        sort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_rsort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'rsort' => Array" );
        }
        rsort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_aSort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'aSort' => Array" );
        }
        asort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_arSort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'arSort' => Array" );
        }
        arsort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_kSort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'kSort' => Array" );
        }
        ksort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_krSort(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'krSort' => Array" );
        }
        krsort( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_count(): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'count' => Array" );
        }
        $this->testPrimaVar = count( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_slice($offset, $length = null): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_slice' => Array" );
        }
        $this->primaVar = array_slice( $this->primaVar, $offset, $length );
        return $this->ifDebugMode();
    }
    /****************************************************/

    /**
     * @throws Exception
     */
    public function array_splice($offset, $length = null, $replacement = array()): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_splice' => Array" );
        }
        array_splice( $this->primaVar, $offset, $length, $replacement );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function array_rand($num = 1): object
    {
        if ( !is_array( $this->primaVar ) ) {
            throw new Exception( "'array_rand' => Array" );
        }
        $this->testPrimaVar = array_rand( $this->primaVar, $num );
        return $this->ifDebugMode();
    }

    /** date_
     * @throws Exception
     */
    public function date(): object
    {
        // Assuming $this->primarvar is expected to hold a string in date format
        if ( $this->type != Types::STRING ) {
            throw new Exception( "'date_date' => type STRING" );
        }

        $this->primaVar = date( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function date_strToTime(): object
    {
        if ( $this->type != Types::STRING ) {
            throw new Exception( "'date_strToTime' => type STRING" );
        }

        $this->testPrimaVar = strtotime( $this->primaVar );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function date_mkTime(): object
    {
        if ( $this->type != Types::STRING ) {
            throw new Exception( "'date_mkTime' => type STRING" );
        }

        // Assuming $this->primaVar is a comma-separated string like "hour,minute,second,month,day,year"
        $time_parts = explode( ',', $this->primaVar );
        $this->testPrimaVar = mktime( ...$time_parts );
        return $this->ifDebugMode();
    }

    /****************************************/

    public function date_time(): object
    {
        $this->testPrimaVar = time();
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function date_getDate(): object
    {
        if ( $this->type != Types::INTEGER ) {
            throw new Exception( "'date_getDate' => type INTEGER" );
        }

        $this->testPrimaVar = getdate( $this->primaVar );
        return $this->ifDebugMode();
    }

    /** file_
     * @throws Exception
     */
    public function file_open(): object
    {
        if ( $this->type != Types::STRING ) {
            throw new Exception( "'file_open' => type STRING" );
        }

        $this->testPrimaVar = fopen( $this->primaVar, 'r' );
        return $this->ifDebugMode();
    }

    public function file_close(): object
    {
        fclose( $this->testPrimaVar );
        return $this->ifDebugMode();
    }

    public function file_eof(): object
    {
        $this->testPrimaVar = feof( $this->testPrimaVar );
        return $this->ifDebugMode();
    }

    public function file_read(): object
    {
        $this->testPrimaVar = fread( $this->testPrimaVar, 8192 ); // read up to 8KB, adjust as needed
        return $this->ifDebugMode();
    }

    public function file_write($data): object
    {
        fwrite( $this->testPrimaVar, $data );
        return $this->ifDebugMode();
    }

    public function file_getContents(): object
    {
        $this->testPrimaVar = file_get_contents( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function file_putContents($data): object
    {
        file_put_contents( $this->primaVar, $data );
        return $this->ifDebugMode();
    }

    public function file_moveUploadedFile($destination): object
    {
        move_uploaded_file( $this->primaVar, $destination );
        return $this->ifDebugMode();
    }

    public function file_copy($destination): object
    {
        copy( $this->primaVar, $destination );
        return $this->ifDebugMode();
    }

    public function file_rename($newName): object
    {
        rename( $this->primaVar, $newName );
        return $this->ifDebugMode();
    }

    public function file_unlink(): object
    {
        unlink( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function file_isFile(): object
    {
        $this->testPrimaVar = is_file( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function file_isDir(): object
    {
        $this->testPrimaVar = is_dir( $this->primaVar );
        return $this->ifDebugMode();
    }

    /****************************************/

    public function file_mkDir(): object
    {
        mkdir( $this->primaVar );
        return $this->ifDebugMode();
    }

    public function file_rmDir(): object
    {
        rmdir( $this->primaVar );
        return $this->ifDebugMode();
    }

    /** session_
     * @throws Exception
     */
    public function session_has($key): object
    {
        if ( $this->type !== Types::SESSION ) {
            throw new Exception( "'session_has' => type SESSION" );
        }
        $this->testPrimaVar = (!empty( $_SESSION[$key]));
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function session_user($key, $name): object
    {
        if ( $this->type !== Types::SESSION ) {
            throw new Exception( "'session_has' => type SESSION" );
        }

        $_SESSION[$key] = $name;
        $this->primaVar =  $_SESSION[$key];

        return $this->ifDebugMode();
    }

    public function session_start(): object
    {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
        return $this->ifDebugMode();
    }

    public function session_destroy(): object
    {
        if ( session_status() !== PHP_SESSION_NONE ) {
            session_destroy();
        }
        return $this->ifDebugMode();
    }

    public function session_id(?string $id = null): object
    {
        if ( $id !== null ) {
            session_id( $id );
        } else {
            $this->primaVar = session_id();
        }
        return $this->ifDebugMode();
    }

    public function session_name(?string $name = null): object
    {
        if ( $name !== null ) {
            session_name( $name );
        } else {
            $this->primaVar = session_name();
        }
        return $this->ifDebugMode();
    }

    public function session_unset(): object
    {
        session_unset();
        return $this->ifDebugMode();
    }

    public function session_regenerateId(bool $deleteOldSession = false): object
    {
        session_regenerate_id( $deleteOldSession );
        return $this->ifDebugMode();
    }

    public function session_encode(): object
    {
        $this->primaVar = session_encode();
        return $this->ifDebugMode();
    }

    /****************************************/

    public function session_decode(string $data): object
    {
        session_decode( $data );
        return $this->ifDebugMode();
    }

    public function session_savePath(?string $path = null): object
    {
        if ( $path !== null ) {
            session_save_path( $path );
        } else {
            $this->primaVar = session_save_path();
        }
        return $this->ifDebugMode();
    }

    /** io_ */
    public function io_filterInput(int $type, string $variable_name, int $filter = FILTER_DEFAULT, $options = NULL): object
    {
        $this->primaVar = filter_input( $type, $variable_name, $filter, $options );
        return $this->ifDebugMode();
    }

    public function io_filterVar($variable, int $filter = FILTER_DEFAULT, $options = NULL): object
    {
        $this->primaVar = filter_var( $variable, $filter, $options );
        return $this->ifDebugMode();
    }

    public function io_filterInputArray(int $type, $definition = NULL, bool $add_empty = true): object
    {
        $this->primaVar = filter_input_array( $type, $definition, $add_empty );
        return $this->ifDebugMode();
    }

    public function io_filterVarArray(array $data, $definition = NULL, bool $add_empty = true): object
    {
        $this->primaVar = filter_var_array( $data, $definition, $add_empty );
        return $this->ifDebugMode();
    }

    public function io_header(string $string, bool $replace = true, int $http_response_code = 0): object
    {
        header( $string, $replace, $http_response_code );
        return $this->ifDebugMode();
    }

    public function io_setCookie(string $name, string $value = "", int $expires = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = false): object
    {
        setcookie( $name, $value, $expires, $path, $domain, $secure, $httponly );
        return $this->ifDebugMode();
    }

    public function io_parseUrl(string $url, int $component = -1): object
    {
        $this->primaVar = parse_url( $url, $component );
        return $this->ifDebugMode();
    }

    public function io_urlEncode(string $str): object
    {
        $this->primaVar = urlencode( $str );
        return $this->ifDebugMode();
    }

    public function io_urlDecode(string $str): object
    {
        $this->primaVar = urldecode( $str );
        return $this->ifDebugMode();
    }

    /****************************************/

    public function io_httpBuildQuery($query_data, string $numeric_prefix = '', string $arg_separator = '', int $enc_type = PHP_QUERY_RFC1738): object
    {
        $this->primaVar = http_build_query( $query_data, $numeric_prefix, $arg_separator, $enc_type );
        return $this->ifDebugMode();
    }

    public function io_parseStr(string $encoded_string, array &$result = null): object
    {
        parse_str( $encoded_string, $result );
        $this->primaVar = $result;
        return $this->ifDebugMode();
    }

    /** math_ */
    public function math_abs($number): object
    {
        $this->primaVar = abs( $number );
        return $this->ifDebugMode();
    }

    public function math_ceil($number): object
    {
        $this->primaVar = ceil( $number );
        return $this->ifDebugMode();
    }

    public function math_floor($number): object
    {
        $this->primaVar = floor( $number );
        return $this->ifDebugMode();
    }

    public function math_round($number, $precision = 0, $mode = PHP_ROUND_HALF_UP): object
    {
        $this->primaVar = round( $number, $precision, $mode );
        return $this->ifDebugMode();
    }

    public function math_rand(int $min = 0, int $max = PHP_INT_MAX): object
    {
        $this->primaVar = rand( $min, $max );
        return $this->ifDebugMode();
    }

    public function math_mtRand(int $min = 0, int $max = PHP_INT_MAX): object
    {
        $this->primaVar = mt_rand( $min, $max );
        return $this->ifDebugMode();
    }

    public function math_max(...$values): object
    {
        $this->primaVar = max( ...$values );
        return $this->ifDebugMode();
    }

    public function math_min(...$values): object
    {
        $this->primaVar = min( ...$values );
        return $this->ifDebugMode();
    }

    /****************************************/

    public function math_sqrt($number): object
    {
        $this->primaVar = sqrt( $number );
        return $this->ifDebugMode();
    }

    public function math_pow($base, $exp): object
    {
        $this->primaVar = pow( $base, $exp );
        return $this->ifDebugMode();
    }

    /** db_ */
    public function db_newPDO(string $dsn, string $username = null, string $password = null, array $options = []): object
    {
        $this->pdo = new PDO( $dsn, $username, $password, $options );
        return $this->ifDebugMode();
    }

    public function db_prepare(string $statement): object
    {
        $this->primaVar = $this->pdo->prepare( $statement );
        return $this->ifDebugMode();
    }

    public function db_query(string $statement): object
    {
        $this->primaVar = $this->pdo->query( $statement );
        return $this->ifDebugMode();
    }

    public function db_exec(string $statement): object
    {
        $this->primaVar = $this->pdo->exec( $statement );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function db_execute(array $input_parameters = null): object
    {
        if ( !($this->primaVar instanceof PDOStatement) ) {
            throw new Exception( "prep request => 'execute'" );
        }
        $this->primaVar->execute( $input_parameters );
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function db_fetch(int $fetch_style = PDO::FETCH_ASSOC): array
    {
        if ( !($this->primaVar instanceof PDOStatement) ) {
            throw new Exception( "prep & exec request => 'fetch'" );
        }
        return $this->primaVar->fetch( $fetch_style );
    }

    /**
     * @throws Exception
     */
    public function db_fetchAll(int $fetch_style = PDO::FETCH_ASSOC): array
    {
        if ( !($this->primaVar instanceof PDOStatement) ) {
            throw new Exception( "prep & exec request => 'fetchAll'" );
        }
        return $this->primaVar->fetchAll( $fetch_style );
    }

    /**
     * @throws Exception
     */
    public function db_rowCount(): int
    {
        if ( !($this->primaVar instanceof PDOStatement) ) {
            throw new Exception( "prep & exec request => 'rowCount'" );
        }
        return $this->primaVar->rowCount();
    }

    public function db_lastInsertId(string $name = null): string
    {
        return $this->pdo->lastInsertId( $name );
    }

    public function db_beginTransaction(): object
    {
        $this->pdo->beginTransaction();
        return $this->ifDebugMode();
    }

    /****************************************/

    public function db_commit(): object
    {
        $this->pdo->commit();
        return $this->ifDebugMode();
    }

    public function db_rollback(): object
    {
        $this->pdo->rollback();
        return $this->ifDebugMode();
    }

    /** misc_ */
    public function misc_pregMatch(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0): object
    {
        $this->primaVar = preg_match( $pattern, $subject, $matches, $flags, $offset );
        return $this->ifDebugMode();
    }

    public function misc_pregReplace($pattern, $replacement, $subject, int $limit = -1, int &$count = null): object
    {
        $this->primaVar = preg_replace( $pattern, $replacement, $subject, $limit, $count );
        return $this->ifDebugMode();
    }

    public function misc_pregSplit(string $pattern, string $subject, int $limit = -1, int $flags = 0): object
    {
        $this->primaVar = preg_split( $pattern, $subject, $limit, $flags );
        return $this->ifDebugMode();
    }

    public function misc_jsonEncode($value, int $options = 0, int $depth = 512): object
    {
        $this->primaVar = json_encode( $value, $options, $depth );
        return $this->ifDebugMode();
    }

    public function misc_jsonDecode(string $json, bool $assoc = false, int $depth = 512, int $options = 0): object
    {
        $this->primaVar = json_decode( $json, $assoc, $depth, $options );
        return $this->ifDebugMode();
    }

    public function misc_base64Encode(string $data): object
    {
        $this->primaVar = base64_encode( $data );
        return $this->ifDebugMode();
    }

    public function misc_base64Decode(string $data, bool $strict = false): object
    {
        $this->primaVar = base64_decode( $data, $strict );
        return $this->ifDebugMode();
    }

    public function misc_serialize($value): object
    {
        $this->primaVar = serialize( $value );
        return $this->ifDebugMode();
    }

    public function misc_unSerialize(string $str, array $options = []): object
    {
        $this->primaVar = unserialize( $str, $options );
        return $this->ifDebugMode();
    }

    public function misc_md5(string $str, bool $raw_output = false): object
    {
        $this->primaVar = md5( $str, $raw_output );
        return $this->ifDebugMode();
    }

    public function misc_sha1(string $str, bool $raw_output = false): object
    {
        $this->primaVar = sha1( $str, $raw_output );
        return $this->ifDebugMode();
    }

    public function misc_passwordHash(string $password, int $algo, array $options = []): object
    {
        $this->primaVar = password_hash( $password, $algo, $options );
        return $this->ifDebugMode();
    }

    public function misc_passwordVerify(string $password, string $hash): object
    {
        $this->primaVar = password_verify( $password, $hash );
        return $this->ifDebugMode();
    }

    public function misc_crypt(string $str, string $salt): object
    {
        $this->primaVar = crypt( $str, $salt );
        return $this->ifDebugMode();
    }
    /****************************************/


    /****************************************************/

    public function misc_varDump($expression): void
    {
        var_dump( $expression );
    }

    public function misc_printR($expression, bool $return = false): void
    {
        if ( $return ) {
            $this->primaVar = print_r( $expression, true );
        } else {
            print_r( $expression );
        }
    }

    public function messageDebug($messageDebug): object
    {
        $this->messageDebug = $messageDebug;
        return $this->ifDebugMode();
    }

    public function startTimeAnalyse($message = "startTimeAnalyse"): object
    {
        if ( $message ) $this->messageDebug = $message;
        $this->startTimeAnalyse = microtime( true );
        return $this->ifDebugMode();
    }

    public function endTimeAnalyse($message = "endTimeAnalyse"): object
    {
        if ( $message ) $this->messageDebug = $message;
        $this->endTimeAnalyse = microtime( true );
        $this->difTimeAnalyse = ($this->endTimeAnalyse - $this->startTimeAnalyse);
        return $this->ifDebugMode();
    }

    /**
     * @throws Exception
     */
    public function inc(): object
    {
        if ( $this->type !== Types::INTEGER ) {
            throw new Exception( "'inc' => type INTEGER" );
        }

        $this->primaVar++;
        return $this->ifDebugMode();
    }


    // ... Vous pouvez ajouter d'autres méthodes spécifiques à votre librairie ici.
}