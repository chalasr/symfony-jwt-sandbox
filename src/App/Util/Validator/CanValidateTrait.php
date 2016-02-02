<?php

namespace App\Util\Validator;

use App\Util\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Add validations for post-request data.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait CanValidateTrait
{
    /** @var array */
    protected $rules = array();

    /** @var array */
    protected $errors = array();

    /**
     * Validates data based on $rules.
     *
     * @param array  $data
     * @param string $type Origin of signup
     *
     * @return bool Validation status
     */
    protected function check($data, $type = 'basic', $lazy = false)
    {
        $emailConstraint = new Email();
        $this->errors = array();

        foreach ($this->rules[$type] as $prop => $rules) {
            // Is set
            if (!isset($data[$prop]) && $this->hasRule($rules, 'required')) {
                $this->errors[$prop] = 'missing';
            }

            if (!isset($data[$prop])) {
                if (!empty($this->errors) && false === $lazy) {
                    return false;
                }

                continue;
            }

            // Is not blank
            if (!$data[$prop] && $this->hasRule($rules, 'nonempty')) {
                $validator = false;
                $this->errors[$prop] = 'empty';
            }

            // Is valid email
            if ($prop == 'email' && $this->hasRule($rules, 'email')) {
                $error = $this->get('validator')->validateValue($data[$prop], $emailConstraint);
                if ($error->count() > 0) {
                    $this->errors[$prop] = 'not a valid email';
                }
            }

            // Break validation at first error if lazy is false
            if (!empty($this->errors) && false === $lazy) {
                return false;
            }
        }

        return empty($this->errors);
    }

      /**
       * Validation failed.
       *
       * @param int $code The status code
       *
       * @return JsonResponse Instance of JsonResponse used as Exception
       */
      protected function validationFailedException($code = 400)
      {
          $message = array();

          foreach ($this->errors as $prop => $error) {
              count(array_keys($this->errors)) > 1
              ? $message[$prop] = $error
              : $message = sprintf('The request parameter \'%s\' is %s', $prop, $error);
          }

          $exception = array(
              'code'    => $code,
              'message' => $message,
              'errors'  => null,
          );

          return new JsonResponse($exception, $code);
      }

    /**
     * Returns an error caused by valid format but not good data.
     *
     * @param string $action
     * @param string $user
     *
     * @throws UnprocessableEntityHttpException
     */
    protected function missingParametersError($action, $origin = null)
    {
        $required = implode('\', \'', array_values($this->rules[null == $origin ? $action : $origin]));

        throw new UnprocessableEntityHttpException(
            sprintf('Some mandatory parameters are missing for %s user (required: \'%s\')', $action, $required)
        );
    }

    /**
     * Returns an error caused by already existing entity on try to create a new.
     *
     * @param string $prop The property used
     * @param string $val  Value of property
     *
     * @throws UnprocessableEntityHttpException
     */
    protected function resourceAlreadyExistsError($prop, $val)
    {
        throw new UnprocessableEntityHttpException(
            sprintf('An user already exists with %s \'%s\'', $prop, $val)
        );
    }

    /**
     * Check if field has rule.
     *
     * @param array  $propertyRules
     * @param string $rule
     *
     * @return bool
     */
    protected function hasRule($propertyRules, $rule)
    {
        $propertyRules = explode('|', $propertyRules);

        return in_array($rule, $propertyRules);
    }
}
