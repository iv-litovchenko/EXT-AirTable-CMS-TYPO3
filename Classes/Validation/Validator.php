<?php
namespace Litovchenko\AirTable\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidatorFactory;

class Validator
{
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FuncValidate;
}