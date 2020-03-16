<?php
return <<<EOT
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class $obj->model extends Model
{
{$obj->forcedName}
{$obj->defaultValues}
{$obj->fillable}
{$obj->hasMany}
{$obj->BelongsTo}
{$obj->BelongsToMany}
}
EOT;
