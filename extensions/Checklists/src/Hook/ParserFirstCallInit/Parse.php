<?php

namespace BlueSpice\Checklists\Hook\ParserFirstCallInit;

class Parse extends \BlueSpice\Hook\ParserFirstCallInit {

	/**
	 *
	 * @return bool
	 */
	protected function doProcess() {
		$this->getServices()->getService( 'BSWhoIsOnlineTracer' )->trace(
			$this->getContext()
		);
		return true;
	}

}
