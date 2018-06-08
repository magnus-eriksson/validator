# Validator

[![Build Status](https://api.travis-ci.org/magnus-eriksson/validator.svg?branch=master)](https://travis-ci.org/magnus-eriksson/validator)

#### So, why create yet another validation library?
After searching for a simple standalone validation library, I've yet to find one that is actively maintained, doesn't require you to run through hoops to make it play nicely with your application or has a bunch of third party dependencies. And it's always fun to build stuff!

This library is ment to be very customizable and easy to fit into most application without too much work.


* [Installation](#installation)
* [Simple example](#simple-example)
* [Get errors](#get-errors)
* [Custom error messages](#custom-error-messages)
    * [Field error messages](#field-error-messages)
* [Use nice field names](#use-nice-field-names)
* [Available rules](#available-rules)
* [Custom rules](#custom-rules)
    * [Define a rule](#define-a-rule)
    * [Naming](#naming)
    * [Response](#response)
    * [Rulesets](#register-and-use-our-new-ruleset)
* [Quicktest a value](#quicktest-a-value)


## Installation
You should probably use [composer](https://getcomposer.org):

    $ composer require maer/validator

_Change `dev-master` to the latest tagged release_

Load composers autoloader:

```php
include 'path/to/vendor/autoload.php';
```

## Simple example

```php
use Maer\Validator\Validator;

// Main instance, this only needs to be instantiated once
$validator = new Validator;

$data = [
    'name'        => 'Foo Bar',
    'status'      => 'awesome',
    'email'       => 'master@universe.com',
    'won_fights'  => 10000,
    'lost_fights' => -1
];

// Adding rules - Method 1
// ------------------------------------------------------------
// This method allows you to define all your rules in an array:
// ['key' => ['rule1', 'rule2:argument', 'rule3:argument1,argument2', etc]]

$rules = [
    'name'        => ['required','minLength:2','maxLength:32'],
    'status'      => ['required','in:cool,awesome,something else'],
    'email'       => ['required','email'],
    'won_fights'  => ['required','integer'],
    'lost_fights' => ['required','integer'],
];

$v = $validator->make($data, $rules);

// Adding rules - Method 2
// ------------------------------------------------------------
// This method allows you to add the rules using method chaining

$v = $validator->make($data);

// Define validation rules for the different parameters
$v->param('name')
    ->required()
    ->minLength(2)
    ->maxLength(22);

$v->param('status')
    ->required()
    ->in(['cool', 'awesome', 'foo']);

$v->param('email')
    ->required()
    ->email();

$v->param('won_fights')
    ->required()
    ->integer();

$v->param('lost_fights')
    ->required()
    ->integer();


// Validate
// ------------------------------------------------------------
if ($v->passes()) {
    echo "Yay.. it passed!";
} else {
    // Failed. Get all the errors
    var_dump($v->errors()->all());
}
```

#### Why two different ways of adding rules?

I've found that there are pros and cons with both ways:

**Using arrays**
_Pros:_ This method allows you to define rules that can easily be reused for multiple validations. You can even store them in a config or similar.
_Cons:_ Since you pass arguments as comma separated values, arguments can't contain commas. Sure, we could implement escaping etc, but that's a pain and error prone. Also, any argument would be sent in as a string, which makes it hard to do strict type checks.

**Using methods**
_Pros:_ This solves the cons with using arrays.
_Cons:_ It's not as easy to reuse the rules. You would need to create a function/method to handle that.


## Get errors

The `errors()`-method returns an instance of the `Errors`-class. To fetch a list of failed fields and their error messages:

```php
$messages = $v->errors()->all();

// returns:
// [
//    'field_name'  => 'error_message',
//    'field_name2' => 'error_message2',
// ]
```

If you just want a list of the failed fields:
```php
$fields = $v->errors()->fields();

// returns:
// [
//    'field_name1',
//    'field_name2',
// ]
```

If you just want an indexed array with the error messages:
```php
$fields = $v->errors()->messages();

// returns:
// [
//    'error message',
//    'error message2',
// ]
```

To check if a specific field failed:
```php
if ($v->errors()->has('field_name')) {
    echo "field_name failed...";
}
```

## Custom error messages
You can override the built in error messages by sending in an array with your custom messages to the validation instance:

```php
use Maer\Validator\Validator;

$validator = new Validator;

$validator->addMessages([
    'required' => 'Dude! This field %s is required!',
    ...
]);
```

You only need to add the messages you want to override.

### Field error messages
You can also set a specific error message for a field. In this case, you will get the same error message no matter which rule fails.

```php
use Maer\Validator\Validator;

$validator = new Validator;
$v = $validator->make($data);

// Define the error messages the fields
$v->fieldMessages([
    'foo' => The field foo totally failed',
    ...
]);

```

## Use nice field names
If you're planning to show the error messages to the user, it might not be a good idea to use the field name in the messages (example: "The field company_name must be at least xx characters").

To use a nice field name instead, use the method `->fieldNames()` to set nice names:

```php
use Maer\Validator\Validator;

$validator = new Validator;
$v = $validator->make([
    'company_name' => 'Acme AB',
    ...
]);

$v->fieldNames([
    'company_name' => 'Company name',
    ...
]);

```

If the `company_name` fails, you will get a nicer message, like: "The field Company Name must be at least xx characters".

## Available rules

|Rule                           |  Description                                              |
|-------------------------------|-----------------------------------------------------------|
| `required()`                  | The key must exist in the data array                      |
| `minLength( int:length )`     | Input must be more or equal in length                     |
| `maxLenght( int:length )`     | Input must be less or equal in length                     |
| `minSize( int:size )`         | Input must have a value of more or equal                  |
| `maxSize( int:size )`         | Input must have a value of less or equal                  |
| `regex( string:expression )`  | Input must match regular expression                       |
| `alphaNumeric()`              | input must only contain alphabetic and numeric characters |
| `integer()`                   | Input must be of type integer                             |
| `float()`                     | Input must be of type float                               |
| `boolean()`                   | Input must be of type boolean                             |
| `email()`                     | Input must be a valid email address                       |
| `url()`                       | Input must be a valid URL                                 |
| `ip()`                        | Input must be a valid IP address                          |
| `equal( mixed )`              | Input must match value                                    |
| `notEqual( mixed )`           | Input must not match value                                |
| `same( string:field_name )`   | Input must match another field                            |
| `different( string:field_name )` | Input must not match another field                     |
| `in( array )`    | Input must exist in the list of values                                 |
| `notIn( array )` | Input must not exist in the list of values                             |


A note about the **email, url** and **ip** rules, they do not check if they exist. They only check that the format is valid for their respective types.

#### A note about `required`
When a field is set as `required`, the field _must_ exist in the data array and is evaluated with `array_key_exists()`.


## Custom rules
It wouldn't be very customizable if you couldn't add your own validation rules.

#### Define a rule
To register your own rules, you need to create a new class which extends `Maer\Validator\RuleSet`.
If we want to create a new rule called **myCoolRule** that takes one argument, it would look something like this:

```php
class MyRules extends Maer\Validator\Rules\RuleSet
{
    public function ruleMyCoolRule($input, $arg)
    {
        // Do some awesome validation
        if ($input == 'chuck') {
            return true;
        }

        // On fail, return a default error message. The %s will be
        // replaced with the field name/nice name.
        return "The field %s must be chuck";
    }
}
```

The `$input` is the value to validate. This value will automatically be injected to the rule. The above rule would be used like: `->myCoolRule($arg)` or `['myCoolRule:arg']`. You can of course add as many argument as you want.

#### Naming
The method must be prepended by the word "rule" (to eliminate any clashes with reserved keywords) and the first letter in the actual name must be a capital letter. Other than that, you're the boss.

#### Response
To make the rule pass, it must return `true` (strict type checking). If the rule fails, you can return a default error message instead, as the example above shows.

#### Register and use our new rule set

This is quite simple. Just add it to your rule set to your validation instance:

```php
$validator->addRuleset(new MyRules);
```

Now you're ready to validate your data with your new rule:


You can add multiple rule sets to your validation instance. In case several rules in different ruleset shares the same name, the rule from the first registered ruleset will be used. This also means that none of the default rules can be overridden.

## Quicktest a value

Sometimes you just want to test a single value against one specific rule. To do this, you can use the `$validator->test()`-method:

```php
if (!$validator->test('email', 'some-invalid(a)emailaddress')) {
    echo "It failed :(";
}

// The test() method returns a boolean.
```

If you've added your own rule sets, they will also be available through the `test()`-method.

## Note
If you have any questions, suggestions or issues, let me know!

Happy coding!