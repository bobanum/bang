<?php
return <<<EOT
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class $table->model extends Model
{
{$table->forcedName}
{$table->defaultValues}
{$table->fillable}
{$table->hasMany}
{$table->BelongsTo}
{$table->BelongsToMany}
}
EOT;
