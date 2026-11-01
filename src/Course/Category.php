<?php

declare(strict_types=1);

namespace App\Course;

/**
 * The course categories. The values match the options of the select field in the Course class.
 */
enum Category: string
{
    case It = 'it';
    case Engineering = 'engineering';
    case Business = 'business';
    case Design = 'design';

    public function translationKey(): string
    {
        return 'course.category.' . $this->value;
    }
}
