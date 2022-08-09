<?php

namespace Checklists\Tests;

use Checklists\Parser;
use PHPUnit\Framework\TestCase;

class ChecklistsParserTest extends TestCase {

	protected function setUp() : void {
		parent::setUp();
	}

	/**
	 * @group extension-Checklists
	 * @covers \Parser
	 */
	public function testUncheckedList() {
		$testInput = "
         


        []Unchecked item 1
        
        
        [] Unchecked item 2
        
        
        [x] Checked item
        ";
		$outputHtml = "<ul><li>Unchecked item 1</li><li>Unchecked item 2</li>";
		$outputHtml .= "<li><s>Checked item</s></li></ul>";
		// Parser
		$parser = new Parser();
		$outputFromParser = $parser->parse( $testInput );
		$this->assertTrue( $outputFromParser == $outputHtml, "Checklists parser is working fine!" );
	}

}
