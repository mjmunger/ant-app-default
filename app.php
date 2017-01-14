<?php

namespace PHPAnt\Apps;

/**
 * App Name: Default Grammar
 * App Description: Provides the default grammar for commands in the CLI.
 * App Version: 1.0
 * App Action: cli-load-grammar -> loadDefaultGrammar @ 50
 * App Action: cli-init         -> declareMySelf      @ 50
 * App Action: cli-command      -> processCommand     @ 50
 */

 /**
 * This plugin adds the default grammar and commands into the CLI by adding in
 * the grammar for commands into an array, and returning it up the chain.
 *
 * @package      BFW
 * @subpackage   Plugins
 * @category     Default Commands
 * @author       Michael Munger <michael@highpoweredhelp.com>
 */


class DefaultGrammar extends \PHPAnt\Core\AntApp implements \PHPAnt\Core\AppInterface  {

    /**
     * Instantiates an instance of the DefaultGrammar class.
     * Example:
     *
     * <code>
     * $pluginDefaultGrammar = new DefaultGrammar();
     * </code>
     *
     * @return void
     * @author Michael Munger <michael@highpoweredhelp.com>
     **/

    function __construct() {
        $this->appName = 'Default Grammar';
        $this->canReload = false;
        $this->path = __DIR__;
    }

    /**
     * Callback for the cli-load-grammar action, which adds commands specific to this plugin to the CLI grammar.
     * Example:
     *
     * <code>
     * $pluginDefaultGrammar->addHook('cli-load-grammar','loadDefaultGrammar');
     * </code>
     *
     * @return array An array of CLI grammar that will be merged with the rest of the grammar.
     * @author Michael Munger <michael@highpoweredhelp.com>
     **/

    function loadDefaultGrammar() {
        $grammar = [];
        $grammar = ['errors' => [ 'hide' => NULL
                                , 'show' => NULL
                                ]
                    ];

        $grammar['set'] = ['verbosity' => NULL
                          ,'debug' => ['on' => NULL
                                      ,'off' => NULL
                                      ]
                          ,'visualtrace' => ['on' => NULL
                                            ,'off' => NULL
                                            ]
                          ];
        $grammar['show'] = ['debug' => ['environment' => ['dump' => ['grammar'   => NULL
                                                                    ,'appengine' => NULL
                                                                    ,'plugins'   => NULL
                                                                    ]
                                                         ,
                                                         ]
                                       ,
                                       ]
                           ,'verbosity' => NULL
                           ,'warranty'  => NULL
                           ];
        $this->loaded = true;

        $results['grammar'] = $grammar;
        $results['success'] = true;
        return $results;
    }

    /**
     * Callback function that prints to the CLI during cli-init to show this plugin has loaded.
     * Example:
     *
     * <code>
     * $pluginDefaultGrammar->addHook('cli-init','declareMySelf');
     * </code>
     *
     * @return array An associative array declaring the status / success of the operation.
     * @author Michael Munger <michael@highpoweredhelp.com>
     **/

    function declareMySelf() {
        if($this->verbosity > 4 && $this->loaded ){
            print("Default Grammar Plugin loaded.\n");
        }
        return array('success' => true);
    }

    function debugDump($args) {
        $AE = $args['AE'];
        $cmd = $args['command'];

        switch($cmd->getToken(4)) {
            case 'available':
                var_dump($PE->availablePlugins);
                break;
            case 'enabled':
                var_dump($PE->enabledPlugins);
                break;
            case 'apps':
                var_dump($AE->apps);
                break;
            case "ae":
                var_dump($AE);
                break;
            default:
                break;
        }
    }

    function doDebug($args) {
        $cmd = $args['command'];

        switch($cmd->getToken(3)) {
            case "dump":
                $this->debugDump($args);
                break;
        }
    }

    function processCommand($args) {
        $cmd = $args['command'];

        if($cmd->startsWith('errors')) {
            $state = $cmd->getLastToken();
            if($state == 'show' || $state == 'hide') $args['AE']->Configs->setConfig('errors', $state);
        }

        if($cmd->startsWith('set visualtrace')) {
            $visualTrace = ($cmd->getLastToken() == 'on' ? true : false);

            printf("AppEngine visual trace set to: %s" . PHP_EOL, ( $args['AE']->setVisualTrace($visualTrace) ? "on" : "off"));
        }

        if($cmd->startsWith('show debug')) {
            $this->doDebug($args);
        }

        if($cmd->is('test enabled')) {
            print "Default Grammar is enabled and responding" . PHP_EOL;
            return ['success' => true];
        }

        if($cmd->is('test app')) {
            $return = [];
            $return['test-value'] = 7;
            $return['success']    = true;
            return $return;
        }

        return ['success' => true];
    }

}

/*$pluginDefaultGrammar = new DefaultGrammar();
$pluginDefaultGrammar->addHook('cli-load-grammar','loadDefaultGrammar');
$pluginDefaultGrammar->addHook('cli-init','declareMySelf');
$pluginDefaultGrammar->addHook('cli-command','processCommand');

array_push($this->apps,$pluginDefaultGrammar);*/
