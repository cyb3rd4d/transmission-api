<?php

namespace Martial\Transmission\API;

class TorrentIdList
{
    /**
     * @var \SplFixedArray
     */
    private $list;

    /**
     * This list only accepts an array of integers, or throw an exception with invalid type.
     *
     * @param int[] $ids
     * @throws \InvalidArgumentException
     */
    public function __construct(array $ids)
    {
        foreach ($ids as $key => $id) {
            $type = gettype($id);

            if ('integer' !== $type) {
                throw new \InvalidArgumentException(sprintf(
                    'The array of IDs can only contain integers. Item of type %s given at the offset %s',
                    $type,
                    $key
                ));
            }
        }

        $this->list = \SplFixedArray::fromArray($ids, false);
    }

    /**
     * @return \SplFixedArray
     */
    public function getList()
    {
        return $this->list;
    }
}
