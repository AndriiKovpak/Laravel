<?php

namespace App\Components\Repositories;

/**
 * Interface RepositoryContract
 * @package App\Components\Repositories
 */
interface RepositoryContract
{
    /**
     * @param $filters
     * @param $page
     * @param array $options
     * @return mixed
     */
    public function paginate($filters, $page, $options);
}
