<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 27 Sep 2017 13:47:10 +0700.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheModelTrait;

class GroupRole extends Model
{
    use CacheModelTrait;
    
	protected $table = 'group_role';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'group_id' => 'int',
		'role_id' => 'int'
	];

	public function group()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}
}
