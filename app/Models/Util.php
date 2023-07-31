<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class Util
{
    public static function getRemoteAddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        return null;
    }

    public static function log($message, $error = true, $email = false)
    {
        /*
        Show as Error in Windows Event Log:
        syslog(LOG_EMERG, "system is unusable");
        syslog(LOG_ALERT, "action must be taken immediately");
        syslog(LOG_CRIT, "critical conditions");

        Show as Warning in Windows Event Log:
        syslog(LOG_ERR, "error conditions");
        syslog(LOG_WARNING, "warning conditions");

        Show as Information in Windows Event Log:
        syslog(LOG_NOTICE, "normal, but significant, condition");
        syslog(LOG_INFO, "informational message");
        syslog(LOG_DEBUG, "debug-level message");
        */

        $level = LOG_INFO;
        if ($error) $level = LOG_ALERT;

        $emailsSentFilename = 'email-count-' . date('Y-m-d') . '.txt';
        if (!Storage::disk('local')->exists($emailsSentFilename))
            Storage::disk('local')->put($emailsSentFilename, '0');
        $emailsSentToday = Storage::disk('local')->get($emailsSentFilename);

        $traceFormatted = '';
        $trace = debug_backtrace();
        for ($i = 0; $i < count($trace); $i++) // Row 0 is the reportError() call
        {
            if (isset($trace[$i]['file']) && isset($trace[$i]['line'])) {
                $traceFormatted .= "{$trace[$i]['file']}({$trace[$i]['line']})";
                if ($i < count($trace) - 1)
                    $traceFormatted .= "\n\t in ";
            }
        }

        $ipAddress = Util::getRemoteAddress();
        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $userID = '';
        if (Auth::user() != null) $userID = Auth::user()->UserID;

        openlog('[USACE Comms]', LOG_PERROR, 0);
        syslog($level, "Message: {$message}\nClient IP: {$ipAddress}\nBrowser: {$browser}\nUserID: {$userID}\nTrace: {$traceFormatted}\n");
        closelog();

        $limit = intval(env('MAIL_ERROR_DAILY_LIMIT'));
        if ($email && $emailsSentToday < $limit) {
            $emailsSentToday++;
            $traceFormatted = str_replace('\n', '<br>', $traceFormatted);
            $data = ['error' => $message, 'trace' => $traceFormatted];
            Mail::send('emails.error', $data, function ($headers) use ($data) {
                $to = env('MAIL_ERROR_TOADDRESS');
                if (str_contains($to, ';')) {
                    $to = explode(';', $to);
                }
                $headers->to($to)
                    ->subject(strtolower(env('APP_ENV')) != 'production' ? 'TESTING - USACE Comms Error' : 'USACE Comms Error');
                //$headers->from('support@egsnetwork.com');
            });
            Storage::disk('local')->put($emailsSentFilename, $emailsSentToday);
        }
    }

    public static function formatPhoneNumber($phone_number)
    {
        if ($phone_number) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $phone_number);
            if (strlen($cleaned) == 10) {
                preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
            } else {
                return $cleaned;
            }
        } else {
            return '';
        }
    }

    public static function formatCurrency($value, $commas = false, $default = '')
    {
        if (is_null($value)) {
            return $default;
        }
        if (is_string($value)) {
            $value = floatval(str_replace(',', '', $value));
        }
        return number_format($value, 2, '.', $commas ? ',' : '');
    }

    /*
     * Adapted from \Illuminate\Database\Eloquent\Builder::paginate()
     *
     * The main change, other than accepting query as a parameter, is that is passes $columns to getCountForPagination
     * See https://github.com/laravel/framework/pull/9385 for why this isn't in Laravel
     */
    public static function paginateDistinctQuery($query, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $query->getModel()->getPerPage();

        $results = ($total = $query->toBase()->getCountForPagination($columns))
            ? $query->forPage($page, $perPage)->get($columns)
            : $query->getModel()->newCollection();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    public static function formatTimezoneACP121I($timezone)
    {
        $timezones = [
            '+0100' => 'A',
            '+0200' => 'B',
            '+0300' => 'C',
            '+0400' => 'D',
            '+0500' => 'E',
            '+0600' => 'F',
            '+0700' => 'G',
            '+0800' => 'H',
            '+0900' => 'I',
            '+1000' => 'K',
            '+1100' => 'L',
            '+1200' => 'M',
            '-0100' => 'N',
            '-0200' => 'O',
            '-0300' => 'P',
            '-0400' => 'Q',
            '-0500' => 'R',
            '-0600' => 'S',
            '-0700' => 'T',
            '-0800' => 'U',
            '-0900' => 'V',
            '-1000' => 'X',
            '-1100' => 'X',
            '-1200' => 'Y',
            '-0000' => 'Z',
        ];
        return Arr::get($timezones, $timezone, '');
    }

    public static function getTimezoneACP121I(\DateTime $dateTime)
    {
        return Util::formatTimezoneACP121I($dateTime->format('O'));
    }
}
