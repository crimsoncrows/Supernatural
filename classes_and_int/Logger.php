<?php

trait Logger {

    public function battleLog($msg) {
        if (!isset($_SESSION['battlelog'])) {
            $_SESSION['battlelog'] = [];
        }
        $_SESSION['battlelog'][] = $msg;
    }
}