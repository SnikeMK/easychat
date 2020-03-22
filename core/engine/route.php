<?php
class Route {
	static function start() {
		$registry = new Registry();
		$registry->set('view', new View());
		$registry->set('log', new Log('123.log'));
		$registry->set('db', new DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT));

		$loader = new Loader($registry);
		$registry->set('load', $loader);
		// контроллер и действие по умолчанию
		$controller_name = 'Main';
		$action_name = 'index';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		// получаем имя контроллера
		if ( !empty($routes[1]) )
		{	
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		if ( !empty($routes[2]) )
		{
			$action_name = $routes[2];
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = DIR_CONTROLLER . $controller_file;
		if(file_exists($controller_path))
		{
			include DIR_CONTROLLER . $controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			die ('404 not found');
		}
		
		// добавляем префиксы
		$controller_name = 'Controller' . $controller_name;

		// создаем контроллер
		$controller = new $controller_name($registry);
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			die ('404 not found');
		}
	
	}
}