@echo off
REM Batch file to send meeting reminders via web request
REM This can be scheduled with Windows Task Scheduler

echo Sending meeting reminders...
curl -s "http://localhost/EllieSecre/beholf/public/index.php/cron/send-meeting-reminders" > nul
echo Reminders sent.
