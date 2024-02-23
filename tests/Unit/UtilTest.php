<?php

namespace Tests\Unit;

use App\Util\Colors;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use function App\collect_enum_values;
use function App\enum_values;

class UtilTest extends TestCase
{
    public function testCollectEnumValues(): void
    {
        $values = collect_enum_values(Colors::cases());

        $this->assertTrue($values instanceof Collection);
        $this->assertCount(22, $values);
        $this->assertContainsOnly('string', $values, true);
    }

    public function testEnumValues(): void
    {
        $values = enum_values(Colors::cases());

        $this->assertIsArray($values);
        $this->assertCount(22, $values);
        $this->assertContainsOnly('string', $values, true);
    }
}
