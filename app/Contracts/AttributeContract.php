<?php

namespace App\Contracts;

/**
 * Interface AttributeContract
 * @package App\Contracts
 */
interface AttributeContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listAttributes(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findAttributeById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createAttribute(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateAttribute(array $params);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAttribute($id);
}