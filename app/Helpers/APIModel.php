<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class APIModel
{
    protected $model;
    protected $model_ins;
    private $table_columns;
    private $add_commands;
    private $cond_columns;
    private $cond_commands;

    function __construct(Model $model, $base_cond = [])
    {
        $this->model = $model;
        $this->model_ins = $model;
        $this->table_columns = $this->model->getTableColumns();
        $this->add_commands = ['limit', 'offset', 'q', 'w'];
        $this->cond_columns = [];
        $this->cond_commands = [];

        if ($base_cond) {
            $this->model_ins = $this->model_ins->where($base_cond);
            $this->table_columns = array_diff($this->table_columns, array_keys($base_cond));
        }
    }

    public function except($columns = [])
    {
        $this->table_columns = array_diff($this->table_columns, $columns);
        return $this;
    }
    public function andWhere($cond = [])
    {
        $this->model_ins = $this->model_ins->where($cond);
        return $this;
    }
    public function orWhere($q = '')
    {
        $cond = array_diff($this->table_columns, array_keys($this->cond_columns));
        $query = '%' . $q . '%';
        if (!empty($cond)) {
            $this->model_ins = $this->model_ins->Where($cond[0], 'LIKE', $query);
            foreach (array_slice($cond, 1) as $value) {
                $this->model_ins = $this->model_ins->orWhere($value, 'LIKE', $query);
            }
        }
        return $this;
    }

    public function wRelation($relation)
    {
        if (is_array($relation)) {
            $relation = array_intersect($relation, $this->model->relationship);
        } else {
            $relation = array_intersect([$relation], $this->model->relationship);
        }
        foreach ($relation as $rel) {
            $this->model_ins = $this->model_ins->with($rel);
        }
    }
    public function woGet($request_in = [])
    {

        $this->cond_columns = array_intersect_key($request_in, array_flip($this->table_columns));
        $this->cond_commands = array_intersect_key($request_in, array_flip($this->add_commands));
        if ($this->cond_columns) {
            $this->andWhere($this->cond_columns);
        }
        if (isset($request_in['q'])) {
            $this->orWhere($request_in['q']);
        }
        if ($this->cond_commands) {
            foreach ($this->cond_commands as $key => $value) {
                switch (strtoupper($key)) {
                    case 'LIMIT':
                        $this->model_ins = $this->model_ins->take($value);
                        break;
                    case 'OFFSET':
                        $this->model_ins = $this->model_ins->skip($value);
                        break;
                    case 'W':
                        $this->wRelation($value);
                        break;
                    default:
                        break;
                }
            }
        }
        return $this->model_ins;
    }
    public function get($request_in = [])
    {
        return $this->woGet($request_in)->get();
    }
}
