<?php
namespace Bang;

use PDO;
use Exception;

class Table {
	use GetSet;
	use Table_Aliases;
	use Table_Proxies;
	use Table_Analyze;
	public $messages = [];
	public $bang = null;

	function __construct() {
		
	}
	function get_fillableColumns() {
		$result = array_filter($this->_columns, function ($col) { 
			return $col->name !== "id";
		});
		return $result;
	}
	function report($width=65) {
		//║╗╝╚╔═╣╠╬╪╤╧
		$hr = "╔".str_repeat("═", $width-30)."";
		$hr .= "╤".str_repeat("═", 22)."";
		$hr .= "╤".str_repeat("═", 4)."";
		$hr .= "╗";
		$result[] = $hr;
		$h = "";
		$h .= "║ ".str_pad("COLUMN NAME", $width-31, " ");
		$h .= "│ ".str_pad("TYPE", 21, " ");
		$h .= "│ PK ║";
		$result[] = $h;
		$hr = "╠".str_repeat("═", $width-30)."";
		$hr .= "╪".str_repeat("═", 22)."";
		$hr .= "╪".str_repeat("═", 4)."";
		$hr .= "╣";
		$result[] = $hr;
		foreach ($this->_columns as $column) {
			$result[] = $column->report_line();
		}
		$hr = "╚".str_repeat("═", $width-30)."";
		$hr .= "╧".str_repeat("═", 22)."";
		$hr .= "╧".str_repeat("═", 4)."";
		$hr .= "╝";
		$result[] = $hr;
		$result = implode("\r\n", $result);
		return $result;
	}
	function go() {
		$name = $this->name;
		$this->messages[] = "• Processing Table '$name'.";
		// $this->messages[] = $this->report();
		$this->processRoutes();
		$this->processModel();
		$this->processController();
		$this->processViews();
	}
	public function processRoutes() {
		$path = "routes/web.php";
		$path = $this->bang->output_path($path);
		$web = file_get_contents($path);
		//Remove old routes
		$head = "\r\n//route for {$this->model}\r\n";
		$foot = "\r\n//endroute\r\n";
		$pattern = '#'.preg_quote($head).'.*'.preg_quote($foot).'#ms';
		$web = preg_replace($pattern, "", $web);
		//Add new routes
		$routes = $this->bang->applyTemplate("routes_web.php", $this);
		$routes = $head.$routes.$foot;
		$web .= $routes;
		file_put_contents($path, $web);
		$this->messages[] = "• Creating routes un file 🗎'{$path}'\r\n  using Model 🖼'{$this->model}' from template.";
	}
	public function processModel() {
		$path = "app/{$this->model}.php";
		$path = $this->bang->output_path($path);
		$this->bang->applyTemplate("model.php", $this, $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Model 🖼'{$this->model}' from template.";
	}
	public function processController() {
		$path = "app/Http/Controllers/{$this->controller}.php";
		$path = $this->bang->output_path($path);
		$this->bang->applyTemplate("controller.php", $this, $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Controller 🖰'$this->controller' from template.";
	}
	public function processViews()
	{
		$views = $this->bang->template_path("view_table_*");
		$views = $this->bang->glob($views);
		foreach ($views as $view) {
			$path = basename($view);
			$path = substr($path, 0, -4);
			$path = explode("_", $path);
			array_shift($path);
			$path[0] = $this->singular;
			$path = implode("/", $path);
			$path = "resources/views/{$path}.blade.php";
			$path = $this->bang->output_path($path);
			$this->bang->applyTemplate($view, $this, $path);
			$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'{$this->singular}.index' from template.";

		}
	}

}
