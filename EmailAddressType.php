<?php

class EmailAddressType extends StringType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'TOO_LONG' => 'mb_strlen($this->%s) > 254',
			'INVALID' => '!filter_var( $this->%s, FILTER_VALIDATE_EMAIL )',
		];
	}

}
