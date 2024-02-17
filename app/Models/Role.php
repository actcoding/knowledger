<?php

namespace App\Models;

enum Role: string
{
    case Admin = 'Admin';
    case Editor = 'Editor';
    case Viewer = 'Viewer';
}
