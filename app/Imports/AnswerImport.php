<?php

namespace App\Imports;

use App\Models\Application;
use App\Models\Answer as ModelsAnswer;
use App\Answer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnswerImport implements ToCollection, WithHeadingRow
{
    protected $app;
    function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function collection(Collection $row)
    {
        foreach ($row as $row) {
            $label = $this->app->label()->firstOrCreate(
                ['label_name' => $row['label']],
                [
                    'app_id' => $this->app->id,
                    'created_at' => new \DateTime
                ]
            );
            ModelsAnswer::firstOrCreate([
                'app_id' => $this->app->id,
                'text' => $row['text'],
                'label_id' => $label->id
            ]);
        }
    }
}
