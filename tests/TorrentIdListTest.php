<?php

namespace Martial\Transmission\Api\Tests;

use Martial\Transmission\API\TorrentIdList;

class TorrentIdListTest extends \PHPUnit_Framework_TestCase
{
    public function testGetListShouldReturnAnIndexedArrayStartingAtTheKeyZero()
    {
        $ids = [
            2 => 42,
            4 => 43,
            5 => 44,
            'string' => 45,
            12 => 46
        ];

        $list = (new TorrentIdList($ids))->getList();

        $this->assertSame($ids[2], $list[0]);
        $this->assertSame($ids[4], $list[1]);
        $this->assertSame($ids[5], $list[2]);
        $this->assertSame($ids['string'], $list[3]);
        $this->assertSame($ids[12], $list[4]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The array of IDs can only contain integers. Item of type string given at the offset 2
     */
    public function testListShouldThrowAnExceptionWhenTheArrayOfIdsDoesNotContainOnlyIntegers()
    {
        new TorrentIdList([
            42,
            54,
            '74'
        ]);
    }
}
