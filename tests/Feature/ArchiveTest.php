<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_archives_index_page_is_displayed_successfully(): void
    {
        $response = $this->get('/archives');

        $response->assertStatus(200);
    }
}
