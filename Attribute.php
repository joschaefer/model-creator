<?php

class Attribute {

	/** @var string */
	private $name;

	/** @var Type */
	private $type;

	/** @var bool */
	private $optional;

	/** @var bool */
	private $collection;

	/** @var string */
	private $default;

	/** @var \ICanBoogie\Inflector */
	private $inflector;

	/**
	 * Attribute constructor.
	 *
	 * @param      $name
	 * @param Type $type
	 * @param bool $optional
	 * @param bool $collection
	 * @param null $default
	 *
	 * @throws RuntimeException
	 */
	public function __construct( $name, Type $type, $optional = false, $collection = false, $default = null ) {

		$this->inflector = \ICanBoogie\Inflector::get();
		$this->name = $this->inflector->underscore($name);
		$this->type = $type;
		$this->optional = $optional;
		$this->collection = $collection;
		$this->default = $default;

		if( $this->type instanceof BooleanType && is_null( $this->default ) ) {
			throw new RuntimeException('Boolean must have a default value');
		}

	}

	/**
	 * @return string
	 */
	public function getVariableName(): string {
		return $this->inflector->camelize( $this->name, true );
	}

	/**
	 * @return string
	 */
	public function getVariableNamePlural(): string {
		return $this->inflector->pluralize( $this->getVariableName() );
	}

	/**
	 * @return string
	 */
	public function getMethodName(): string {
		return $this->inflector->camelize( $this->name, false );
	}

	/**
	 * @return string
	 */
	public function getMethodNamePlural(): string {
		return $this->inflector->pluralize( $this->getMethodName() );
	}

	/**
	 * @return string
	 */
	public function getTextName(): string {
		return mb_strtolower( $this->inflector->humanize( $this->name ) );
	}

	/**
	 * @return string
	 */
	public function getTextNamePlural(): string {
		return $this->inflector->pluralize( $this->getTextName() );
	}

	/**
	 * @return string
	 */
	public function getArrayName(): string {
		return $this->inflector->underscore( $this->name );
	}

	/**
	 * @return string
	 */
	public function getErrorName(): string {
		return mb_strtoupper( $this->inflector->underscore( $this->name ) );
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return Type
	 */
	public function getType(): Type {
		return $this->type;
	}

	/**
	 * @param Type $type
	 */
	public function setType( Type $type ) {
		$this->type = $type;
	}

	/**
	 * @return bool
	 */
	public function isOptional(): bool {
		return $this->optional;
	}

	/**
	 * @param bool $optional
	 */
	public function setOptional( bool $optional ) {
		$this->optional = $optional;
	}

	/**
	 * @return bool
	 */
	public function isCollection(): bool {
		return $this->collection;
	}

	/**
	 * @param bool $collection
	 */
	public function setCollection( bool $collection ) {
		$this->collection = $collection;
	}

	/**
	 * @return null|string
	 */
	public function getDefault(): ?string {
		return $this->default;
	}

	/**
	 * @param string $default
	 */
	public function setDefault( string $default ) {
		$this->default = $default;
	}

	/**
	 * @return bool
	 */
	public function hasDefault(): bool {
		return trim($this->default) != '';
	}

	public function createAttribute(): string {

		if( $this->isCollection() ) {
			return "\t/** @var " . $this->getType()->getName() . "[] */
	private $" . $this->getVariableNamePlural() . " = [];

";
		} else if( $this->getType() instanceof PasswordType ) {
			return "\t/** @var " . $this->getType()->getName() . " */
	private $" . $this->getVariableName() . "Hash;

";
		} else {
			return "\t/** @var " . $this->getType()->getName() . " */
	private $" . $this->getVariableName() . ";

";
		}

	}

	public function createDefaultSettings(): string {
		if( $this->hasDefault() ) {
			return "\t\t\$this->" . $this->getVariableName() . " = " . $this->getDefault() . ";\n";
		}
		return '';
	}

	public function createGetterAndSetter( Model $model ): string {

		if( $this->isCollection() ) {

			return "	/**
	 * Check whether the list of " . $this->getTextNamePlural() . " of this " . $model->getTextName() . " is not empty.
	 *
	 * @return bool True, if the list of " . $this->getTextNamePlural() . " of this " . $model->getTextName() . " has at least one " . $this->getTextName() . ", false otherwise
	 */
	public function has" . $this->getMethodNamePlural() . "(): bool {
		return sizeof(\$this->" . $this->getVariableNamePlural() . "[]) > 0;
	}
	
	/**
	 * Set the list of " . $this->getTextNamePlural() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . "[] $" . $this->getVariableNamePlural() . " The list of " . $this->getTextNamePlural() . "
	 */
	public function set" . $this->getMethodNamePlural() . "( array $" . $this->getVariableNamePlural() . " ): void {
		\$this->" . $this->getVariableNamePlural() . " = $" . $this->getVariableNamePlural() . ";
	}

	/**
	 * Add the " . $this->getTextName() . " to the list of " . $this->getTextNamePlural() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . " $" . $this->getVariableName() . " The " . $this->getTextName() . " to add
	 */
	public function add" . $this->getMethodName() . "( " . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		$" . $this->getVariableName() . "->set" . $model->getClassName() . "(\$this);
		\$this->" . $this->getVariableNamePlural() . "[] = $" . $this->getVariableName() . ";
	}

";

		} else if( $this->getType() instanceof BooleanType ) {

			return "	/**
	 * Check whether this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . " True if this " . $model->getTextName() . " is " . $this->getTextName() . ", false otherwise
	 */
	public function is" . $this->getMethodName() . "(): " . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . ";
	}

	/**
	 * Set whether this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . " $" . $this->getVariableName() . " True if this " . $model->getTextName() . " is " . $this->getTextName() . ", false otherwise
	 */
	public function set" . $this->getMethodName() . "( " . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";

		} else if( $this->getType() instanceof DateType && strpos( $this->getArrayName(), 'date_of_' ) === 0 ) {

			return "	/**
	 * Get the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The " . $this->getTextName() . "
	 */
	public function get" . $this->getMethodName() . "(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . ";
	}

	/**
	 * Set the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";

		} else if( $this->getType() instanceof DateType ) {

			return "	/**
	 * Get the date this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The date this " . $model->getTextName() . " is " . $this->getTextName() . "
	 */
	public function get" . $this->getMethodName() . "(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . ";
	}

	/**
	 * Set the date this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The date this " . $model->getTextName() . " is " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";

		} else if( $this->getType() instanceof DateTimeType ) {

			return "	/**
	 * Get the date and time this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The date and time this " . $model->getTextName() . " is " . $this->getTextName() . "
	 */
	public function get" . $this->getMethodName() . "(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . ";
	}

	/**
	 * Set the date and time this " . $model->getTextName() . " is " . $this->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The date and time this " . $model->getTextName() . " is " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";
		} else if( $this->getType() instanceof CurrencyType ) {

			return "	/**
	 * Get the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The " . $this->getTextName() . "
	 */
	public function get" . $this->getMethodName() . "(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return round( \$this->" . $this->getVariableName() . ", 2 );
	}

	/**
	 * Set the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";

		} else if( $this->getType() instanceof PasswordType ) {

			return "	/**
	 * Get the " . $this->getTextName() . " hash of this " . $model->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The " . $this->getTextName() . " hash
	 */
	public function get" . $this->getMethodName() . "Hash(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . "Hash;
	}

	/**
	 * Set the " . $this->getTextName() . " hash of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . "Hash The " . $this->getTextName() . " hash
	 */
	public function set" . $this->getMethodName() . "Hash( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . "Hash ): void {
		\$this->" . $this->getVariableName() . "Hash = $" . $this->getVariableName() . "Hash;
	}

	/**
	 * Set the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {

		if( trim($" . $this->getVariableName() . ") != false ) {
			\$this->" . $this->getVariableName() . "Hash = password_hash( $" . $this->getVariableName() . ", PASSWORD_BCRYPT );
		}

	}
	
	/**
	 * Verify the given " . $this->getTextName() . " with the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . " $" . $this->getVariableName() . " The " . $this->getTextName() . " to verify
	 * @return bool True, if the given " . $this->getTextName() . " is correct, false otherwise
	 */
	public function verify" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): bool {
		return password_verify( $" . $this->getVariableName() . ", \$this->" . $this->getVariableName() . "Hash );
	}

";

		} else {

			return "	/**
	 * Get the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @return " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " The " . $this->getTextName() . "
	 */
	public function get" . $this->getMethodName() . "(): " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " {
		return \$this->" . $this->getVariableName() . ";
	}

	/**
	 * Set the " . $this->getTextName() . " of this " . $model->getTextName() . ".
	 *
	 * @param " . $this->getType()->getName() . ( $this->isOptional() ? '|null' : '' ) . " $" . $this->getVariableName() . " The " . $this->getTextName() . "
	 */
	public function set" . $this->getMethodName() . "( " . ( $this->isOptional() ? '?' : '' ) . $this->getType()->getName() . " $" . $this->getVariableName() . " ): void {
		\$this->" . $this->getVariableName() . " = $" . $this->getVariableName() . ";
	}

";

		}

	}

}
