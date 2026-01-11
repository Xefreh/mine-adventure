<?php

namespace App\Enums;

enum BlockType: string
{
    case Video = 'video';
    case Text = 'text';
    case Resources = 'resources';
    case Assignment = 'assignment';
    case Quiz = 'quiz';
}
