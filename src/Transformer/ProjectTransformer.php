<?php

namespace App\Transformer;

use App\Entity\Project;

class ProjectTransformer
{
    public function transforms(array $projects)
    {
        $result = [];
        foreach ($projects as $project) {
            $result[] = $this->transform($project);
        }

        return $result;
    }

    private function transform(Project $project)
    {
        return [
            'id' => $project->getId(),
            'text' => $project->getNom(),
        ];
    }
}
