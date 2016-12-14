# Validator

[![Build Status](https://api.travis-ci.org/magnus-eriksson/validator.svg?branch=master)](https://travis-ci.org/magnus-eriksson/validator)

#### So, why create yet another validation library?
After searching for a simple standalone validation library, I've yet to find one that is actively maintained, doesn't require you to run through hoops to make it play nicely with your application or has a bunch of third party dependencies. And it's always fun to build stuff!

This library is ment to be very customizable and easy to fit into most application without too much work.

Let's get started!

## Installation
You should probably use [composer](https://getcomposer.org):

    $ composer require maer/validator dev-master

_Change `dev-master` to the latest tagged release_

Load composers autoloader:

```php
include 'path/to/vendor/autoload.php';
```

## Simple example

```php
// Main instance, this only needs to be instantiated once
$validator = new Maer\Validator\Validator;

$data = [
    'name'        => 'Chuck Norris',
    'status'      => 'awesome',
    'email'       => 'master@universe.com',
    'won_fights'  => 10000,
    'lost_fights' => -1
];

$rules = [
    'name'        => ['required','minLength:2','maxLength:32'],
    'status'      => ['required','in:cool,awesome,something else'],
    'email'       => ['required','email'],
    'won_fights'  => ['required','integer'],
    'lost_fights' => ['required','integer'],
];


// Create a new validation instance
$v = $validator->make($data, $rules);

// Validate
if ($v->passes()) {
    echo "Yay.. it passed!";
} else {
    var_dump($v->errors()->all());
}
```

## Get errors

if the validaton doesn't pass, you can get the error messages/invalid fields with the `Validation::errors()`-method:

```php
if ($v->passes() == false) {
    $errors = $v->errors();
}
```

The `errors()`-method returns an instance of the `Errors´-class. To fetch a list of failed fields and their error messages:

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

To check if a specific field failed:
```php
if ($v->errors()->has('field_name')) {
    echo "field_name failed...";
}
```



## Custom error messages
You can override the built in error messages by sending in an array with your custom messages as a third parameter when you make a new validation instance:

```php
$v = $validator->make($data, $rules, [
    'required' => "Yo! The field %s is required, dude!",
    'email'    => "No no no, %s must be a valid email",
    ...
]);
```

You only need to add the messages you want to override.

## Use nice field names
If you're planning to show the error messages to the user, it might not be a good idea to use the field name in the messages (example: "The field company_name must be at least xx characters").

To use a nice field name instead, use the `as`-parameter in your rule declaration:

```php
[
    'comapny_name' => ['required', 'minLength:2', 'as' => 'Company Name'],
    ...
]
```

If the `company_name` fails, you will get a nicer message: "The field Company Name must be at least xx characters".

## Available rules

|Rule                           |  Description                                              |
|-------------------------------|-----------------------------------------------------------|
| **required**                  | The key must exist in the data array                      |
| **allowEmpty**                | Input can be empty, regardless other rules                |
| **minLength:**_arg_           | Input must be more or equal in length                     |
| **maxLenght:**_arg_           | Input must be less or equal in length                     |
| **minSize:**_arg_             | Input must have a value of more or equal                  |
| **maxSize:**_arg_             | Input must have a value of less or equal                  |
| **regex:**_expression_        | Input must match regular expression                       |
| **alphaNumeric**              | input must only contain alphabetic and numeric characters |
| **integer**                   | Input must be of type integer                             |
| **float**                     | Input must be of type float                               |
| **boolean**                   | Input must be of type boolean                             |
| **email**                     | Input must be a valid email address                       |
| **url**                       | Input must be a valid URL                                 |
| **ip**                        | Input must be a valid IP address                          |
| **equal:**_arg_               | Input must match value                                    |
| **notEqual:**_arg_            | Input must not match value                                |
| **same:**_field_              | Input must match another field                            |
| **different:**_field_         | Input must not match another field                        |
| **in:**_value1,value2,..._    | Input must exist in the list of values                    |
| **notIn:**_value1,value2,..._ | Input must not exist in the list of values                |


A note about the **email, url** and **ip** rules, they do not check if they exist. They only check that the format is valid for their respective types.

#### A note about `required` & `allowEmpty`
When a field is set as `required`, the field _must_ exist in the data array and is evaluated with `array_key_exists()`.

If a field is set as `allowEmpty`, and the value is empty (evaluated with `empty($value)`), the field will pass an all other rules for that field will be ignored.

## Custom rules
It wouldn't be very customizable if you couldn't add your own validation rules.

#### Define the rule
To register your own rules, you need to create a new class which extends the `Maer\Validator\Ruleset`-class,
If we want to create a new rule called **myCoolRule** that takes one argument **myCoolRule:**_value_, it would look something like this:

```php
class MyRules extends Maer\Validator\Rules\Ruleset
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

The `$input` is the value to validate. The `$arg` is the rule argument (omit this if your rule doesn't have any arguments). For multiple arguments, use: **rule:**_arg1,arg2..._

#### Naming
The method must be prepended by the word "rule" (to eliminate any clashes with reserved keywords) and the first letter in the actual name must be a capital letter. Other than that, you're the boss.

#### Response
To make the rule pass, it must return `true` (strict type checking). If the rule fails, you can return a default error message instead, as the example above shows.

#### Register and use our new ruleset

This is quite simple. Just add it to your ruleset to your validation instance:

```php
$v = $validator->make($data, $rules);

$v->addRuleset(new MyRules);
```

Now you're ready to validate your data with your new rule:

    $rules = [
        'field_name': ['myCoolRule:someOptionalArgument']
    ];


You can add multiple rulesets to your validation instance. In case several rules in different ruleset shares the same name, the rule from the first registered ruleset will be used. This also means that none of the default rules can be overridden.

## Note
If you have any questions, suggestions or issues, let me know!

Happy coding!
