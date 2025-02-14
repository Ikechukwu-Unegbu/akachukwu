<?php

namespace App\Services\Health;

use Illuminate\Support\Facades\DB;



class SystemHealthService
{
        /**
     * Get CPU Load (1-minute average).
     */
    // public function getCpuUsage(): float
    // {
    //     return sys_getloadavg()[0];
    // }
    public function getCpuUsage(): float
    {
        $os = strtoupper(PHP_OS);
    
        if (str_contains($os, 'WIN')) {
            return $this->getWindowsCpuUsage();
        } elseif (str_contains($os, 'DARWIN') || str_contains($os, 'LINUX')) {
            return $this->getUnixCpuUsage();
        }
    
        return 0.0;
    }
    
    private function getWindowsCpuUsage(): float
    {
        $command = 'powershell -command "Get-Counter \'\\Processor(_Total)\\% Processor Time\' | Select-Object -ExpandProperty CounterSamples | Select-Object -ExpandProperty CookedValue"';
        $output = shell_exec($command);
        
        return $output ? round(floatval($output), 2) : 0.0;
    }
    
    private function getUnixCpuUsage(): float
    {
        $load = sys_getloadavg();
        return is_array($load) ? round($load[0], 2) : 0;
    }
    
    /**
     * Get Memory Usage in MB.
     */
    public function getMemoryUsage(): array
    {
        return [
            'current' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'peak'    => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
        ];
    }

    /**
     * Get Disk Usage.
     */
    public function getDiskUsage(): array
    {
        $total = disk_total_space('/');
        $free  = disk_free_space('/');
        $used  = $total - $free;
        $percent = ($used / $total) * 100;

        return [
            'total'     => round($total / 1024 / 1024 / 1024, 2) . ' GB',
            'used'      => round($used / 1024 / 1024 / 1024, 2) . ' GB',
            'free'      => round($free / 1024 / 1024 / 1024, 2) . ' GB',
            'usage_pct' => round($percent, 2) . '%',
        ];
    }

    public function getMemoryInfo(): array
    {
        $totalMemory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $usedMemory = round(memory_get_usage(false) / 1024 / 1024, 2);
        $freeMemory = round(($totalMemory - $usedMemory), 2);
        $percentUsed = $totalMemory > 0 ? round(($usedMemory / $totalMemory) * 100, 2) : 0;
        
        return [
            'total_memory' => $totalMemory . ' MB',
            'used_memory'  => $usedMemory . ' MB',
            'free_memory'  => $freeMemory . ' MB',
            'usage_pct'    => $percentUsed . '%',
        ];
    }


    /**
     * Get Active Queue Workers.
     */
    public function getActiveWorkers(): int
    {
        return (int) trim(shell_exec("ps aux | grep 'queue:work' | grep -v grep | wc -l"));
    }

    /**
     * Get Active Database Connections.
     */
    public function getDatabaseConnectionStatus(): array
    {
        $connections = DB::select("SHOW STATUS WHERE `variable_name` = 'Threads_connected'");
        $dbConnections = (int) $connections[0]->Value;
    
        $status = $dbConnections > 0 ? 'Connected' : 'Disconnected';
    
        return [
            'db_connections' => $dbConnections,
            'status' => $status,
        ];
    }
    
    /**
     * Get overall system health report.
     */
    public function getSystemHealth(): array
    {
        return [
            'cpu_load'         => $this->getCpuUsage() . '%',
            'memory_usage'     => $this->getMemoryUsage(),
            'disk_usage'       => $this->getDiskUsage(),
            'db_connections'   => $this->getDatabaseConnectionStatus(),
            'queue_workers'    => $this->getActiveWorkers(),
            'memory'=>$this->getMemoryInfo()
        ];
    }


}
