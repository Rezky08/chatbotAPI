<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\Question;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionExport implements FromCollection, WithHeadings
{
    protected $app;
    function __construct($app_id)
    {
        $this->app = (new Application())->find($app_id);
    }
    function headings(): array
    {
        return [
            '#',
            'Text',
            'Preprocessed',
            'Label'
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $questions = $this->app->question;
        $questions = $questions->map(function ($item, $index) {
            $item = [
                $index + 1,
                $item->text,
                $item->preprocessed,
                $item->label->label_name,
            ];
            return $item;
        });
        return $questions;
    }
}
