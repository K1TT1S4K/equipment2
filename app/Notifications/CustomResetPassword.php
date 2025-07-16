<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    /**
     * โทเค็นรีเซ็ตรหัสผ่าน
     *
     * @var string
     */
    public $token;

    /**
     * สร้าง Notification พร้อมรับ token
     *
     * @param  string  $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * ช่องทางการแจ้งเตือน
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * สร้างอีเมลแจ้งรีเซ็ตรหัสผ่าน
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        // สร้างลิงก์รีเซ็ต
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('แจ้งรีเซ็ตรหัสผ่านใหม่')
            ->greeting('เรียนคุณ ' . ($notifiable->prefix->name . ' ' . $notifiable->firstname . ' ' . $notifiable->lastname))
            ->line('ทางระบบได้รับคำร้องขอให้รีเซ็ตรหัสผ่านสำหรับบัญชีผู้ใช้ของท่าน')
            ->line('กรุณาคลิกที่ปุ่มด้านล่างเพื่อดำเนินการตั้งรหัสผ่านใหม่')
            ->action('ตั้งรหัสผ่านใหม่', $url)
            ->line('ลิงก์สำหรับรีเซ็ตรหัสผ่านนี้จะหมดอายุภายใน 60 นาที')
            ->line('หากท่านไม่ได้ร้องขอการรีเซ็ตรหัสผ่าน กรุณาเพิกเฉยต่ออีเมลฉบับนี้')
            ->salutation('ขอแสดงความนับถือ');
    }

    /**
     * Array representation (ถ้าต้องการเก็บ log หรือส่งช่องทางอื่น)
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
