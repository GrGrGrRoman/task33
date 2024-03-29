<?php

class Helpers
{
    function d($str): void
	{
		echo '<pre>';
		var_dump($str);
		echo '</pre>';
	}
			
	function dd($str): never
    {
        echo '<pre>';
        var_dump($str);
        echo '</pre>';
        exit;
    }

    static public function get_errors(array $errors): string
    {
        $html = '<ul class="list-unstyled">';
            foreach ($errors as $error)
            {
                $html .= "<li>{$error}</li>";
            }
        $html .= '</ul>';
        return $html;
    }

    static public function get_alert(): void
    {
        if (!empty($_SESSION['errors']))
        {
            $errors = self::get_errors($_SESSION['errors']);
            require 'lib/danger.phtml';
            unset($_SESSION['errors']);
        }

        if (!empty($_SESSION['success']))
        {
            $msg = self::get_errors($_SESSION['success']);
            require 'lib/success.phtml';
            unset($_SESSION['success']);
        }
    }
}