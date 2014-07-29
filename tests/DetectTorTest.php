<?php

namespace Omerta\Ponionoo\Tests;

use Omerta\Ponionoo\Classes\OPCOnionoo;

/**
 * Test to detect if there is a tor or not
 *
 * @author Bortoli German for Omerta Game Ltd <support@omertagame.co.uk>
 */
class DetectTorTest extends \PHPUnit_Framework_TestCase {
	
	public function testTorEnabled() {
		$opc = new OPCOnionoo();
		$result = $opc->isTorEnabled("96.47.226.22");
		$this->assertTrue($result, 'The ip is down or the API is not recheable');
	}
	
	public function testTorDisabled() {
		$opc = new OPCOnionoo();
		$result = $opc->isTorEnabled("127.0.0.1");
		$this->assertFalse($result, 'The API is not recheable');
	}
	
}
