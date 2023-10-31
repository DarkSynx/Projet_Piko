<?php
//declare(strict_types=1); // Activez le mode strict pour le typage.
namespace Piko;

require_once __DIR__ . '/vendor/autoload.php';

use Piko\Def;
use Piko\Types;

/*
// Création d'une nouvelle instance avec l'alias "ABC"
Def::init(
    'ABC',
   10,
    Types::INTEGER,
    true
);      // permet l'initialisation d'un objet ici ABC


ABC::_Set(22);
ABC::_SetType(Types::STRING);
ABC::it()(function() {
    echo "test", PHP_EOL;
    // Si vous voulez utiliser des méthodes ou des propriétés de l'instance de Primar:
    // $instance->someMethod();
});

var_dump(ABC::_Get());
ABC::$it->getDebugvar();
// création de liste facile
// création de boucle et d'analyse de liste
*/
Def::init('SessionManagerUser', null, Types::SESSION, true)->session_start();

// Pour enregistrer des informations dans la session :
SessionManagerUser::Exploit()->session_user('username','ChatGPT');

// Pour obtenir des informations de la session :
$username = SessionManagerUser::_Get();
echo '[' , $username, ']', PHP_EOL;  // Devrait afficher: ChatGPT

// Pour vérifier si une clé existe dans la session :
$test = SessionManagerUser::_Session_has('username')->getTest();
var_dump($test);
if($test) {
    echo "L'utilisateur est connecté !", PHP_EOL;
}

// Supprimer une clé de la session :
SessionManagerUser::use()->session_unset('username');

$test = SessionManagerUser::use()->session_has('username')->getTest();
var_dump($test);
if(!$test) {
    echo "L'utilisateur n'est plus connecté !", PHP_EOL;
}
//SessionManagerUser::_GetDebugvar(); // ou SessionManagerUser::use()->getDebugvar();

echo '-------------------', PHP_EOL;
echo SessionManagerUser::use()->get(); // methode normal
echo SessionManagerUser::_Get(); // methode static
echo SessionManagerUser::$USE->get(); // variable static
echo SessionManagerUser->get(); // constante de namespace

// Détruire la session complètement :
SessionManagerUser::$USE->session_destroy();
