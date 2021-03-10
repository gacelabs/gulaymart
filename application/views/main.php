<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->view('static/top', ['top' => $view['top'], 'data' => $data]);?>
<?php $this->view('static/middle', ['middle' => $view['middle'], 'data' => $data]);?>
<?php $this->view('static/bottom', ['bottom' => $view['bottom'], 'data' => $data]);?>