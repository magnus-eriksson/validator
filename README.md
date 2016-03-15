# Validator

An easy and customizable validation library.

> This library is still under development. It's not recommended to use it in production before there are at least one tagged version. There might be breaking changes.

#####So, why create yet another validation library?
After searching for a simple standalone validation library, I've yet to find one that is actively maintained, doesn't require you to run through hoops to make it play nicely with your application or has a bunch of third party dependencies. And it's always fun to build stuff!

This library is ment to be very customizable and easy to fit into most application without too much work.

Let's get started!

## Installation
You should probably use [composer](https://getcomposer.org):

    composer require maer/validator dev-master

Load composers autoloader:

    include 'path/to/vendor/autoload.php';

## Simple example

    // Main instance, this only needs to be instantiated once
    $validator = new Maer\Validator\Validator;
    
    // Data to validate
    $data = [
        'name'        => 'Chuck Norris',
        'status'      => 'awesome',
        'email'       => 'master@universe.com',
        'won_fights'  => 10000,
        'lost_fights' => -1
    ];

    $rules = [
        'name'        => 'required|minLength:2|maxLength:32',
        'status'      => 'required|in:cool,awesome,something else',
        'email'       => 'required|email',
        'won_fights'  => 'required|integer',
        'lost_fights' => 'required|integer',
    ];


    // Create a new validation instance
    $v = $validator->make($data, $rules);

    // Validate
    if ($v->passes()) {
        echo "Yay.. it passed!";
    } else {
        echo "Nope. We got validation errors";
    }

## Get errors

if the validaton doesn't pass, you can get the error messages/invalid fields with the `Validation::getErrors()`-method:

    if (!$v->passes()) {
        $errors = $v->getErrors();
    }

The `getErrors()`-method returns an array with the field name as key and the error as value:    
    
    [
        'field_name'  => 'error_message',
        'field_name2' => 'error_message2',
    ]


## Custom error messages
You can override the built in error messages by sending in an array with your custom messages as a third parameter when you make a new validation instance:

    $v = $validator->make($data, $rules, [
        'required' => "Yo! The field %s is required, dude!",
        'email'    => "No no no, %s must be a valid email",
        ...
    ]);

You only need to add the messages you want to override.


## Available rules

|Rule                           |  Description                                              |
|-------------------------------|-----------------------------------------------------------|
| **required**                  | Checks if the value isn't empty                           |
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


## Custom rules
It wouldn't be very customizable if you couldn't add your own validation rules.

#### Define the rule
To register your own rules, you need to create a new class which extends the `Maer\Validator\Ruleset`-class,
If we want to create a new rule called **myCoolRule** that takes one argument **myCoolRule:**_value_, it would look something like this:

    class MyRules extends Maer\Validator\Ruleset
    {
        public function ruleMyCoolRule($input, $arg)
        {
            // Do some awesome validation
            return true/false;
        } 
    }

The `$input` is the value to validate. The `$arg` is the rule argument (omit this if your rule doesn't have any arguments). For multiple arguments, use: **rule:**_arg1,arg2..._

#### Naming
The method must be prepended by the word "rule" (to eliminate any clashes with reserved keywords) and the first letter in the actual name must be a capital letter. Other than that, you're the boss.

#### Response
To make the rule fail, it must return `false` (strict checking). All other responses will count as a pass.


#### Register and use our new ruleset

This is quite simple. Just add it to your ruleset to your validation instance:

    $v = $validator->make($data, $rules);

    $v->addRuleset(new MyRules);

Now you're ready to validate your data with your new rule: `myCoolRule:something`.

You can add multiple rulesets to your validation instance. In case several rules in different ruleset shares the same name, the rule from the first registered ruleset will be used. This also means that none of the default rules can be overridden.

