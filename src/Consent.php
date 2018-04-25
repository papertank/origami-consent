<?php

namespace Origami\Consent;

use Illuminate\Database\Eloquent\Model;

class Consent extends Model
{
    protected $fillable = ['name', 'model_id', 'model_type', 'given'];
    protected $casts = ['given' => 'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('consent.table'));
    }

    public static function create(array $attributes = [])
    {
        $conditions = array_only($attributes, ['name','model_id','model_type']);

        $consent = static::query()->where($conditions)->latest()->first();

        if (! $consent) {
            $consent = new static;
        }

        $consent->fill($attributes);
    
        if (! $consent->exists || $consent->isDirty()) {
            $consent->save();
        }

        return $consent;
    }

    public static function get(Model $model, $name)
    {
        $attributes = [
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'name' => $name
        ];

        $consent = static::query()->where($attributes)->latest()->first();

        if (!$consent) {
            $consent = new static($attributes);
            $consent->given = false;
        }

        return $consent;
    }

    public function given()
    {
        return $this->given;
    }
}
