<?php
return <<<EOT
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class $obj->model extends Model
{
    public \$timestamps = false;
{$obj->forcedName}
{$obj->defaultValues}
{$obj->fillable}
{$obj->hasMany}
{$obj->BelongsTo}
{$obj->BelongsToMany}

protected function getRulesAttribute() {
    \$result = [];
    {$obj->rules}
    return \$result;
}
static function fake() {
    \$faker = \Faker\Factory::create();
    \$result = new static();
    {$obj->fakeColumns}
    return \$result;
}
}
EOT;
