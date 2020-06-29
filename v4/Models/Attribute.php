<?php

namespace v4\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends BaseModel
{
    public $timestamps = false;

    protected $table = 'form_attributes';
    /**
     * The attributes that should be mutated to dates.
     * @var array
    */
    protected $dates = ['created', 'updated'];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'key',
        'label',
        'instructions',
        'input',
        'type',
        'required',
        'default',
        'priority',
        'options',
        'cardinality',
        'config',
        'response_private',
        'form_stage_id'
    ];
    protected $with = ['translations'];

    protected $casts = [
        'config' => 'json',
        'options' => 'json',
    ];
    protected $hidden = ['icon'];

    public function stage()
    {
        return $this->belongsTo('v4\Models\Stage', 'form_stage_id');
    }

    public function getOptionsAttribute($value)
    {
        if ($this->type === 'tags') {
            $values = array_map(function ($v) {
                if (is_object($v)) {
                    return $v->id;
                }
                return $v;
            }, json_decode($value));
            return Category::all()->whereIn('id', $values);
        }
        return json_decode($value);
    }
    /**
     * Get the attribute's translation.
     */
    public function translations()
    {
        return $this->morphMany('v4\Models\Translation', 'translatable');
    }
}