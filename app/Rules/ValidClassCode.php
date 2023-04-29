<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Classroom;

class ValidClassCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $classroom = Classroom::where('classcode', $value)
        ->where('isAvailable', 1)
        ->where('isFull', 0)
        ->first();

        return $classroom !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Class Code is invalid';
    }
}
