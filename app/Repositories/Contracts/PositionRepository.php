<?php

namespace App\Repositories\Contracts;

use App\Models\Position;

interface PositionRepository extends BaseRepository
{
    /**
     * Create New Position
     *
     * @param array $payload
     * @return Position
     */
    public function createPosition(array $payload): Position;



    /**
     * Update Position, Do Not Update 'slug' Attribute
     *
     * @param string|integer|Position $key
     * @param array $payload
     * @return Position
     */
    public function updatePosition(
        string|int|Position $key,
        array $payload
    ): Position;


    /**
     * Delete Position
     *
     * @param integer|string|Position $key
     * @return boolean
     */
    public function deletePosition(int|string|Position $key): bool;


    /**
     * Force Delete Position
     *
     * @param integer|string|Position $key
     * @return void
     */
    public function forceDeletePosition(int|string|Position $key): bool;



    /**
     * Restore Position
     *
     * @param integer|string|Position $key
     * @return void
     */
    public function restorePosition(int|string|Position $key): bool;


    /**
     * Get Datatables Of Position
     *
     */
    public function datatables();
}
