<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', '127.0.0.1');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4+$mIJ.NJ$Vzsn&(9W}SC5JXvA!D_=Yz(Pp9}6<X>(M[4kqL?kU/ZVLSyOLkx80U');
define('SECURE_AUTH_KEY',  'r@jG;[L-~3Aw)Vt^!U)559b>:)lkG>2C4vfjWNorbZ:Y$e(eD!Bfn|-Y57b8t-kR');
define('LOGGED_IN_KEY',    '43AN;oKZ5Hb.;Q7|Q]---RP*w~#,Oxq+90UdBX(6p_J^ldYF?%j[|!=UaL-wmCq7');
define('NONCE_KEY',        'HB7.Hxtp]8[nN~%tMem6-^86f-E6B/+h+KI>>;RxPf91KyQkj90IXS![`q4/`d5C');
define('AUTH_SALT',        'QXu6kM M$_~V%Wg%{STX5I0C9*l`.|l$.8&<90]19a/eRwY1ZP]IP|UMJ-zLppm,');
define('SECURE_AUTH_SALT', ']:O#m!m~IKt?#|OSSEv|j)lhn6+j(N-cQL+ZgLLnU4^aEf8d5L^joe!rWKX%eq99');
define('LOGGED_IN_SALT',   'DqK2d88`HC|~t}>R.n+~ikcMtC(AO@||he-y?Fbn8V#4,Hyew&1}boistWnZ$UY2');
define('NONCE_SALT',       '7cV=qr}j+>:fyr{0w;29<v]cNbG{hV&7-alo[2pywSByOb3j5/L-$p=B)0Ub n.W');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');