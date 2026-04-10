<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalRejectedNotification extends Notification
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
            ->subject('Update Pengajuan Skripsi: DITOLAK ❌')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Mahasiswa') . ' 👋')
            ->line('Mohon maaf, pengajuan dosen pembimbing kamu telah ditinjau dan dinyatakan **DITOLAK (REJECTED)**.')
            
            // Menggunakan format markdown standar (paling aman, pasti muncul)
            ->line('**Detail Pengajuan:**')
            ->line('• Judul: "' . ($p->title ?? '-') . '"')
            ->line('• Bidang Minat: ' . (optional($p->topic)->name ?? '-'))
            
            // Bagian Alasan Penolakan (Tanpa HTML, pakai Bold saja)
            ->line('---')
            ->line('**⚠️ ALASAN PENOLAKAN:**')
            ->line($p->rejection_reason ?? 'Silakan cek kembali kelengkapan dokumen atau konsultasikan judul kamu kembali dengan prodi.')
            ->line('---')

            ->action('Lihat Detail di Dashboard', route('student.dashboard'))
            ->line('Jika ada pertanyaan lebih lanjut, silakan hubungi bagian akademik.')
            ->salutation('Salam hangat,' . "\r\n" . 'Kaprodi FASILKOM UNSIKA');
    }
}