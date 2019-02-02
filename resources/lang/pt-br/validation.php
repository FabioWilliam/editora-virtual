<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'array' => 'O :attribute deve ser uma lista.',
    'email' => 'O :attribute deve ser um e-mail válido.',
    'in' => 'O valor escolhido do campo :attribute é inválido.',
    'max' => [
        'numeric' => 'O campo :attribute deve ter no máximo :max caracteres.',
        'string' => 'O campo :attribute deve ter no máximo :max caracteres.',
    ],
    'min' => [
        'numeric' => 'O campo :attribute deve ter no mínimo :max caracteres.',
        'string' => 'O campo :attribute deve ter no mínimo :max caracteres.',
    ],
    'required' => 'O :attribute é um campo de preenchimento obrigatório.',
    'same' => 'Os campos :attribute e :other devem ser iguais.',
    'unique' => 'The :attribute has already been taken.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];