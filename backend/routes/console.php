<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command(
    'member:reset-pembayaran'
)->dailyAt('00:01');