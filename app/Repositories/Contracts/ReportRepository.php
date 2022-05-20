<?php

namespace App\Repositories\Contracts;

use App\Models\Report;

interface ReportRepository extends BaseRepository
{
    /**
     * Create New Report
     *
     * @param array $payload
     * @return Report
     */
    public function createReport(array $payload): Report;


    /**
     * Update Report
     *
     * @param integer|string|Report $key
     * @param array $payload
     * @return Report
     */
    public function updateReport(
        int|string|Report $key,
        array $payload
    ): Report;

    /**
     * Delete Report
     *
     * @param integer|string|Report $key
     * @return boolean
     */
    public function deleteReport(int|string|Report $key): bool;

    /**
     * Get DataTables Of Report
     *
     */
    public function datatables();

    /**
     * Get Datatables Of Admin Page
     *
     */
    public function datatablesManager();

    /**
     * Get Position Options By Project
     *
     * @param integer|string $userId
     * @param integer|string $projectId
     * @return array
     */
    public function getPositionOptions(
        int|string $userId,
        int|string $projectId
    ): array;

    /**
     * Get Project Options By User
     *
     * @param integer|string $userId
     * @return array
     */
    public function getProjectOptions(int|string $userId): array;
}
