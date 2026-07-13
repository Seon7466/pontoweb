<?php

namespace App\Core\Services;

use App\Core\Repositories\BaseRepository;

class CrudService
{
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginate($perPage = 10)
    {
        return $this->repository->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
