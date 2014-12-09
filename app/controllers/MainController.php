<?php

class MainController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function indexPage()
    {
        return View::make('index');
    }
}
 