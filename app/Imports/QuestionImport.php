<?php

namespace App\Imports;

use App\Models\Application;
use App\Models\Question as ModelsQuestion;
use App\Question;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToCollection, WithHeadingRow
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
            ModelsQuestion::firstOrCreate([
                'app_id' => $this->app->id,
                'text' => $row['text'],
                'label_id' => $label->id
            ]);
        }
    }
}
