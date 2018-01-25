<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 27 Sep 2017 13:47:10 +0700.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheModelTrait;

class PermissionGroup extends Model
{
    use CacheModelTrait;
    
	protected $table = 'permission_group';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'group_id' => 'int',
		'permission_id' => 'int'
	];

	public function groups()
	{
		return $this->belongsTo(\App\Models\Group::class);
	}

	public function permissions()
	{
		return $this->belongsTo(\App\Models\Permission::class);
	}
}
