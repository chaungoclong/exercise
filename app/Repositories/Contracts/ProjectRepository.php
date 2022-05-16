<?php

namespace App\Repositories\Contracts;

use App\Models\Project;

interface ProjectRepository extends BaseRepository
{
    /**
     * Create new Project
     *
     * @param array $payload
     * @return Project
     */
    public function createProject(array $payload): Project;

    /**
     * Update exist Project
     *
     * @param integer|string|Project $key
     * @param array $payload
     * @return Project
     */
    public function updateProject(
        int|string|Project $key,
        array $payload
    ): Project;

    /**
     * Delete exist Project
     *
     * @param integer|string|Project $key
     * @return boolean
     */
    public function deleteProject(int|string|Project $key): bool;

    /**
     * Get DataTables Of Project
     *
     */
    public function datatables();


    /**
     * Get Count Project By Status
     *
     * @return array
     */
    public function getCountByStatus(): array;

    /**
     * Get Data for create: list user, list position
     *
     * @return array
     */
    public function getDataForCreate(): array;


    /**
     * Get Data for Edi: list user, list status, list position
     *
     * @return array
     */
    public function getDataForEdit(): array;


    /**
     * Make Project Details For Show Project
     *
     * @param Project $project
     * @return array
     */
    public function makeProjectDetails(Project $project): array;
}
