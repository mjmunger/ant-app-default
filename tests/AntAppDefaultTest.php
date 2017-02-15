<?php

use PHPUnit\Framework\TestCase;

include_once('tests/test_top.php');

class AntAppDefaultTest extends TestCase
{
	/**
	 * @covers DefaultGrammar::loadDefaultGrammar
	 */

	function testLoadDefaultGrammar() {
		/* <THIS SECTION SHOWS YOU HOW TO DISABLE ALL OTHER APPS FOR TESTING> */
		$options = getDefaultOptions();
		$options['disableApps'] = true;

		$A = getMyAppEngine($options);
		$this->assertInstanceOf('\PHPAnt\Core\AppEngine', $A);

		//Make sure no apps are enabled.
		$this->assertCount(0, $A->activatedApps);

		//Enable ONLY this app
		$name = "Default Grammar";
		$A->enableApp($name,$A->availableApps[$name]);

		$this->assertCount(1, $A->enabledApps);

		//Make sure the tests are linked.
		$A->linkAppTests();
		//Activate / include them!
		$A->activateApps();
		/* </THIS SECTION SHOWS YOU HOW TO DISABLE ALL OTHER APPS FOR TESTING> */

		/* <HERE WE ARE INSTANTIATING THE APP BY ITSELF FOR TESTING> */
		
		$D = new \PHPAnt\Apps\DefaultGrammar();
		$this->assertInstanceOf('\PHPAnt\Apps\DefaultGrammar', $D);

		$grammar = $D->loadDefaultGrammar()['grammar'];
		$this->assertCount(4, $grammar);
		$this->assertCount(3,$grammar['set']);
		$this->assertCount(2,$grammar['set']['debug']);
		$this->assertCount(3,$grammar['show']);
		$this->assertCount(1,$grammar['show']['debug']);
		$this->assertCount(1,$grammar['show']['debug']['environment']);
		$this->assertCount(3,$grammar['show']['debug']['environment']['dump']);

		$this->assertTrue($D->loaded);
		/* </HERE WE ARE INSTANTIATING THE APP BY ITSELF FOR TESTING> */
	}


	/**
	 * @covers DefaultGrammar::declareMyself
	 * @covers DefaultGrammar::processCommand
	 */
	public function testDeclareMyself()
	{
		$options = getDefaultOptions();
		$options['disableApps'] = true;

		$A = getMyAppEngine($options);
		$this->assertInstanceOf('\PHPAnt\Core\AppEngine', $A);

		//Make sure no apps are enabled.
		$this->assertCount(0, $A->activatedApps);

		//Enable ONLY this app
		$name = "Default Grammar";
		$A->enableApp($name,$A->availableApps[$name]);
		$A->reload();

		$D = new \PHPAnt\Apps\DefaultGrammar();
		$this->assertInstanceOf('\PHPAnt\Apps\DefaultGrammar', $D);

		$grammar = $D->loadDefaultGrammar();
		$D->verbosity = 5;
		$this->expectOutputString("Default Grammar Plugin loaded.\n");
		$D->declareMyself();

		$C = new \PHPAnt\Core\Command('test app');

		$options = getDefaultOptions();
		$A = getMyAppEngine($options);
		$this->assertInstanceOf('\PHPAnt\Core\AppEngine', $A);		
		$args = [];
		$args['AE'] = $A;
		$args['command'] = $C;
		$return = $D->processCommand($args);
		$this->assertTrue($return['success']);
		$this->assertSame($return['test-value'],7);
	}
	
}