<?php

class DateType extends Type {

	/**
	 * @return string
	 */
	public function getName(): string {
		return '\DateTime';
	}

	/**
	 * @return bool
	 */
	public function isObject(): bool {
		return true;
	}

	/**
	 * @return string
	 */
	public function serialization(): string {
		return '$this->get%s() ? $this->get%s()->format(\'Y-m-d\') : null';
	}

	/**
	 * @return mixed
	 */
	public function deserialization(): string {
		return '$result[\'%s\'] ? \DateTime::createFromFormat( \'Y-m-d H:i:s\', $result[\'%s\'] . \' 00:00:00\' ) : null';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'is_null($this->%s)';
	}

}
