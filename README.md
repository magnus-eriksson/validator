# Validator

### Simple usage

```php
$data = [
    'foo' => 'bar',
    'lorem' => [
        'ipsum' => 'loremipsum',
    ]
];

$rules = [
    'foo'         => ['required', 'in:bar,foo'],
    'lorem.ipsum' => ['required', 'minLength:10'],
],

$v = new \Maer\Validator\Validator($data, $rules);

if ($v->isValid() === false) {
    print_r($v->getErrors());
}
```

### Add custom rules

```php
class MyRules extends \Maer\Validator\Sets\AbstractSet
{
    public function myRule(mixed $input): string|bool
    {
        if ($input === 'hello-world') {
            return true;
        }

        return "Input must be hello-world";
    }

    public function rules(): array
    {
        return [
            'myNewRule' => [$this, 'myRule'],
        ];
    }
}

$data = [
    'foo' => 'hello-world',
];

$rules = [
    'foo' => ['myNewRule'],
];

$v = new Validator($data, $rules);

// Add ruleset
$v->addSet(new MyRules);
```


