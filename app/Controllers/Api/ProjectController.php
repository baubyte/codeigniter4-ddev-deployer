<?php

namespace App\Controllers\Api;

use App\Models\ProjectModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProjectController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $userId = auth()->id();
        $projectModel = new ProjectModel();

        $projects = $projectModel->where(['user_id' => $userId])->findAll();

        return $this->respond(
            $this->genericResponse(
                ResponseInterface::HTTP_OK,
                "Projects found",
                false,
                ["projects" => $projects]
            )
        );
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'title' => 'required',
            'budget' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->respond(
                $this->genericResponse(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    $this->validator->getErrors(),
                    true,
                    []
                )
            );
        }

        $userId = auth()->id();
        $projectModel = new ProjectModel();

        $data = [
            "user_id" => $userId,
            "title" => $this->request->getJsonVar("title"),
            "budget" => $this->request->getJsonVar("budget"),
        ];

        if ($projectModel->insert($data)) {
            return $this->respond(
                $this->genericResponse(
                    ResponseInterface::HTTP_OK,
                    "New Project created successfully",
                    false,
                    []
                )
            );
        }

        return $this->respond(
            $this->genericResponse(
                ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                "Failed to insert Project",
                true,
                []
            )
        );
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $userId = auth()->id();
        $projectModel = new ProjectModel();

        $project = $projectModel->where(
            [
                "id" => $id,
                "user_id" => $userId
            ]
        )->findAll();

        if(empty($project)) {
            return $this->respond(
                $this->genericResponse(
                    ResponseInterface::HTTP_NOT_FOUND,
                    "Project not found",
                    true,
                    []
                )
            );
        }

        $projectModel->where([
            "id" => $id,
            "user_id" => $userId
        ])->delete();

        return $this->respond(
            $this->genericResponse(
                ResponseInterface::HTTP_OK,
                "Project deleted",
                false,
                []
            )
        );
    }

    /**
     * Generic Response
     *
     * @param integer $status
     * @param string $message
     * @param boolean $error
     * @param array $data
     * @return array
     */
    private function genericResponse(int $status, string|array $message, bool $error, array $data): array
    {
        return [
            "status"    => $status,
            "message"   => $message,
            "error"     => $error,
            "data"      => $data
        ];
    }
}
