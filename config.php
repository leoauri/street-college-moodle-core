<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = getenv('MOODLE_DOCKER_DBTYPE');
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'db';
$CFG->dbname    = getenv('MOODLE_DOCKER_DBNAME');
$CFG->dbuser    = getenv('MOODLE_DOCKER_DBUSER');
$CFG->dbpass    = getenv('MOODLE_DOCKER_DBPASS');
$CFG->prefix    = 'm_';
$CFG->dboptions = ['dbcollation' => getenv('MOODLE_DOCKER_DBCOLLATION')];

$host = 'localhost';
if (!empty(getenv('MOODLE_DOCKER_WEB_HOST'))) {
    $host = getenv('MOODLE_DOCKER_WEB_HOST');
}
$protocol = 'http';
if (!empty(getenv('MOODLE_DOCKER_WEB_PROTOCOL'))) {
    $protocol = getenv('MOODLE_DOCKER_WEB_PROTOCOL');
}
$CFG->wwwroot = "{$protocol}://{$host}";

$port = getenv('MOODLE_DOCKER_WEB_PORT');

// quick and hacky way to run SSL, should probably clean up
if (getenv('MOODLE_DOCKER_SSL')) {
    $port = '443';
    $CFG->sslproxy = true;
    $CFG->overridetossl = true;
}

if (!empty($port)) {
    $CFG->wwwroot .= ":{$port}";
}

$CFG->dataroot  = '/var/www/moodledata';
$CFG->admin     = 'admin';
$CFG->directorypermissions = 0777;

if (empty(getenv('MOODLE_DOCKER_SMTP_HOST'))) {
    $CFG->smtphosts = 'mailhog:1025';
} else {
    $CFG->smtphosts = getenv('MOODLE_DOCKER_SMTP_HOST');

    if(!empty(getenv('MOODLE_DOCKER_SMTP_SECURITY'))) {
        $CFG->smtpsecure = getenv('MOODLE_DOCKER_SMTP_SECURITY');
    }

    if(!empty(getenv('MOODLE_DOCKER_SMTP_AUTHTYPE'))) {
        $CFG->smtpauthtype = getenv('MOODLE_DOCKER_SMTP_AUTHTYPE');
    }

    if(!empty(getenv('MOODLE_DOCKER_SMTP_USER'))) {
        $CFG->smtpuser = getenv('MOODLE_DOCKER_SMTP_USER');
    }

    if(!empty(getenv('MOODLE_DOCKER_SMTP_PASS'))) {
        $CFG->smtppass = getenv('MOODLE_DOCKER_SMTP_PASS');
    }
}

if (false) {
    // Debug options - possible to be controlled by flag in future..
    $CFG->debug = (E_ALL | E_STRICT); // DEBUG_DEVELOPER
    $CFG->debugdisplay = 1;
    $CFG->debugstringids = 1; // Add strings=1 to url to get string ids.
    $CFG->perfdebug = 15;
    $CFG->debugpageinfo = 1;
    
    $CFG->passwordpolicy = 0;
    
    $CFG->phpunit_dataroot  = '/var/www/phpunitdata';
    $CFG->phpunit_prefix = 't_';
    define('TEST_EXTERNAL_FILES_HTTP_URL', 'http://exttests');
    
    $CFG->behat_wwwroot   = 'http://webserver';
    $CFG->behat_dataroot  = '/var/www/behatdata';
    $CFG->behat_prefix = 'b_';
    $CFG->behat_profiles = array(
        'default' => array(
            'browser' => getenv('MOODLE_DOCKER_BROWSER'),
            'wd_host' => 'http://selenium:4444/wd/hub',
        ),
    );
    $CFG->behat_faildump_path = '/var/www/behatfaildumps';
    
    define('PHPUNIT_LONGTEST', true);
    
    if (getenv('MOODLE_DOCKER_PHPUNIT_EXTRAS')) {
        define('TEST_SEARCH_SOLR_HOSTNAME', 'solr');
        define('TEST_SEARCH_SOLR_INDEXNAME', 'test');
        define('TEST_SEARCH_SOLR_PORT', 8983);
    
        define('TEST_SESSION_REDIS_HOST', 'redis');
        define('TEST_CACHESTORE_REDIS_TESTSERVERS', 'redis');
    
        define('TEST_CACHESTORE_MONGODB_TESTSERVER', 'mongodb://mongo:27017');
    
        define('TEST_CACHESTORE_MEMCACHED_TESTSERVERS', "memcached0:11211\nmemcached1:11211");
        define('TEST_CACHESTORE_MEMCACHE_TESTSERVERS', "memcached0:11211\nmemcached1:11211");
    
        define('TEST_LDAPLIB_HOST_URL', 'ldap://ldap');
        define('TEST_LDAPLIB_BIND_DN', 'cn=admin,dc=openstack,dc=org');
        define('TEST_LDAPLIB_BIND_PW', 'password');
        define('TEST_LDAPLIB_DOMAIN', 'ou=Users,dc=openstack,dc=org');
    
        define('TEST_AUTH_LDAP_HOST_URL', 'ldap://ldap');
        define('TEST_AUTH_LDAP_BIND_DN', 'cn=admin,dc=openstack,dc=org');
        define('TEST_AUTH_LDAP_BIND_PW', 'password');
        define('TEST_AUTH_LDAP_DOMAIN', 'ou=Users,dc=openstack,dc=org');
    
        define('TEST_ENROL_LDAP_HOST_URL', 'ldap://ldap');
        define('TEST_ENROL_LDAP_BIND_DN', 'cn=admin,dc=openstack,dc=org');
        define('TEST_ENROL_LDAP_BIND_PW', 'password');
        define('TEST_ENROL_LDAP_DOMAIN', 'ou=Users,dc=openstack,dc=org');
    }
}


// Hardwire Street College theme settings
$CFG->theme = 'street_college';
$CFG->themelist = 'street_college';
$CFG->allowthemechangeonurl = false;

// Set German default
$CFG->lang = 'de';

// Remove courses from frontpage
$CFG->frontpage = '';

// Trim user menu
$CFG->customusermenuitems = "preferences,moodle|/user/preferences.php|t/preferences";

// Hardwire dashboard blocks
$CFG->forcedefaultmymoodle = true;

// Default course blocks
$CFG->defaultblocks_override = 'course_participants';

$CFG->forced_plugin_settings = [
    'moodlecourse' => [
        // Unset default end date for courses
        'courseenddateenabled' => false,
        'numsections' => 0
    ]
];

// Default hide email address
$CFG->defaultpreference_maildisplay = 0;

// Locale settings
$CFG->timezone = 'Europe/Berlin';
$CFG->country = 'DE';
$CFG->defaultcity = 'Berlin';

// Disable components we're not using
$CFG->messaging = false;
$CFG->enableblogs = false;
$CFG->enablebadges = false;
$CFG->enableavailability = false;


require_once(__DIR__ . '/lib/setup.php');
