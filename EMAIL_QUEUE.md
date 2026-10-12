# Email queue operations

The portal queues email in the `email_queue` database table. Web requests do not send SMTP mail directly.

## Start the worker

After MySQL is running and `SMTP_PASSWORD` is set in `.env`, run this from the project folder:

```powershell
php .\send_email_queue.php 50
```

The optional number is the maximum messages processed in one run. The worker sends at the rate configured by `MAIL_QUEUE_DELAY_US` in `.env`; `300000` is roughly 3 messages per second.

## Schedule on Windows

Create a Windows Task Scheduler task every minute:

- Program: `C:\xampp\php\php.exe`
- Arguments: `D:\xampp\htdocs\cms\send_email_queue.php 50`
- Start in: `D:\xampp\htdocs\cms`

Use one worker task initially. The Admin > Email Queue page shows pending, sent, processing, and failed deliveries. Failed items retry up to five times with a delay.
