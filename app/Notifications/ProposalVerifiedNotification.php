<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ProposalVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(public Proposal $proposal) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $p = $this->proposal;

        return (new MailMessage)
            ->subject('Selamat! Pengajuan Skripsi Disetujui (Verified) 🎉')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Mahasiswa') . ' 👋')
            
            // Jangan pakai <div> atau <img> di sini lagi karena sudah ada di email.blade.php
            ->line('Kabar baik! Pengajuan dosen pembimbing kamu telah ditinjau dan dinyatakan **DISETUJUI (VERIFIED)**.')
            
            ->line('**Ringkasan Pengajuan:**')
            ->line('• **Judul:** "' . ($p->title ?? '-') . '"')
            ->line('• **Bidang:** ' . (optional($p->topic)->name ?? '-'))
            ->line('• **Pembimbing:** ' . (optional($p->selectedDosen)->name ?? '-'))
            
            ->line('Silakan segera menghubungi dosen pembimbing terkait untuk melakukan bimbingan awal.')
            
            ->action('Buka Dashboard Mahasiswa', route('student.dashboard'))
            
            ->line('Selamat berkarya!')
            
            // Gunakan HtmlString hanya untuk penutup agar rapi
            ->salutation(new HtmlString('**Kaprodi Fasilkom UNSIKA**<br><span style="font-size: 10px; color: #94a3b8;">Sistem Pengajuan Dosen Pembimbing</span>'));
    }
}