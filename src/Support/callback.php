<?php

function add_one_to_x( $x ): void
{
    echo $x + 1;
}

//add_one_to_x(3);

function cat_says(): void
{
    echo "Dogs suck";
}

function dog_says($dog_saying): void
{
    echo $dog_saying;
}

function animal_says($animal_type, $animal_function): void
{
    echo $animal_type . ' says ';
    echo call_user_func($animal_function);
}

//animal_says('Cat', 'cat_says');


function animal_says_two($animal_type, $animal_function, $saying): void
{
        echo $animal_type . ' says ';
        echo call_user_func($animal_function, $saying);
}

//animal_says_two('Dog', 'dog_says', 'Cats suck');

function animal_says_three($animal_type, $animal_function, $saying): void
{
    echo $animal_type . ' says ';
    $animal_function($saying);
}

//animal_says_three('Cat', 'cat_says', 'hello');


function animal_says_four($animal_function, $callback, $saying): void
{
    echo $animal_function . ' says ';
    $callback($saying);
}

$saying_also = ' yap ';



//animal_says_four('Dog', 'dog_says', 'Let me out');
animal_says_four(
    'Dog',
    function ($says) use ($saying_also)
    {
        echo $says;
        echo $saying_also;
    },
    'Let me out');

echo PHP_EOL;

function animal_says_five($animal_type, $callback, $saying): void
{
    echo $animal_type . ' says ';
    echo $callback($saying);
}

animal_says_five('Dog', fn($saying) => $saying, 'Leave me along');











