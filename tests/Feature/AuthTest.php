<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testDenyAnonymousAccess(): void
    {
        $this->get('/')->assertRedirectToRoute('login');
    }
}
