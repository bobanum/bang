<?php
$tmpl = <<<EOT
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class $table->model extends Model
{
	{$table->forcedName}{$table->fillable}//
	{$table->forcedName}{$table->fillable}//
	{$table->forcedName}{$table->fillable}//
}
EOT;